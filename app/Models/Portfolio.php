<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'client',
        'budget',
        'category_id',
        'date'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}


