<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    protected $fillable = ['form_id', 'user_id', 'email', 'data', 'ip'];

    protected $casts = ['data' => 'array'];

    public function form() {
        return $this->belongsTo(Form::class);
    }
}
