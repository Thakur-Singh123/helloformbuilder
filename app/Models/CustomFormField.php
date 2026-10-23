<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFormField extends Model
{   
    protected $table = 'custom_form_fields';
    
    protected $fillable = [
        'custom_form_id', 'field_name', 'field_key', 'field_type',
        'is_required', 'metadata', 'order', 'created_at', 'updated_at'
    ];

    public $timestamps = true; // Add this line

    public function form()
    {
        return $this->belongsTo(CustomForm::class, 'custom_form_id');
    }

}
