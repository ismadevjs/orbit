<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;

    protected $fillable = [
        'percentage',
        'capital',
        'user_id',
        'employe_id',
        'reference',
        'invest_date',
        'percentage',
        'can_invite',
        'duration'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function incentiveInvestors()
    {
        return $this->hasMany(IncentiveInvestor::class);  // Ensuring the correct foreign key reference
    }


}
