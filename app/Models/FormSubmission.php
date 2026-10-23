<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * FormSubmission Model
 * 
 * Represents a submission made through a shortcode form.
 * Stores all the data submitted by users.
 */
class FormSubmission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'form_submissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'client_id',  // Website owner (client) - submissions from their site
        'user_id',
        'shortcode_id',
        'custom_form_id',
        'form_version_id',
        'submission_data',
        'ip_address',
        'user_agent',
        'read_at',
        'status',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_HIRED = 'hired';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'submission_data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Whether this submission is unread (not yet viewed).
     */
    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function isPending(): bool
    {
        return ($this->status ?? self::STATUS_PENDING) === self::STATUS_PENDING;
    }

    public function statusLabel(): string
    {
        return match ($this->status ?? self::STATUS_PENDING) {
            self::STATUS_HIRED => 'Hired',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Pending',
        };
    }

    /**
     * Get the shortcode that received this submission.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shortcode()
    {
        return $this->belongsTo(Shortcode::class);
    }

    /**
     * Get the custom form associated with this submission.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customForm()
    {
        return $this->belongsTo(CustomForm::class, 'custom_form_id');
    }

    /**
     * Get the form version associated with this submission.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function formVersion()
    {
        return $this->belongsTo(FormVersion::class, 'form_version_id');
    }

    /**
     * Get the admin (company) who owns this submission.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the client (website owner) this submission belongs to.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the user who created/edited the form.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Draft/sent invoice created when this lead is hired.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'form_submission_id');
    }

    /**
     * Extract visitor name from submission data
     * Looks for common name fields like: name, full_name, first_name, etc.
     */
    public function getVisitorNameAttribute(): ?string
    {
        if (!$this->submission_data) {
            return null;
        }

        $nameFields = ['name', 'full_name', 'first_name', 'firstname', 'your_name', 'contact_name', 'visitor_name'];
        
        foreach ($nameFields as $field) {
            if (isset($this->submission_data[$field]['value'])) {
                $value = $this->submission_data[$field]['value'];
                if (!empty($value)) {
                    return $value;
                }
            }
        }

        // Try to find any field with "name" in the key
        foreach ($this->submission_data as $key => $data) {
            if (stripos($key, 'name') !== false && isset($data['value']) && !empty($data['value'])) {
                return $data['value'];
            }
        }

        return null;
    }

    /**
     * Extract visitor email from submission data
     */
    public function getVisitorEmailAttribute(): ?string
    {
        if (!$this->submission_data) {
            return null;
        }

        $emailFields = ['email', 'e_mail', 'email_address', 'your_email', 'contact_email', 'visitor_email'];
        
        foreach ($emailFields as $field) {
            if (isset($this->submission_data[$field]['value'])) {
                $value = $this->submission_data[$field]['value'];
                if (!empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return $value;
                }
            }
        }

        // Try fields explicitly marked as Email type in the form builder
        foreach ($this->submission_data as $data) {
            if (
                isset($data['field_type'], $data['value']) &&
                strcasecmp((string) $data['field_type'], 'Email') === 0 &&
                !empty($data['value']) &&
                filter_var($data['value'], FILTER_VALIDATE_EMAIL)
            ) {
                return $data['value'];
            }
        }

        // Try to find any field with "email" in the key
        foreach ($this->submission_data as $key => $data) {
            if (stripos($key, 'email') !== false && isset($data['value']) && !empty($data['value'])) {
                $value = $data['value'];
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return $value;
                }
            }
        }

        return null;
    }

    /**
     * Extract visitor phone from submission data
     */
    public function getVisitorPhoneAttribute(): ?string
    {
        if (!$this->submission_data) {
            return null;
        }

        $phoneFields = ['phone', 'telephone', 'mobile', 'phone_number', 'contact_phone', 'your_phone', 'phone_no'];
        
        foreach ($phoneFields as $field) {
            if (isset($this->submission_data[$field]['value'])) {
                $value = $this->submission_data[$field]['value'];
                if (!empty($value)) {
                    return $value;
                }
            }
        }

        // Try to find any field with "phone" or "mobile" in the key
        foreach ($this->submission_data as $key => $data) {
            if ((stripos($key, 'phone') !== false || stripos($key, 'mobile') !== false) && 
                isset($data['value']) && !empty($data['value'])) {
                return $data['value'];
            }
        }

        return null;
    }

    /**
     * Get message/text content from submission data
     * Looks for message, comment, feedback, etc.
     */
    public function getVisitorMessageAttribute(): ?string
    {
        if (!$this->submission_data) {
            return null;
        }

        $messageFields = ['message', 'comment', 'feedback', 'comments', 'notes', 'description', 'details', 'text'];
        
        foreach ($messageFields as $field) {
            if (isset($this->submission_data[$field]['value'])) {
                $value = $this->submission_data[$field]['value'];
                if (!empty($value)) {
                    return $value;
                }
            }
        }

        return null;
    }
}
