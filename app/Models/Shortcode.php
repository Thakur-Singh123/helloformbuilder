<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Shortcode Model
 * 
 * Represents a shortcode that can be embedded anywhere to display a submission form.
 * Each shortcode is linked to a custom form and belongs to a user.
 */
class Shortcode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shortcodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'user_id',
        'client_id',
        'website',    // Client's website name/URL where this shortcode is embedded
        'custom_form_id',
        'form_version_id',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array', // Automatically cast JSON to array
    ];

    /**
     * Normalized submission behavior settings for this shortcode.
     */
    public function getSubmissionSettingsAttribute(): array
    {
        $settings = is_array($this->settings) ? $this->settings : [];
        $successAction = ($settings['success_action'] ?? 'message') === 'redirect' ? 'redirect' : 'message';
        $redirectUrl = isset($settings['redirect_url']) ? trim((string) $settings['redirect_url']) : '';
        $buttonColor = self::normalizeButtonColor($settings['button_color'] ?? null);

        if ($successAction !== 'redirect' || $redirectUrl === '' || !filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
            return [
                'success_action' => 'message',
                'redirect_url' => null,
                'button_color' => $buttonColor,
            ];
        }

        return [
            'success_action' => 'redirect',
            'redirect_url' => $redirectUrl,
            'button_color' => $buttonColor,
        ];
    }

    /**
     * Get the configured post-submit success action.
     */
    public function getSuccessActionAttribute(): string
    {
        return $this->submission_settings['success_action'];
    }

    /**
     * Get the configured redirect URL after successful submission.
     */
    public function getRedirectUrlAttribute(): ?string
    {
        return $this->submission_settings['redirect_url'];
    }

    /**
     * Get the configured submit button color.
     */
    public function getButtonColorAttribute(): string
    {
        return $this->submission_settings['button_color'];
    }

    /**
     * Normalize a hex button color or fall back to default Bootstrap primary.
     */
    public static function normalizeButtonColor($color): string
    {
        $default = '#0d6efd';
        if (!is_string($color)) {
            return $default;
        }

        $color = trim($color);
        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            return $color;
        }

        return $default;
    }

    /**
     * Generate a unique shortcode.
     * 
     * @return string
     */
    public static function generateUniqueCode(): string
    {
        do {
            // Generate a random 8-character alphanumeric code
            $code = Str::random(8);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Get the user (Admin) that owns this shortcode.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client (website owner) this shortcode is assigned to.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the custom form associated with this shortcode.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customForm()
    {
        return $this->belongsTo(CustomForm::class, 'custom_form_id');
    }

    /**
     * Get the form version associated with this shortcode.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function formVersion()
    {
        return $this->belongsTo(FormVersion::class, 'form_version_id');
    }

    /**
     * Get all submissions for this shortcode
     */
    public function submissions()
    {
        return $this->hasMany(FormSubmission::class, 'shortcode_id');
    }
}
