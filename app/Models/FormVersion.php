<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * FormVersion Model
 * 
 * Represents a version of a form. Each time a form is edited,
 * a new version is created to maintain history and allow old
 * shortcodes to continue working with their specific version.
 */
class FormVersion extends Model
{
    protected $table = 'form_versions';

    protected $fillable = [
        'custom_form_id',
        'version_number',
        'created_by_user_id',
        'form_data',
        'description',
        'is_active',
    ];

    protected $casts = [
        'form_data' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the form this version belongs to
     */
    public function customForm()
    {
        return $this->belongsTo(CustomForm::class, 'custom_form_id');
    }

    /**
     * Get the user who created this version
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get all shortcodes using this version
     */
    public function shortcodes()
    {
        return $this->hasMany(Shortcode::class, 'form_version_id');
    }

    /**
     * Get all submissions for this version
     */
    public function submissions()
    {
        return $this->hasMany(FormSubmission::class, 'form_version_id');
    }

    /**
     * Get the form fields for this version
     */
    public function getFieldsAttribute()
    {
        return $this->form_data['fields'] ?? [];
    }
}
