<?php

namespace App\Models;

use App\SubscriptionPlanRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

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
        'is_custom',
        'plan_duration',
        'is_never_expire',
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
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeFilterStatus($query, $status)
    {
        if (!empty($status)) {

            $query->where('custom_status', $status);
        }
        return $query;
    }

    public function scopeFilterCreatedAt($query, $sort)
    {
        if ($sort == null || $sort == 'desc') {
            $query->orderByDesc('created_at');
        }
        return $query;
    }

    public function scopeFilterSearch($query, $search)
    {
        if (!empty($search)) {


            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        return $query;
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

    public static function stripeSyncById($id)
    {
        try {
            // Get Stripe Secret
            $secret = DB::table('Stripe_keys')
                ->where('status', 'active')
                ->value('stripe_secret');

            if (!$secret) {

                // Alert::error('Error', 'Stripe secret not found.');
                // return redirect()->back();
            }

            \Stripe\Stripe::setApiKey($secret);

            // Fetch subscription from Stripe by ID
            $subscription = \Stripe\Subscription::retrieve([
                'id'     => $id,
                'expand' => ['latest_invoice'],
            ]);

            $item = $subscription->items->data[0] ?? null;

            if (!$item || !isset($item->price)) {


                // Alert::error('Error', "No price found for subscription");
                // return redirect()->back();
            }

            $priceId    = $item->price->id ?? null;
            $planAmount = isset($item->price->unit_amount)
                ? $item->price->unit_amount / 100
                : 0;

            $updated = DB::table('subscriptions')
                ->where('stripe_id', $id)
                ->update([
                    'stripe_plan'          => $priceId,
                    'stripe_status'        => $subscription->status,
                    'quantity'             => $subscription->quantity ?? 1,
                    'livemode'             => (int) $subscription->livemode,
                    'current_period_start' => Carbon::createFromTimestamp($subscription->current_period_start),
                    'renews_at'            => Carbon::createFromTimestamp($subscription->current_period_end),
                    'amount'               => $planAmount,
                    'updated_at'           => now(),
                ]);

            if (!$updated) {

                // Alert::error('Error', "No local record found for subscription");
                // return redirect()->back();
            }


            // Alert::success('Success', 'Subscription synced successfully.');
            return redirect()->back();
        } catch (\Stripe\Exception\InvalidRequestException $e) {

            // Alert::error('Error', "Stripe subscription not found !");
            // return redirect()->back();
        } catch (\Exception $e) {

            // Alert::error('Error', 'Sync failed: ' . $e->getMessage());
            // return redirect()->back();
        }
    }
}
