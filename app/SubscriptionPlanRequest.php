<?php

namespace App;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SubscriptionPlanRequest extends Model
{
    protected $table = "subscription_plan_request";

    public function plan()
    {
        return $this->hasOne(SubscriptionPlan::class, 'id', 'plan_id');
    }

    public static function getPlanRequest()
    {
        return SubscriptionPlanRequest::where('user_id', Auth::id())->latest()->first();
    }
}
