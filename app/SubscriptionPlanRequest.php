<?php

namespace App;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanRequest extends Model
{
    protected $table = "subscription_plan_request";

    public function plan()
    {
        return $this->hasOne(SubscriptionPlan::class, 'id', 'plan_id');
    }
}
