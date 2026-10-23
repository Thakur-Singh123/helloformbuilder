<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// If you already have App\Models\Form, merge the fillable/casts below into it.
class Form extends Model
{
    protected $fillable = [
        'user_id', 'form_template_id', 'title', 'description', 'slug',
        'fields', 'settings', 'status', 'published_at',
    ];

    protected $casts = [
        'fields'       => 'array',
        'settings'     => 'array',
        'published_at' => 'datetime',
    ];

    public function responses(): HasMany
    {
        return $this->hasMany(FormResponse::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
