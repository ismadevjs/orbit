<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'min_amount',
        'percentage',
        'bonus',
        'features',
        'msg_investor'
    ];

    // Automatically cast the features column to array
    protected $casts = [
        'features' => 'array',
    ];
}
