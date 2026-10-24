<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'month_category';

    public $timestamps = false;

    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
