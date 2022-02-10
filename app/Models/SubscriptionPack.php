<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $name name
@property int $duration duration
@property decimal $rate rate
@property datetime $created_on created on
@property datetime $modified_on modified on
@property \Illuminate\Database\Eloquent\Collection $subscription hasMany
   
 */
class SubscriptionPack extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'subscription_pack';
    
    const CREATED_AT = 'created_on';
const UPDATED_AT = 'modified_on';
    /**
    * Mass assignable columns
    */
    protected $fillable=[
'name',
'duration',
'rate',
'created_on',
'modified_on'];

    /**
    * Date time columns.
    */
    protected $dates=['created_on',
'modified_on'];

    /**
    * subscriptions
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class,'subscriptionpack_id');
    }



}