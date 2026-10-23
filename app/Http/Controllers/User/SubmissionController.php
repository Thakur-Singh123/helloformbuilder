<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormSubmission;
use App\Models\CustomForm;
use App\Models\Client;
use App\Models\Shortcode;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class SubmissionController extends Controller
{
    /**
     * Get submissions query with proper data isolation
     */
    protected function getSubmissionsQuery()
    {
        $user = currentUser();
        $query = FormSubmission::with(['customForm', 'formVersion', 'shortcode', 'invoice']);

        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isAdmin()) {
            return $query->where(function ($q) use ($user) {
                $q->where('admin_id', $user->id)
                    ->orWhereHas('shortcode', fn ($q2) => $q2->where('user_id', $user->id))
                    ->orWhereHas('client', fn ($q2) => $q2->where('admin_id', $user->id));
            });
        }

        if ($user->isClient()) {
            return $query->where('client_id', $user->id);
        }

        return $query->where('admin_id', $user->admin_id)
            ->where('user_id', $user->id);
    }

    /**
     * Display a listing of submissions.
     */
    public function index(Request $request)
    {
        $user = currentUser();
        if (!$user) {
            abort(403, 'You must be logged in to view submissions.');
        }
        Gate::forUser($user)->authorize('viewAny', FormSubmission::class);

        $query = $this->getSubmissionsQuery();

        if ($request->filled('form_id')) {
            $query->where('custom_form_id', $request->form_id);
        }

        if ($request->filled('client_id') && ($user->isAdmin() || $user->isSuperAdmin())) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('shortcode_id')) {
            $query->where('shortcode_id', $request->shortcode_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status') && $user->isAdmin()) {
            $query->where('status', $request->status);
        }

        $submissions = $query->latest('created_at')->paginate(20)->withQueryString();

        $formsQuery = CustomForm::query();
        if ($user->isSuperAdmin()) {
            // all forms
        } elseif ($user->isAdmin()) {
            $formsQuery->where('admin_id', $user->id);
        } elseif ($user->isClient()) {
            $formsQuery->whereHas('submissions', fn ($q) => $q->where('client_id', $user->id));
        } else {
            $formsQuery->where('admin_id', $user->admin_id);
        }
        $forms = $formsQuery->latest()->get();

        $clients = collect();
        if ($user->isAdmin()) {
            $clients = Client::where('admin_id', $user->id)->orderBy('name')->get();
        } elseif ($user->isSuperAdmin()) {
            $clients = Client::orderBy('name')->get();
        }

        $shortcodesQuery = Shortcode::with('customForm')->whereHas('customForm');
        if ($user->isSuperAdmin()) {
            // all shortcodes
        } elseif ($user->isAdmin()) {
            $shortcodesQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('client', fn ($q2) => $q2->where('admin_id', $user->id));
            });
        } else {
            $shortcodesQuery->whereRaw('1 = 0');
        }
        if ($request->filled('client_id')) {
            $shortcodesQuery->where('client_id', $request->client_id);
        }
        $shortcodes = $shortcodesQuery->orderBy('id', 'desc')->get();

        $unreadCount = $this->getSubmissionsQuery()->whereNull('read_at')->count();

        $statusCounts = [
            'all' => 0,
            'pending' => 0,
            'hired' => 0,
            'cancelled' => 0,
        ];
        if ($user->isAdmin()) {
            $baseQuery = $this->getSubmissionsQuery();
            $statusCounts['all'] = (clone $baseQuery)->count();
            $statusCounts['pending'] = (clone $baseQuery)->where('status', FormSubmission::STATUS_PENDING)->count();
            $statusCounts['hired'] = (clone $baseQuery)->where('status', FormSubmission::STATUS_HIRED)->count();
            $statusCounts['cancelled'] = (clone $baseQuery)->where('status', FormSubmission::STATUS_CANCELLED)->count();
        }

        return view('user.submissions.index', compact('submissions', 'forms', 'clients', 'shortcodes', 'unreadCount', 'statusCounts'));
    }

    /**
     * Display the specified submission. Mark as read when viewed.
     */
    public function show(FormSubmission $submission)
    {
        $user = currentUser();
        if (!$user) {
            abort(403, 'You must be logged in to view this submission.');
        }

        $submission = $this->getSubmissionsQuery()
            ->whereKey($submission->id)
            ->firstOrFail();

        if ($submission->read_at === null) {
            $submission->update(['read_at' => now()]);
        }

        $submission->load(['customForm', 'formVersion', 'shortcode', 'admin', 'user', 'client', 'invoice']);

        return view('user.submissions.show', compact('submission'));
    }

    /**
     * Remove the specified submission.
     */
    public function destroy(FormSubmission $submission)
    {
        $user = currentUser();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }
        Gate::forUser($user)->authorize('delete', $submission);

        $submission->delete();

        return redirect()->route('submissions.index')
            ->with('success', 'Submission deleted successfully.');
    }

    public function hire(FormSubmission $submission)
    {
        $user = currentUser();
        abort_unless($user && $user->isAdmin(), 403);

        $submission = $this->getSubmissionsQuery()
            ->whereKey($submission->id)
            ->firstOrFail();

        if (!$submission->isPending()) {
            $invoice = Invoice::where('form_submission_id', $submission->id)->first()
                ?: Invoice::createDraftForSubmission($submission);

            return redirect()
                ->route('invoices.edit', $invoice)
                ->with('success', 'This lead was already hired. You can resend the invoice from this page.');
        }

        $submission->update(['status' => FormSubmission::STATUS_HIRED]);

        if (!$submission->admin_id) {
            $submission->loadMissing(['customForm', 'shortcode']);
            $adminId = optional($submission->customForm)->admin_id
                ?? optional($submission->shortcode)->user_id
                ?? optional(currentUser())->id;
            if ($adminId) {
                $submission->update(['admin_id' => $adminId]);
                $submission->refresh();
            }
        }

        $invoice = Invoice::createDraftForSubmission($submission);

        return redirect()
            ->route('invoices.edit', $invoice)
            ->with('success', 'Lead marked as hired. Draft invoice created - set the amount and send.');
    }

    public function cancel(FormSubmission $submission)
    {
        $user = currentUser();
        abort_unless($user && $user->isAdmin(), 403);

        $submission = $this->getSubmissionsQuery()
            ->whereKey($submission->id)
            ->firstOrFail();

        abort_unless($submission->isPending(), 422, 'This lead has already been processed.');

        $submission->update(['status' => FormSubmission::STATUS_CANCELLED]);

        return back()->with('success', 'Lead cancelled.');
    }

    /**
     * Send a reply email to the form lead from the admin panel.
     */
    public function reply(Request $request, FormSubmission $submission)
    {
        $user = currentUser();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $submission = $this->getSubmissionsQuery()
            ->whereKey($submission->id)
            ->firstOrFail();

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
        ]);

        $toEmail = $submission->visitor_email;
        if (!$toEmail) {
            return back()->with('error', 'This lead has no email address.');
        }

        $toName = $submission->visitor_name;
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        try {
            $settings = DB::table('settings')->first();
            if ($settings) {
                if (!empty($settings->sender_email)) {
                    $fromAddress = $settings->sender_email;
                }
                if (!empty($settings->sender_name)) {
                    $fromName = $settings->sender_name;
                }
            }
        } catch (\Throwable $e) {
            // settings table may not exist
        }

        $senderName = $user->first_name ?? $user->name ?? $fromName;
        $replyTo = $user->email ?? $fromAddress;

        $emailData = [
            'visitor_name' => $toName ?: 'there',
            'message_body' => $data['message'],
            'sender_name' => $senderName,
            'form_name' => $submission->customForm->name ?? null,
        ];

        try {
            $html = view('email.lead_reply', $emailData)->render();

            Mail::html($html, function ($message) use ($toEmail, $toName, $data, $fromAddress, $fromName, $replyTo, $senderName) {
                $message->to($toEmail, $toName ?: $toEmail)
                    ->subject($data['subject'])
                    ->from($fromAddress, $fromName);

                if ($replyTo) {
                    $message->replyTo($replyTo, $senderName);
                }
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        return back()->with('success', 'Reply sent to ' . $toEmail);
    }
}