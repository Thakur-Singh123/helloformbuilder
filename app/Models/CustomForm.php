<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomForm extends Model
{
    protected $table = 'custom_forms';

    protected $fillable = [
        'user_id',
        'admin_id',
        'is_global',
        'name',
        'heading',
        'title',
        'description',
        'display_settings',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'is_global' => 'boolean',
        'display_settings' => 'array',
    ];

    public static function defaultDisplaySettings(): array
    {
        return [
            'heading' => [
                'tag' => 'h2',
                'color' => '#333333',
                'align' => 'left',
                'line_height' => '1.5',
                'italic' => false,
                'underline' => false,
            ],
            'title' => [
                'tag' => 'h4',
                'color' => '#6c757d',
                'align' => 'left',
                'line_height' => '1.4',
                'italic' => false,
                'underline' => false,
            ],
            'description' => [
                'tag' => 'p',
                'color' => '#6c757d',
                'align' => 'left',
                'line_height' => '1.5',
                'italic' => false,
                'underline' => false,
            ],
            'submit_button' => [
                'text' => 'Submit',
                'color' => '#0d6efd',
            ],
        ];
    }

    public function getDisplaySettings(): array
    {
        $defaults = static::defaultDisplaySettings();
        $stored = is_array($this->display_settings) ? $this->display_settings : [];

        foreach ($defaults as $section => $defaultValues) {
            if (!isset($stored[$section]) || !is_array($stored[$section])) {
                continue;
            }

            $defaults[$section] = array_merge($defaultValues, $stored[$section]);
        }

        return $defaults;
    }

    /**
     * Submit button label and color stored on the form.
     *
     * @return array{text: string, color: string}
     */
    public function getSubmitButtonSettings(): array
    {
        $settings = $this->getDisplaySettings()['submit_button'] ?? [];

        $text = trim((string) ($settings['text'] ?? ''));
        $color = (string) ($settings['color'] ?? '');

        return [
            'text' => $text !== '' ? $text : 'Submit',
            'color' => preg_match('/^#[0-9A-Fa-f]{6}$/', $color) ? $color : '#0d6efd',
        ];
    }

    /**
     * Render styled heading / title / description HTML for public forms.
     */
    public function renderDisplayTextHtml(string $section, ?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $settings = $this->getDisplaySettings()[$section] ?? static::defaultDisplaySettings()[$section];
        $allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'];
        $tag = in_array($settings['tag'] ?? '', $allowedTags, true) ? $settings['tag'] : 'p';
        $color = preg_match('/^#[0-9A-Fa-f]{6}$/', $settings['color'] ?? '') ? $settings['color'] : '#333333';
        $align = in_array($settings['align'] ?? '', ['left', 'center', 'right'], true) ? $settings['align'] : 'left';
        $lineHeight = is_numeric($settings['line_height'] ?? null) ? $settings['line_height'] : '1.5';
        $style = sprintf(
            'color:%s;text-align:%s;line-height:%s;margin-bottom:1rem;',
            $color,
            $align,
            $lineHeight
        );

        if (!empty($settings['italic'])) {
            $style .= 'font-style:italic;';
        }

        if (!empty($settings['underline'])) {
            $style .= 'text-decoration:underline;';
        }

        $content = $section === 'description'
            ? nl2br(e($text))
            : e($text);

        return sprintf('<%1$s style="%2$s">%3$s</%1$s>', $tag, $style, $content);
    }

    /**
     * Get all fields for this form
     */
    public function fields()
    {
        return $this->hasMany(CustomFormField::class, 'custom_form_id');
    }

    /**
     * Get all versions of this form
     */
    public function versions()
    {
        return $this->hasMany(FormVersion::class, 'custom_form_id')->orderBy('version_number', 'desc');
    }

    /**
     * Get the latest active version
     */
    public function latestVersion()
    {
        return $this->hasOne(FormVersion::class, 'custom_form_id')
            ->where('is_active', true)
            ->latest('version_number');
    }

    /**
     * Get all shortcodes for this form (across all versions)
     */
    public function shortcodes()
    {
        return $this->hasMany(Shortcode::class, 'custom_form_id');
    }

    /**
     * Get the user who created this form
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin (subscriber) who owns this form
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get all submissions for this form
     */
    public function submissions()
    {
        return $this->hasMany(FormSubmission::class, 'custom_form_id');
    }

    /**
     * Create a new version of this form
     */
    public function createVersion($userId, $description = null)
    {
        // Always read the latest fields from DB (avoids stale relation after update)
        $this->load('fields');

        // Get current version number
        $latestVersion = $this->versions()->max('version_number') ?? 0;
        $newVersionNumber = $latestVersion + 1;

        // Deactivate old versions
        $this->versions()->update(['is_active' => false]);

        // Get current form data
        $formData = [
            'name' => $this->name,
            'heading' => $this->heading,
            'title' => $this->title,
            'description' => $this->description,
            'display_settings' => $this->getDisplaySettings(),
            'status' => $this->status,
            'fields' => $this->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'field_name' => $field->field_name,
                    'field_key' => $field->field_key,
                    'field_type' => $field->field_type,
                    'is_required' => $field->is_required,
                    'metadata' => $field->metadata,
                    'order' => $field->order,
                ];
            })->toArray(),
        ];

        // Create new version
        $version = FormVersion::create([
            'custom_form_id' => $this->id,
            'version_number' => $newVersionNumber,
            'created_by_user_id' => $userId,
            'form_data' => $formData,
            'description' => $description,
            'is_active' => true,
        ]);

        return $version;
    }
}

