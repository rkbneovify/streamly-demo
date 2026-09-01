<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';

    public static function getPlanDetail($planId)
    {
        return SubscriptionPlan::where('id', $planId)->first();
    }
}
