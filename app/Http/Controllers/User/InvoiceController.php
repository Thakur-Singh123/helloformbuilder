<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceSendLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    protected function getInvoicesQuery()
    {
        $user = currentUser();
        $query = Invoice::with(['submission.customForm', 'client']);

        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isAdmin()) {
            return $query->where('admin_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public function index(Request $request)
    {
        $user = currentUser();
        abort_unless($user && ($user->isAdmin() || $user->isSuperAdmin()), 403);

        $query = $this->getInvoicesQuery()->withCount('sendLogs');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('invoice_number', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_email', 'like', "%{$q}%")
                    ->orWhere('customer_phone', 'like', "%{$q}%");
            });
        }

        $invoices = $query->latest('created_at')->paginate(20)->withQueryString();

        $base = $this->getInvoicesQuery();
        $statusCounts = [
            'all' => (clone $base)->count(),
            'draft' => (clone $base)->where('status', Invoice::STATUS_DRAFT)->count(),
            'sent' => (clone $base)->where('status', Invoice::STATUS_SENT)->count(),
        ];

        return view('user.invoices.index', compact('invoices', 'statusCounts'));
    }

    public function edit(Invoice $invoice)
    {
        $user = currentUser();
        abort_unless($user && ($user->isAdmin() || $user->isSuperAdmin()), 403);

        $invoice = $this->getInvoicesQuery()
            ->whereKey($invoice->id)
            ->firstOrFail();

        $invoice->load(['submission.customForm', 'client', 'sendLogs']);

        return view('user.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $user = currentUser();
        abort_unless($user && ($user->isAdmin() || $user->isSuperAdmin()), 403);

        $invoice = $this->getInvoicesQuery()
            ->whereKey($invoice->id)
            ->firstOrFail();

        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'required|in:INR,USD',
            'description' => 'nullable|string|max:5000',
        ]);

        $invoice->update($data);

        return back()->with('success', 'Invoice updated.');
    }

    public function send(Invoice $invoice)
    {
        $user = currentUser();
        abort_unless($user && ($user->isAdmin() || $user->isSuperAdmin()), 403);

        $invoice = $this->getInvoicesQuery()
            ->whereKey($invoice->id)
            ->firstOrFail();

        if (empty($invoice->customer_email)) {
            return back()->with('error', 'Customer email is required before sending.');
        }

        if ($invoice->amount === null) {
            return back()->with('error', 'Please set an amount before sending the invoice.');
        }

        $isResend = $invoice->isSent() || $invoice->sendLogs()->exists();

        $emailData = [
            'invoice' => $invoice,
            'subject' => 'Invoice ' . $invoice->invoice_number,
        ];

        Mail::send('email.invoice', $emailData, function ($message) use ($invoice, $emailData) {
            $message->to($invoice->customer_email, $invoice->customer_name ?: 'Customer')
                ->subject($emailData['subject']);
        });

        $invoice->update([
            'status' => Invoice::STATUS_SENT,
            'sent_at' => now(),
        ]);

        InvoiceSendLog::create([
            'invoice_id' => $invoice->id,
            'sent_to_email' => $invoice->customer_email,
            'sent_by' => optional($user)->id,
            'amount' => $invoice->amount,
            'currency' => $invoice->currency ?? 'INR',
            'is_resend' => $isResend,
            'created_at' => now(),
        ]);

        $msg = $isResend
            ? 'Invoice resent to ' . $invoice->customer_email
            : 'Invoice sent to ' . $invoice->customer_email;

        return back()->with('success', $msg . ' See Send History below.');
    }
}
