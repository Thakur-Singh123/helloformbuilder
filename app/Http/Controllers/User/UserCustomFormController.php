<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomForm;
use App\Models\CustomFormField;
use App\Models\FormVersion;
use App\Models\Shortcode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserCustomFormController extends Controller
{
    /**
     * Normalize display settings JSON from the form builder.
     */
    protected function normalizeDisplaySettings($input): array
    {
        $defaults = CustomForm::defaultDisplaySettings();

        if (is_string($input)) {
            $input = json_decode($input, true) ?: [];
        }

        if (!is_array($input)) {
            return $defaults;
        }

        $allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'];
        $allowedAlign = ['left', 'center', 'right'];

        if (isset($input['submit_button']) && is_array($input['submit_button'])) {
            $buttonText = trim(strip_tags((string) ($input['submit_button']['text'] ?? '')));
            $defaults['submit_button']['text'] = $buttonText !== '' ? mb_substr($buttonText, 0, 50) : 'Submit';
            if (preg_match('/^#[0-9A-Fa-f]{6}$/', $input['submit_button']['color'] ?? '')) {
                $defaults['submit_button']['color'] = $input['submit_button']['color'];
            }
        }

        foreach ($defaults as $section => $sectionDefaults) {
            if ($section === 'submit_button') {
                continue;
            }
            if (!isset($input[$section]) || !is_array($input[$section])) {
                continue;
            }

            $value = $input[$section];

            if (in_array($value['tag'] ?? '', $allowedTags, true)) {
                $defaults[$section]['tag'] = $value['tag'];
            }

            if (preg_match('/^#[0-9A-Fa-f]{6}$/', $value['color'] ?? '')) {
                $defaults[$section]['color'] = $value['color'];
            }

            if (in_array($value['align'] ?? '', $allowedAlign, true)) {
                $defaults[$section]['align'] = $value['align'];
            }

            $lineHeight = (float) ($value['line_height'] ?? $sectionDefaults['line_height']);
            if ($lineHeight >= 1 && $lineHeight <= 3) {
                $defaults[$section]['line_height'] = (string) $lineHeight;
            }

            $defaults[$section]['italic'] = filter_var($value['italic'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $defaults[$section]['underline'] = filter_var($value['underline'] ?? false, FILTER_VALIDATE_BOOLEAN);
        }

        return $defaults;
    }

    /**
     * Get the admin ID for the current user
     */
    protected function getAdminId()
    {
        $user = currentUser();
        
        if ($user->isSuperAdmin()) {
            return null; // Super Admin has no admin
        }
        
        if ($user->isAdmin()) {
            return $user->id; // Admin is their own admin
        }
        
        return $user->admin_id; // Regular user's admin_id
    }

    /**
     * Get forms query for "my custom templates" (Create template list / edit / delete).
     * Only custom (is_global = false) forms per admin; clients see forms that have submissions for them (any form).
     */
    protected function getFormsQuery()
    {
        $user = currentUser();
        $query = CustomForm::with(['fields', 'shortcodes', 'versions']);

        if ($user->isClient()) {
            // Client: forms that have received submissions on their shortcodes (include global templates)
            return $query->whereHas('submissions', fn ($q) => $q->where('client_id', $user->id));
        }

        $query->where('is_global', false);
        
        if ($user->isSuperAdmin()) {
            return $query; // Super Admin sees all custom forms
        }
        
        if ($user->isAdmin()) {
            return $query->where('admin_id', $user->id);
        }
        
        return $query->where('admin_id', $user->admin_id);
    }

    /**
     * Build Form landing: choose template (shortcode) or create template (new form).
     */
    public function buildFormLanding()
    {
        return view('user.form.build_landing');
    }

    /**
     * Authorize using currentUser() (not only Auth::user()).
     */
    protected function authorizeForCurrentUser(string $ability, $model = null): void
    {
        $user = currentUser();
        if (!$user) {
            abort(403, 'You must be logged in.');
        }

        if (method_exists($user, 'isClient') && $user->isClient()) {
            abort(403, 'You do not have permission to modify forms.');
        }

        if ($model !== null) {
            Gate::forUser($user)->authorize($ability, $model);
            return;
        }

        Gate::forUser($user)->authorize($ability, CustomForm::class);
    }

    public function add_new_form() {
        $this->authorizeForCurrentUser('create');
        return view('user.form.add');
    }

    public function custom_form_save(Request $request)
    {
        try {
            $this->authorizeForCurrentUser('create');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['success' => false, 'message' => 'You are not allowed to create forms.'], 403);
        }

        $user = currentUser();
        $adminId = $this->getAdminId();

        try {
            $canSetGlobal = $user->isSuperAdmin() || $user->isAdmin();
            $isGlobal = $canSetGlobal && $request->boolean('is_global');
            $form = CustomForm::create([
                'name' => $request->input('name', 'Untitled Form'),
                'heading' => $request->input('heading', ''),
                'title' => $request->input('title', ''),
                'status' => $request->input('status', 'active'),
                'description' => $request->input('description', ''),
                'display_settings' => $this->normalizeDisplaySettings($request->input('display_settings')),
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'is_global' => $isGlobal,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create form: ' . $e->getMessage(),
            ], 422);
        }

        $fields = $request->input('fields', []);
        if (is_string($fields)) {
            $fields = json_decode($fields, true) ?: [];
        }
        if (!is_array($fields)) {
            $fields = [];
        }

        foreach ($fields as $index => $field) {
            CustomFormField::create([
                'custom_form_id' => $form->id,
                'field_name'     => $field['field_name'] ?? 'Field',
                'field_key'     => $field['field_key'] ?? ('field_' . ($index + 1)),
                'field_type'    => $field['field_type'] ?? 'Input',
                'is_required'   => !empty($field['is_required']),
                'metadata'      => isset($field['metadata']) && $field['metadata'] !== null ? (is_string($field['metadata']) ? $field['metadata'] : json_encode($field['metadata'])) : '{}',
                'order'         => (int) ($field['order'] ?? ($index + 1)),
            ]);
        }

        try {
            $form->load('fields');
            $version = $form->createVersion($user->id, 'Initial version');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create form version: ' . $e->getMessage(),
            ], 422);
        }

        try {
            $submitButton = $form->getSubmitButtonSettings();
            $shortcode = Shortcode::create([
                'code' => Shortcode::generateUniqueCode(),
                'user_id' => $user->id,
                'custom_form_id' => $form->id,
                'form_version_id' => $version->id,
                'settings' => [
                    'success_action' => 'message',
                    'button_text' => $submitButton['text'],
                    'button_color' => $submitButton['color'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create shortcode: ' . $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'form_id' => $form->id,
            'shortcode' => '[shortcode=' . $shortcode->code . ']',
            'message' => 'Form created successfully. You can now generate an embed code below.',
        ]);
    }

    public function list_view(Request $request) {
        $user = currentUser();
        if (!$user) {
            abort(403, 'You must be logged in to view forms.');
        }
        Gate::forUser($user)->authorize('viewAny', CustomForm::class);
        
        $query = $this->getFormsQuery();
        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('heading', 'like', '%' . $term . '%')
                    ->orWhere('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
            });
        }
        $forms = $query->latest()->get();
        
        return view('user.form.list', compact('forms'));
    }

    // Show edit page
    public function edit_form(CustomForm $form)
    {
        $this->authorizeForCurrentUser('update', $form);
        
        $form->load(['fields', 'versions', 'shortcodes']);
        return view('user.form.edit', compact('form'));
    }

    // Status-only update from the forms list toggle (does not touch fields or shortcodes).
    public function update_form_status(Request $request, CustomForm $form)
    {
        $this->authorizeForCurrentUser('update', $form);

        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $form->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Form status updated.',
            'status' => $request->status,
        ]);
    }

    // Handle full form update (edit page)
    public function custom_form_update(Request $request, CustomForm $form)
    {
        $this->authorizeForCurrentUser('update', $form);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'heading' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'fields' => 'required|json',
        ]);

        $user = currentUser();

        // Update form
        $updateData = [
            'name' => $request->name,
            'heading' => $request->input('heading', ''),
            'title' => $request->input('title', ''),
            'description' => $request->description ?? $form->description,
            'display_settings' => $this->normalizeDisplaySettings($request->input('display_settings')),
            'status' => $request->status,
        ];
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $updateData['is_global'] = $request->boolean('is_global');
        }
        $form->update($updateData);
        $this->syncShortcodeButtonSettings($form);

        // Update fields (full replace from edit page)
        $fieldsData = json_decode($request->input('fields', '[]'), true);
        if (!is_array($fieldsData)) {
            $fieldsData = [];
        }

        $form->fields()->delete();

        $fieldsToInsert = [];
        foreach ($fieldsData as $index => $field) {
            if (!empty($field['field_name']) && !empty($field['field_type'])) {
                $metadata = $field['metadata'] ?? '{}';
                if ($metadata !== null && !is_string($metadata)) {
                    $metadata = json_encode($metadata);
                }
                if ($metadata === null || $metadata === '') {
                    $metadata = '{}';
                }

                $fieldsToInsert[] = [
                    'custom_form_id' => $form->id,
                    'field_name'     => $field['field_name'],
                    'field_key'      => $field['field_key'] ?? Str::slug($field['field_name'], '_'),
                    'field_type'     => $field['field_type'],
                    'is_required'    => $field['is_required'] ?? 0,
                    'metadata'       => $metadata,
                    'order'          => $field['order'] ?? ($index + 1),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        if (!empty($fieldsToInsert)) {
            CustomFormField::insert($fieldsToInsert);
        }

        // Snapshot fresh fields before versioning
        $form->load('fields');
        $version = $form->createVersion($user->id, 'Form updated');

        // Keep existing embed codes — point all shortcodes for this form to the new version
        $existingShortcodes = $form->shortcodes()->get();
        if ($existingShortcodes->isNotEmpty()) {
            $form->shortcodes()->update(['form_version_id' => $version->id]);
            $primaryShortcode = $existingShortcodes->sortBy('id')->first();
        } else {
            $submitButton = $form->getSubmitButtonSettings();
            $primaryShortcode = Shortcode::create([
                'code' => Shortcode::generateUniqueCode(),
                'user_id' => $user->id,
                'custom_form_id' => $form->id,
                'form_version_id' => $version->id,
                'settings' => [
                    'success_action' => 'message',
                    'button_text' => $submitButton['text'],
                    'button_color' => $submitButton['color'],
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Form updated successfully. Your existing embed code will now show the updated form.',
            'shortcode' => '[shortcode=' . $primaryShortcode->code . ']',
            'shortcode_code' => $primaryShortcode->code,
        ]);
    }

    /**
     * Keep related shortcode button label/color in sync with the form.
     */
    protected function syncShortcodeButtonSettings(CustomForm $form): void
    {
        $form->refresh();
        $submitButton = $form->getSubmitButtonSettings();

        foreach ($form->shortcodes()->get() as $shortcode) {
            $settings = $shortcode->settings;
            if (is_string($settings)) {
                $settings = json_decode($settings, true) ?: [];
            }
            if (!is_array($settings)) {
                $settings = [];
            }

            $settings['button_text'] = $submitButton['text'];
            $settings['button_color'] = $submitButton['color'];
            $shortcode->settings = $settings;
            $shortcode->save();
        }
    }

    // Delete form
    public function delete_form(CustomForm $form)
    {
        $this->authorizeForCurrentUser('delete', $form);
        
        $form->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Form deleted successfully.'
        ]);
    }


}
