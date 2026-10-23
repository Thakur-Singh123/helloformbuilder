<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginActivity extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'ip_address',
        'user_agent',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account that logged in (User or Client).
     */
    public function account()
    {
        if ($this->user_type === 'client') {
            return Client::find($this->user_id);
        }
        return User::find($this->user_id);
    }
}
