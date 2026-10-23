<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shortcode;
use App\Models\CustomForm;
use App\Models\FormSubmission;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * ShortcodeController
 * 
 * Handles creation and rendering of shortcodes for embedding forms.
 * Only logged-in users can create shortcodes.
 */
class ShortcodeController extends Controller
{
    /**
     * Show the form to create a new shortcode.
     * Only accessible to logged-in users.
     * Optional query: form_id to pre-select a form (e.g. after creating a new form).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $user = currentUser();
        if (!$user) {
            abort(403, 'You must be logged in to create shortcodes.');
        }
        // Clients cannot create shortcodes; only view those assigned to them
        if ($user->isClient()) {
            return redirect()->route('shortcodes.index')->with('info', 'Only your admin can create shortcodes. You can view shortcodes assigned to you below.');
        }

        // Choose template: show shared templates only (same for all admins)
        $forms = CustomForm::where('status', 'active')
            ->where('is_global', true)
            ->with(['fields', 'latestVersion'])
            ->latest()
            ->get();

        $formsForPreview = $forms->map(function ($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'fields' => $f->fields->sortBy('order')->values()->map(function ($field) {
                    return [
                        'field_name' => $field->field_name,
                        'field_key' => $field->field_key,
                        'field_type' => $field->field_type,
                        'is_required' => (bool) $field->is_required,
                    ];
                })->toArray(),
            ];
        })->values()->toArray();

        $selectedFormId = $request->get('form_id');
        if ($selectedFormId && !$forms->contains('id', (int) $selectedFormId)) {
            $selectedFormId = null;
        }

        // Admins can assign shortcode to a client (website owner)
        $clients = collect();
        if ($user->isAdmin()) {
            $clients = Client::where('admin_id', $user->id)->orderBy('name')->get();
        }

        return view('user.shortcode.create', compact('forms', 'formsForPreview', 'selectedFormId', 'clients'));
    }

    /**
     * Store a new shortcode.
     * Only accessible to logged-in users.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = currentUser();
        if (!$user || $user->isClient()) {
            return response()->json(['success' => false, 'message' => 'You must be logged in to create shortcodes.'], 403);
        }

        // Validate the request (client_id optional: assign shortcode to a client/website)
        $request->validate([
            'custom_form_id' => 'required|exists:custom_forms,id',
            'client_id' => 'nullable|exists:clients,id',
            'settings' => 'nullable|json',
        ]);

        $settings = $this->normalizeShortcodeSettings($request->input('settings'));

        // Verify the form belongs to the logged-in user with proper authorization
        $form = CustomForm::where('id', $request->custom_form_id)->first();

        if (!$form) {
            return response()->json(['success' => false, 'message' => 'Form not found.'], 404);
        }

        // Check authorization: allow if form is global (shared) or belongs to this user/admin
        if (!$form->is_global) {
            if ($user->isSuperAdmin()) {
                // Super Admin can create shortcodes for any form
            } elseif ($user->isAdmin() && $form->admin_id != $user->id) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to create shortcodes for this form.'], 403);
            } elseif (!$user->isSuperAdmin() && $user->isRegularUser() && $form->admin_id != $user->admin_id) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to create shortcodes for this form.'], 403);
            }
        }

        // Get the latest active version of the form
        $version = $form->latestVersion;
        if (!$version) {
            // Create initial version if none exists
            $version = $form->createVersion($user->id, 'Initial version for shortcode');
        }

        // If client_id provided, ensure it belongs to this Admin
        $clientId = $request->client_id ? (int) $request->client_id : null;
        if ($clientId && $user->isAdmin()) {
            $client = Client::where('id', $clientId)->where('admin_id', $user->id)->first();
            if (!$client) {
                $clientId = null;
            }
        } elseif ($clientId && !$user->isSuperAdmin()) {
            $clientId = null;
        }

        // Create the shortcode linked to the form version (and optionally to a client)
        $shortcode = Shortcode::create([
            'code' => Shortcode::generateUniqueCode(),
            'user_id' => $user->id,
            'client_id' => $clientId,
            'custom_form_id' => $form->id,
            'form_version_id' => $version->id,
            'settings' => $settings,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shortcode created successfully!',
            'shortcode' => $shortcode,
            'embed_code' => '[shortcode=' . $shortcode->code . ']',
        ]);
    }

    /**
     * Render the shortcode form.
     * This method is publicly accessible and renders the form for the given shortcode.
     * 
     * @param string $code The shortcode identifier
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function render($code)
    {
        // Find the shortcode by its code with form version
        $shortcode = Shortcode::where('code', $code)
            ->with(['customForm', 'formVersion'])
            ->first();

        // If shortcode doesn't exist, return 404
        if (!$shortcode) {
            abort(404, 'Shortcode not found.');
        }

        // Get the form and form version
        $form = $shortcode->customForm;
        $version = $shortcode->formVersion;

        // If form doesn't exist or is inactive, return error
        if (!$form || $form->status !== 'active') {
            abort(404, 'Form is not available.');
        }

        // If version doesn't exist, try to get latest version
        if (!$version) {
            $version = $form->latestVersion;
            if (!$version) {
                abort(404, 'Form version not found.');
            }
        }

        // Get fields from the version's form_data
        $fields = collect($version->form_data['fields'] ?? [])->sortBy('order');

        // Return the form view with version data
        return view('user.shortcode.render', compact('shortcode', 'form', 'version', 'fields'));
    }

    /**
     * Handle form submission from shortcode.
     * Stores the submission data and sends email notification to the shortcode owner.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $code The shortcode identifier
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request, $code)
    {
        try {
            // Find the shortcode with form version
            $shortcode = Shortcode::where('code', $code)
                ->with(['customForm', 'formVersion', 'user', 'client'])
                ->first();

            if (!$shortcode) {
                return response()->json(['success' => false, 'message' => 'Shortcode not found.'], 404);
            }

            $form = $shortcode->customForm;
            $version = $shortcode->formVersion;

            if (!$form || $form->status !== 'active') {
                return response()->json(['success' => false, 'message' => 'Form is not available.'], 404);
            }

            // If version doesn't exist, try to get latest version
            if (!$version) {
                $version = $form->latestVersion;
                if (!$version) {
                    return response()->json(['success' => false, 'message' => 'Form version not found.'], 404);
                }
            }

        // Get fields from the version's form_data
        $fields = collect($version->form_data['fields'] ?? []);

        // Collect submission data
        $submissionData = [];

        // Validate and collect form field values
        foreach ($fields as $field) {
            $fieldKey = $field['field_key'] ?? null;
            if (!$fieldKey) {
                continue;
            }

            $fieldType = $field['field_type'] ?? 'Input';
            $fieldName = $field['field_name'] ?? $fieldKey;
            $isRequired = !empty($field['is_required']);
            $metadataArray = $this->parseFieldMetadata($field);
            $allowedOptions = $metadataArray['options'];
            $allowMultiple = $metadataArray['allow_multiple'];

            if ($fieldType === 'Checkbox' && !empty($allowedOptions)) {
                if ($allowMultiple) {
                    $value = $request->input($fieldKey, []);
                    $value = is_array($value) ? array_values(array_filter($value, fn ($item) => $item !== '' && $item !== null)) : [];
                } else {
                    $rawValue = $request->input($fieldKey, '');
                    $value = is_array($rawValue) ? ($rawValue[0] ?? '') : $rawValue;
                }

                if ($isRequired && ($value === '' || $value === [])) {
                    return response()->json([
                        'success' => false,
                        'message' => "The field '{$fieldName}' is required.",
                    ], 422);
                }

                $valuesToCheck = is_array($value) ? $value : ($value !== '' && $value !== null ? [$value] : []);
                foreach ($valuesToCheck as $selectedValue) {
                    if (!in_array($selectedValue, $allowedOptions, true)) {
                        return response()->json([
                            'success' => false,
                            'message' => "Invalid selection for '{$fieldName}'.",
                        ], 422);
                    }
                }
            } elseif ($fieldType === 'Checkbox') {
                $value = $request->boolean($fieldKey) ? '1' : '';
                if ($isRequired && $value === '') {
                    return response()->json([
                        'success' => false,
                        'message' => "The field '{$fieldName}' is required.",
                    ], 422);
                }
            } else {
                $value = $request->input($fieldKey, '');

                if ($isRequired && ($value === '' || $value === null || (is_array($value) && empty($value)))) {
                    return response()->json([
                        'success' => false,
                        'message' => "The field '{$fieldName}' is required.",
                    ], 422);
                }

                if ($fieldType === 'Select' && $value !== '' && $value !== null) {
                    if (!empty($allowedOptions) && !in_array($value, $allowedOptions, true)) {
                        return response()->json([
                            'success' => false,
                            'message' => "Invalid selection for '{$fieldName}'.",
                        ], 422);
                    }
                }
            }

            $submissionData[$fieldKey] = [
                'field_name' => $fieldName,
                'field_type' => $fieldType,
                'value' => $value,
            ];
        }

        // Get admin_id, client_id (website owner), user_id for the submission
        $adminId = $form->admin_id;
        $clientId = $shortcode->client_id; // Client (website owner) - so they see it and get email
        $userId = $version->created_by_user_id ?? $form->user_id;

        // Store the submission with proper tracking
        $submission = FormSubmission::create([
            'admin_id' => $adminId,
            'client_id' => $clientId,
            'user_id' => $userId,
            'shortcode_id' => $shortcode->id,
            'custom_form_id' => $form->id,
            'form_version_id' => $version->id,
            'submission_data' => $submissionData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => FormSubmission::STATUS_PENDING,
        ]);

        // Send email to Client (website owner) so they can reply to their visitor
        if ($clientId) {
            try {
                $this->sendClientSubmissionNotification($shortcode, $form, $submission);
            } catch (\Exception $e) {
                \Log::error('Failed to send client submission notification: ' . $e->getMessage());
            }
        }
        // Also notify Admin (shortcode owner) if different from client
        try {
            $this->sendSubmissionNotification($shortcode, $form, $submission);
        } catch (\Exception $e) {
            \Log::error('Failed to send shortcode submission notification: ' . $e->getMessage());
        }

            $redirectUrl = $shortcode->redirect_url;

            return response()->json([
                'success' => true,
                'message' => $redirectUrl ? 'Form submitted successfully! Redirecting...' : 'Form submitted successfully!',
                'success_action' => $shortcode->success_action,
                'redirect_url' => $redirectUrl,
            ]);
        } catch (\Exception $e) {
            \Log::error('Shortcode submission error: ' . $e->getMessage(), [
                'code' => $code,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the form. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Parse field metadata from form version data.
     */
    private function parseFieldMetadata(array $field): array
    {
        $metadata = $field['metadata'] ?? '{}';
        if (is_string($metadata)) {
            $metadata = json_decode($metadata, true) ?: [];
        }
        if (!is_array($metadata)) {
            $metadata = [];
        }

        return [
            'options' => $metadata['options'] ?? [],
            'allow_multiple' => !empty($metadata['allow_multiple']),
        ];
    }

    /**
     * Get mail sender settings from database. Uses .env (config) when settings table is missing.
     *
     * @return array{sender_email?: string, sender_name?: string}
     */
    private function getMailSettings(): array
    {
        try {
            $settings = DB::table('settings')->first();
            if ($settings) {
                return [
                    'sender_email' => $settings->sender_email ?? null,
                    'sender_name' => $settings->sender_name ?? null,
                ];
            }
        } catch (\Throwable $e) {
            // Table may not exist; use config (mail.from) from .env
        }
        return [];
    }

    /**
     * Normalize shortcode submission settings and validate redirect URL when present.
     */
    private function normalizeShortcodeSettings($rawSettings): ?array
    {
        if ($rawSettings === null || $rawSettings === '') {
            return null;
        }

        if (is_string($rawSettings)) {
            $rawSettings = json_decode($rawSettings, true);
        }

        if (!is_array($rawSettings)) {
            throw ValidationException::withMessages([
                'settings' => 'Invalid shortcode settings payload.',
            ]);
        }

        $settings = $rawSettings;
        $settings['button_color'] = Shortcode::normalizeButtonColor($settings['button_color'] ?? null);
        $settings['button_text'] = $this->normalizeButtonText($settings['button_text'] ?? null);
        $successAction = ($settings['success_action'] ?? 'message') === 'redirect' ? 'redirect' : 'message';

        if ($successAction !== 'redirect') {
            unset($settings['redirect_url']);
            $settings['success_action'] = 'message';
            return $settings;
        }

        $redirectUrl = isset($settings['redirect_url']) ? trim((string) $settings['redirect_url']) : '';
        if ($redirectUrl === '' || !filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
            throw ValidationException::withMessages([
                'redirect_url' => 'Please enter a valid redirect URL including http:// or https://.',
            ]);
        }

        $settings['success_action'] = 'redirect';
        $settings['redirect_url'] = $redirectUrl;

        return $settings;
    }

    /**
     * Normalize the public form submit button label.
     */
    private function normalizeButtonText($value): string
    {
        $text = trim(strip_tags((string) ($value ?? '')));
        if ($text === '') {
            return 'Submit';
        }

        return mb_substr($text, 0, 50);
    }

    /**
     * Send email notification to the shortcode owner about a new submission.
     *
     * @param \App\Models\Shortcode $shortcode
     * @param \App\Models\CustomForm $form
     * @param \App\Models\FormSubmission $submission
     * @return void
     */
    private function sendSubmissionNotification(Shortcode $shortcode, CustomForm $form, FormSubmission $submission)
    {
        $settings = $this->getMailSettings();
        $visitorEmail = $submission->visitor_email;
        $visitorName = $submission->visitor_name;

        // Prepare email data
        $emailData = [
            'form_name' => $form->name,
            'shortcode_code' => $shortcode->code,
            'submission_data' => $submission->submission_data,
            'submitted_at' => $submission->created_at->format('Y-m-d H:i:s'),
            'ip_address' => $submission->ip_address,
            'user_name' => $shortcode->user->first_name . ' ' . $shortcode->user->last_name,
            'user_email' => $shortcode->user->email,
            'visitor_email' => $visitorEmail,
            'visitor_name' => $visitorName,
            'sender_email' => $settings['sender_email'] ?? config('mail.from.address'),
            'sender_name' => $settings['sender_name'] ?? config('mail.from.name'),
        ];

        // Send the email - From must match SMTP account (use config)
        Mail::send('email.shortcode_submission', $emailData, function ($message) use ($emailData, $visitorEmail, $visitorName) {
            $message->to($emailData['user_email'], $emailData['user_name'])
                ->subject('New Form Submission: ' . $emailData['form_name'])
                ->from(config('mail.from.address'), config('mail.from.name'));

            if ($visitorEmail) {
                $message->replyTo($visitorEmail, $visitorName ?: $visitorEmail);
            }
        });
    }

    /**
     * Send email to Client (website owner) so they can reply to the visitor.
     * Admin does not access client inbox; this is the notification to the client only.
     */
    private function sendClientSubmissionNotification(Shortcode $shortcode, CustomForm $form, FormSubmission $submission)
    {
        $client = $shortcode->client;
        if (!$client || !$client->email) {
            return;
        }
        $settings = $this->getMailSettings();
        $visitorEmail = $submission->visitor_email;
        $visitorName = $submission->visitor_name;
        $emailData = [
            'form_name' => $form->name,
            'shortcode_code' => $shortcode->code,
            'submission_data' => $submission->submission_data,
            'submitted_at' => $submission->created_at->format('Y-m-d H:i:s'),
            'ip_address' => $submission->ip_address,
            'client_name' => $client->name ?? $client->first_name . ' ' . $client->last_name,
            'client_email' => $client->email,
            'visitor_email' => $visitorEmail,
            'visitor_name' => $visitorName,
            'sender_email' => $settings['sender_email'] ?? config('mail.from.address'),
            'sender_name' => $settings['sender_name'] ?? config('mail.from.name'),
        ];
        Mail::send('email.client_submission', $emailData, function ($message) use ($emailData, $visitorEmail, $visitorName) {
            $message->to($emailData['client_email'], $emailData['client_name'])
                ->subject('New submission on your website: ' . $emailData['form_name'])
                ->from(config('mail.from.address'), config('mail.from.name'));

            if ($visitorEmail) {
                $message->replyTo($visitorEmail, $visitorName ?: $visitorEmail);
            }
        });
    }

    /**
     * List all shortcodes created by the logged-in user.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = currentUser();
        if (!$user) {
            abort(403, 'You must be logged in to view shortcodes.');
        }

        // Get shortcodes: for clients only those assigned to them; for admin/user all they own or manage
        $query = Shortcode::with(['customForm.latestVersion', 'formVersion']);
        
        if ($user->isClient()) {
            $query->where('client_id', $user->id);
        } elseif ($user->isSuperAdmin()) {
            // Super Admin can see all shortcodes
        } elseif ($user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('customForm', fn ($q2) => $q2->where('admin_id', $user->id));
            });
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('customForm', fn ($q2) => $q2->where('admin_id', $user->admin_id));
            });
        }
        
        $shortcodes = $query->with('client')->latest()->get();

        // Clients list for assign dropdown (admin: their clients; superadmin: could pass all or leave empty; client: none)
        $clients = collect();
        if ($user->isAdmin()) {
            $clients = Client::where('admin_id', $user->id)->orderBy('name')->get();
        } elseif ($user->isSuperAdmin()) {
            $clients = Client::orderBy('name')->get();
        }

        return view('user.shortcode.index', compact('shortcodes', 'clients'));
    }

    /**
     * Update shortcode client assignment and website.
     */
    public function update(Request $request, Shortcode $shortcode)
    {
        $user = currentUser();
        if (!$user || $user->isClient()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        // Authorization: must own shortcode or form belongs to admin
        $form = $shortcode->customForm;
        if (!$form) {
            return response()->json(['success' => false, 'message' => 'Shortcode form not found.'], 404);
        }
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin() && $shortcode->user_id != $user->id && $form->admin_id != $user->id) {
                return response()->json(['success' => false, 'message' => 'Not allowed to update this shortcode.'], 403);
            }
            if ($user->isRegularUser() && $shortcode->user_id != $user->id && $form->admin_id != $user->admin_id) {
                return response()->json(['success' => false, 'message' => 'Not allowed to update this shortcode.'], 403);
            }
        }

        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'website' => 'nullable|string|max:500',
            'settings' => 'nullable|json',
        ]);

        $settings = $this->normalizeShortcodeSettings($request->input('settings'));

        $shortcode->client_id = $request->input('client_id') ?: null;
        $shortcode->website = $request->input('website') ? trim($request->input('website')) : null;
        $shortcode->settings = $settings;
        $shortcode->save();

        return response()->json(['success' => true, 'message' => 'Shortcode updated.']);
    }

    /**
     * Delete a shortcode.
     */
    public function destroy(Shortcode $shortcode)
    {
        $user = currentUser();
        if (!$user || $user->isClient()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        $form = $shortcode->customForm;
        if (!$form) {
            $shortcode->delete();
            return response()->json(['success' => true, 'message' => 'Shortcode deleted.']);
        }
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin() && $shortcode->user_id != $user->id && $form->admin_id != $user->id) {
                return response()->json(['success' => false, 'message' => 'Not allowed to delete this shortcode.'], 403);
            }
            if ($user->isRegularUser() && $shortcode->user_id != $user->id && $form->admin_id != $user->admin_id) {
                return response()->json(['success' => false, 'message' => 'Not allowed to delete this shortcode.'], 403);
            }
        }

        $shortcode->delete();
        return response()->json(['success' => true, 'message' => 'Shortcode deleted.']);
    }
}
