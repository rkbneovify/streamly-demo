<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $subscriptionpack_id subscriptionpack id
@property int $users_id users id
@property date $start_date start date
@property date $expiry_date expiry date
@property datetime $created_on created on
@property User $user belongsTo
@property Subscriptionpack $subscriptionPack belongsTo
@property \Illuminate\Database\Eloquent\Collection $payment hasMany
   
 */
class Subscription extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'subscriptions';
    
    const CREATED_AT = 'created_on';
const UPDATED_AT = 'modified_on';
    /**
    * Mass assignable columns
    */
    protected $fillable=[
'subscriptionpack_id',
'users_id',
'start_date','status'
];

    /**
    * Date time columns.
    */
    protected $dates=['start_date',
'expiry_date',
'created_on',
'modified_on',
];

    /**
    * user
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user()
    {
        return $this->belongsTo('App\User','users_id');
    }

    /**
    * subscriptionpack
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function subscriptionpack()
    {
        return $this->belongsTo(SubscriptionPack::class,'subscriptionpack_id');
    }

    /**
    * payments
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function payments()
    {
        return $this->hasMany(Payment::class,'subscriptions_id');
    }



}