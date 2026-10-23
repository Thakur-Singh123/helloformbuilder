<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Client (website owner) under an Admin. Stored in separate `clients` table.
 * Can log in via the same login page as users; uses 'client' auth guard.
 */
class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'clients';

    protected $fillable = [
        'admin_id',
        'name',
        'first_name',
        'last_name',
        'email',
        'website',
        'password',
        'is_active',
        'email_verified_at',
        'email_verification_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /** Role checks (compatible with User for shared code) */
    public function isSuperAdmin(): bool { return false; }
    public function isAdmin(): bool { return false; }
    public function isClient(): bool { return true; }
    public function isRegularUser(): bool { return false; }
    public function isUser(): bool { return false; }

    /** For views/helpers that expect user_type */
    public function getUserTypeAttribute(): string { return 'client'; }

    public function getEffectiveAdminId() { return $this->admin_id; }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function shortcodes()
    {
        return $this->hasMany(Shortcode::class, 'client_id');
    }
}
