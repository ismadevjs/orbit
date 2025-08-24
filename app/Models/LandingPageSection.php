<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageSection extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'image', 'buttons', 'unique_name', 'name'];

    // Cast the buttons JSON column to an array
    protected $casts = [
        'buttons' => 'array',
    ];
}
