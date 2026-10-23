<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Shortcode;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use DB;

/**
 * Admin (company) only: manage their Clients (website owners). Basic info only; no email inbox access.
 */
class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function authorizeAdmin(): void
    {
        $user = currentUser();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Only Admin can manage clients.');
        }
    }

    private function getSettings()
    {
        try {
            $s = DB::table('settings')->first();
        } catch (\Exception $e) {
            $s = null;
        }
        return $s ?: (object) ['site_title' => config('app.name', 'LeadMatrix'), 'icon' => 'favicon.ico'];
    }

    /**
     * List clients (basic info only).
     */
    public function index()
    {
        $this->authorizeAdmin();
        $user = currentUser();
        $clients = Client::where('admin_id', $user->id)->orderBy('name')->get();
        $settings = $this->getSettings();
        return view('admin.clients.index', compact('settings', 'clients'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $settings = $this->getSettings();
        return view('admin.clients.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $admin = currentUser();
        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('clients', 'email')->where('admin_id', $admin->id),
                Rule::unique('users', 'email'),
            ],
            'website' => 'nullable|string|max:500',
            'password' => ['required', 'string', 'min:6', 'confirmed', 'different:email'],
        ], [
            'password.different' => 'The password cannot be the same as the email address. Please choose a different password for security.',
            'email.unique' => 'This email is already registered as an admin or user. Clients must use a different email address so they can log in to their client account.',
        ]);
        $client = new Client();
        $client->name = $request->name;
        $client->first_name = $request->first_name ?? $request->name;
        $client->last_name = $request->last_name ?? '';
        $client->email = $request->email;
        $client->website = $request->filled('website') ? trim($request->website) : null;
        $client->password = $request->password;
        $client->admin_id = $admin->id;
        $client->is_active = true;
        $client->email_verified_at = now(); // Admin-created accounts can log in immediately
        $client->save();

        // Send welcome email with login link and credentials
        try {
            $settings = $this->getSettings();
            $mailData = [
                'client_name'  => $client->name ?? $client->first_name . ' ' . $client->last_name ?: $client->email,
                'client_email' => $client->email,
                'password'     => $request->password,
                'login_url'    => route('login'),
                'site_name'    => $settings->site_title ?? config('app.name'),
            ];
            Mail::send('email.client_welcome', $mailData, function ($message) use ($client) {
                $message->to($client->email, $client->name ?? $client->email)
                    ->subject('Your client account – sign in details')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send client welcome email: ' . $e->getMessage());
        }

        return redirect()->route('admin.clients.index')->with('success', 'Client created successfully. A welcome email with login details has been sent to the client.');
    }

    public function edit($id)
    {
        $this->authorizeAdmin();
        $admin = currentUser();
        $client = Client::where('id', $id)->where('admin_id', $admin->id)->firstOrFail();
        $settings = $this->getSettings();
        return view('admin.clients.edit', compact('settings', 'client'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();
        $admin = currentUser();
        $client = Client::where('id', $id)->where('admin_id', $admin->id)->firstOrFail();
        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('clients', 'email')->where('admin_id', $admin->id)->ignore($client->id),
                Rule::unique('users', 'email'),
            ],
            'website' => 'nullable|string|max:500',
            'password' => ['nullable', 'string', 'min:6', 'confirmed', 'different:email'],
        ], [
            'password.different' => 'The password cannot be the same as the email address. Please choose a different password for security.',
            'email.unique' => 'This email is already registered as an admin or user. Clients must use a different email address so they can log in to their client account.',
        ]);
        $client->name = $request->name;
        $client->first_name = $request->first_name ?? $request->name;
        $client->last_name = $request->last_name ?? '';
        $client->email = $request->email;
        $client->website = $request->filled('website') ? trim($request->website) : null;
        if ($request->filled('password')) {
            $client->password = $request->password;
        }
        if (!$client->email_verified_at) {
            $client->email_verified_at = now(); // Allow admin to "fix" clients created before welcome-email flow
        }
        $client->save();
        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Show client detail, shortcodes assigned to this client, and submissions for this client.
     */
    public function show($id)
    {
        $this->authorizeAdmin();
        $admin = currentUser();
        $client = Client::where('id', $id)->where('admin_id', $admin->id)
            ->with(['shortcodes' => function ($q) {
                $q->with('customForm')->orderBy('shortcodes.id', 'desc');
            }])
            ->firstOrFail();
        $submissions = FormSubmission::where('client_id', $client->id)
            ->with(['customForm', 'shortcode'])
            ->latest()
            ->limit(50)
            ->get();
        $settings = $this->getSettings();
        return view('admin.clients.show', compact('settings', 'client', 'submissions'));
    }

    /**
     * Delete a client. Shortcodes assigned to this client will have client_id set to null.
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();
        $admin = currentUser();
        $client = Client::where('id', $id)->where('admin_id', $admin->id)->firstOrFail();
        Shortcode::where('client_id', $client->id)->update(['client_id' => null]);
        $client->delete();
        return response()->json(['success' => true, 'message' => 'Client deleted.']);
    }
}
