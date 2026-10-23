<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SENT = 'sent';

    protected $fillable = [
        'admin_id',
        'form_submission_id',
        'client_id',
        'invoice_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'amount',
        'currency',
        'description',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'sent_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(FormSubmission::class, 'form_submission_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function sendLogs()
    {
        return $this->hasMany(InvoiceSendLog::class)->orderByDesc('created_at');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    public static function generateNumber(): string
    {
        return 'INV-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    /**
     * Create a draft invoice for a hired lead (idempotent).
     */
    public static function createDraftForSubmission(FormSubmission $submission): self
    {
        $existing = self::where('form_submission_id', $submission->id)->first();
        if ($existing) {
            return $existing;
        }

        $submission->loadMissing(['customForm', 'shortcode']);

        $adminId = $submission->admin_id
            ?? optional($submission->customForm)->admin_id
            ?? optional($submission->shortcode)->user_id
            ?? optional(function_exists('currentUser') ? currentUser() : null)->id;

        if (!$adminId) {
            throw new \RuntimeException('Cannot create invoice: admin_id is missing for this lead.');
        }

        return self::create([
            'admin_id' => $adminId,
            'form_submission_id' => $submission->id,
            'client_id' => $submission->client_id,
            'invoice_number' => self::generateNumber(),
            'customer_name' => $submission->visitor_name,
            'customer_email' => $submission->visitor_email,
            'customer_phone' => $submission->visitor_phone,
            'amount' => null,
            'currency' => 'INR',
            'description' => null,
            'status' => self::STATUS_DRAFT,
        ]);
    }
}
