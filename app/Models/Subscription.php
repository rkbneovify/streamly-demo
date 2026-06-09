<?php

namespace App\Models;

use App\SubscriptionPlanRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
   @property bigint unsigned $user_id user id
@property varchar $name name
@property varchar $stripe_id stripe id
@property varchar $stripe_status stripe status
@property varchar $stripe_plan stripe plan
@property int $quantity quantity
@property timestamp $trial_ends_at trial ends at
@property timestamp $ends_at ends at
@property timestamp $created_at created at
@property timestamp $updated_at updated at

 */
class Subscription extends Model
{

    /**
     * Database table name
     */
    protected $table = 'subscriptions';

    /**
     * Mass assignable columns
     */
    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'user_id',
        'name',
        'stripe_id',
        'stripe_status',
        'stripe_plan',
        'quantity',
        'livemode',
        'current_period_start',
        'custom_status',
        'trial_ends_at',
        'renews_at',
        'ends_at',
        'canceled_at'
    ];

    /**
     * Date time columns.
     */
    protected $dates = [
        'trial_ends_at',
        'renews_at',
        'ends_at'
    ];


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function planRequest()
    {
        return $this->hasOne(
            SubscriptionPlanRequest::class,
            'user_id',
            'user_id'
        )->where('status', 'under_review')->latest();
    }

    public function plan()
    {
        return $this->hasOne(
            SubscriptionPlan::class,
            'id',
            'custom_plan_id'
        );
    }

    public static function isSubscriptionCustom($subscription)
    {
        $isCustom = false;
        if ($subscription->plan_duration != null && strpos($subscription->stripe_id, 'sub_custom') !== false) {
            $isCustom = true;
        } elseif ($subscription->plan_duration != null) {
            $isCustom = true;
        }

        return $isCustom;
    }

    public static function syncCustomExpired()
    {
        DB::beginTransaction();
        try {
            $subscriptions = Subscription::whereHas('user')   // only subscriptions with a related user
                ->with('user')      // still eager load user data
                ->whereNotNull('custom_status')
                ->where('renews_at', '<', Carbon::now())
                ->get();

            foreach ($subscriptions as $subscription) {
                $subscription->update([
                    'custom_status' => 'expired'
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
