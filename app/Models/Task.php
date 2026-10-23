<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'admin_id', 'user_id', 'title', 'description', 'status', 'due_date'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}