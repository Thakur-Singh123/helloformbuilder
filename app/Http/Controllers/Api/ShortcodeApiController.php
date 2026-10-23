<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shortcode;
use App\Models\FormVersion;

class ShortcodeApiController extends Controller
{
    /**
     * Get form data for embedding via API
     * Returns JSON data that can be used by JavaScript embed script
     */
    public function getFormData($code)
    {
        try {
            // Find the shortcode
            $shortcode = Shortcode::where('code', $code)
                ->with(['customForm', 'formVersion'])
                ->first();

            if (!$shortcode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shortcode not found'
                ], 404);
            }

            $form = $shortcode->customForm;
            $version = $shortcode->formVersion;

            // If version doesn't exist, try to get latest version
            if (!$version) {
                $version = $form->latestVersion;
                if (!$version) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Form version not found'
                    ], 404);
                }
            }

            // Check if form is active
            if ($form->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Form is not active'
                ], 403);
            }

            // Get form fields from version (include step from metadata for multi-step forms)
            $fields = collect($version->form_data['fields'] ?? [])
                ->sortBy('order')
                ->values()
                ->map(function ($field) {
                    $metadata = $field['metadata'] ?? '{}';
                    $metadataArray = is_string($metadata) ? (json_decode($metadata, true) ?: []) : (is_array($metadata) ? $metadata : []);
                    $fieldType = $field['field_type'] ?? 'Input';
                    return [
                        'field_name' => $field['field_name'] ?? '',
                        'field_key' => $field['field_key'] ?? '',
                        'field_type' => $fieldType,
                        'is_required' => $field['is_required'] ?? false,
                        'metadata' => $metadataArray,
                        'options' => $metadataArray['options'] ?? [],
                        'allow_multiple' => $fieldType === 'Checkbox' && !empty($metadataArray['allow_multiple']),
                        'order' => $field['order'] ?? 0,
                        'step' => (int) ($metadataArray['step'] ?? 1),
                    ];
                });

            // Return form data
            return response()->json([
                'success' => true,
                'form' => [
                    'id' => $form->id,
                    'name' => $form->name,
                    'heading' => $form->heading,
                    'title' => $form->title,
                    'description' => $form->description,
                    'display_settings' => $form->getDisplaySettings(),
                    'status' => $form->status,
                ],
                'version' => [
                    'id' => $version->id,
                    'version_number' => $version->version_number,
                ],
                'fields' => $fields,
                'submit_url' => route('shortcode.submit', $code),
                'success_action' => $shortcode->success_action,
                'redirect_url' => $shortcode->redirect_url,
                'button_color' => $shortcode->button_color,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading form data'
            ], 500);
        }
    }

    /**
     * Get form HTML for iframe embedding
     */
    public function getFormHtml($code)
    {
        try {
            $shortcode = Shortcode::where('code', $code)
                ->with(['customForm', 'formVersion'])
                ->first();

            if (!$shortcode) {
                return response('<p>Shortcode not found</p>', 404)
                    ->header('Content-Type', 'text/html');
            }

            $form = $shortcode->customForm;
            $version = $shortcode->formVersion;

            if (!$version) {
                $version = $form->latestVersion;
            }

            if (!$form || $form->status !== 'active' || !$version) {
                return response('<p>Form is not available</p>', 403)
                    ->header('Content-Type', 'text/html');
            }

            $fields = collect($version->form_data['fields'] ?? [])->sortBy('order');

            return view('user.shortcode.embed-iframe', compact('shortcode', 'form', 'version', 'fields'))
                ->render();
        } catch (\Exception $e) {
            return response('<p>Error loading form</p>', 500)
                ->header('Content-Type', 'text/html');
        }
    }
}
