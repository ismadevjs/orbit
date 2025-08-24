<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageRule extends Model
{
    use HasFactory;

    protected $table = 'stages_rules';

    protected $fillable = [
        'user_id',
        'plan_id',
        'affiliate_stage_id'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function plan() {
        return $this->belongsTo(PricingPlan::class, 'plan_id', 'id');
    }

    public function affiliateStage() {
        return $this->belongsTo(AffiliateStage::class, 'affiliate_stage_id', 'id');
    }
}
