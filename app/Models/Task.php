<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
        'is_completed' => 0,
        'sort' => 1,
    ];

    protected $casts = [
        'task_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
