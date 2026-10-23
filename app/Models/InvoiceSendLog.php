<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSendLog extends Model
{
    public $timestamps = false;

    protected $table = 'invoice_send_logs';

    protected $fillable = [
        'invoice_id',
        'sent_to_email',
        'sent_by',
        'amount',
        'currency',
        'is_resend',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_resend' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
