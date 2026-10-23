<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateCategory extends Model
{
    protected $fillable = ['name', 'slug', 'code', 'description', 'sort_order'];

    public function templates(): HasMany
    {
        return $this->hasMany(FormTemplate::class);
    }

    /** Accent colour from the brand palette, stable per category. */
    public function getAccentAttribute(): string
    {
        $palette = ['#1e88ff', '#0e2a6b', '#00a3e0', '#4f46e5', '#0891b2', '#2563eb', '#7c3aed', '#0ea5e9'];

        return $palette[$this->sort_order % count($palette)];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
