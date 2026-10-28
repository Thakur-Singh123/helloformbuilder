<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Stripe\StripeClient;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',  
        'last_name',   
        'name',
        'user_type',   // 'superadmin', 'admin', 'user' (clients are in clients table)
        'email',
        'password',
        'profile',
        'admin_id',    // For multi-tenant: Users belong to an Admin
        'is_active',
        'plan',
        'stripe_customer_id',
        'stripe_subscription_id',
        'plan_ends_at',
        'email_verified_at',
        'email_verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'plan_ends_at' => 'datetime',  
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'superadmin';
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    /**
     * Check if user is Client (website owner under an Admin)
     */
    public function isClient(): bool
    {
        return $this->user_type === 'client';
    }

    /**
     * Check if user is regular User
     */
    public function isRegularUser(): bool
    {
        return $this->user_type === 'user';
    }

    public function isEmployee(): bool
    {
        return $this->user_type === 'employee';
    }

    /**
     * Check if user is regular User (alias for isRegularUser)
     */
    public function isUser(): bool
    {
        return $this->user_type === 'user';
    }

    /**
     * Get the Admin this user belongs to (for regular users)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get all users under this Admin (if this user is an Admin)
     */
    public function users()
    {
        return $this->hasMany(User::class, 'admin_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all clients (website owners) under this Admin (from clients table).
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'admin_id');
    }

    /**
     * Get all forms created by this user
     */
    public function forms()
    {
        return $this->hasMany(CustomForm::class, 'user_id');
    }

    // User.php model ke andar ye function add karein:
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    /**
     * Get all form versions created by this user
     */
    public function formVersions()
    {
        return $this->hasMany(FormVersion::class, 'created_by_user_id');
    }

    /**
     * Get all shortcodes created by this user
     */
    public function shortcodes()
    {
        return $this->hasMany(Shortcode::class, 'user_id');
    }

    /**
     * Get all submissions for forms owned by this Admin
     */
    public function adminSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'admin_id');
    }

    /**
     * Get all submissions for forms created by this User
     */
    public function userSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'user_id');
    }

    /**
     * Get the effective admin ID for this user
     * Helper method (not an accessor to avoid conflicts)
     */
    public function getEffectiveAdminId()
    {
        if ($this->isSuperAdmin()) {
            return null;
        }
        if ($this->isAdmin()) {
            return $this->id;
        }
        return $this->admin_id; // Client or regular user
    }

    //Function for plans
    public function isPro(): bool
    {
        return $this->plan === 'pro'
            && $this->plan_ends_at
            && $this->plan_ends_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->plan === 'expired';
    }

    public function syncPlan(): void
    {
        if ($this->plan !== 'pro' || ($this->plan_ends_at && $this->plan_ends_at->isFuture())) {
            return;
        }

        $end = null;

        if ($this->stripe_subscription_id) {
            try {
                $stripe = new StripeClient(config('services.stripe.secret'));
                $sub    = $stripe->subscriptions->retrieve($this->stripe_subscription_id);

                if (in_array($sub->status, ['active', 'trialing'])) {
                    $ts  = $sub->items->data[0]->current_period_end ?? $sub->current_period_end ?? null;
                    $end = $ts ? Carbon::createFromTimestamp($ts) : null;
                }
            } catch (\Exception $e) {
                return; 
            }
        }

        if ($end) {
            $this->update(['plan_ends_at' => $end]); 
        } else {
            $this->update(['plan' => 'expired']);
        }
    }

    public function templatesUsedCount(): int
    {
        return \App\Models\Form::where('user_id', $this->id)
            ->whereNotNull('form_template_id')
            ->count();
    }

    public function canUseTemplate(): bool
    {
        return $this->isPro() || $this->templatesUsedCount() < 1;
    }
}
