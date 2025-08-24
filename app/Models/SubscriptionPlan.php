<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'minimum_deposit', 'percentage', 'bonus', 'bonus_duration', 'msg_investor'
    ];

    public function calculateNextBonusDate($startDate)
    {
        $date = Carbon::parse($startDate);

        switch ($this->bonus_duration) {
            case 'hourly':
                return $date->addHour();
            case 'daily':
                return $date->addDay();
            case 'monthly':
                return $date->addMonth();
            case 'yearly':
                return $date->addYear();
            default:
                throw new \Exception('Invalid bonus duration');
        }
    }


}
