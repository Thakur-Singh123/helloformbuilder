<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormTemplate extends Model
{
    /** The 19 field types the renderer understands. */
    public const FIELD_TYPES = [
        'text', 'email', 'phone', 'url', 'number', 'textarea', 'date', 'time',
        'select', 'radio', 'checkbox', 'file', 'rating', 'address', 'signature',
        'heading', 'paragraph', 'hidden', 'scale',
    ];

    protected $fillable = [
        'template_category_id', 'title', 'slug', 'description', 'fields',
        'fields_count', 'is_popular', 'is_active', 'uses_count',
    ];

    protected $casts = [
        'fields'     => 'array',
        'is_popular' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TemplateCategory::class, 'template_category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        if (! $term) {
            return $q;
        }

        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';

        return $q->where(fn ($w) => $w
            ->where('title', 'like', $like)
            ->orWhere('description', 'like', $like)
            ->orWhere('fields', 'like', $like));   // matches field labels too
    }

    protected static function booted(): void
    {
        static::saving(function (self $t) {
            $t->fields_count = count($t->fields ?? []);
        });
    }
}
