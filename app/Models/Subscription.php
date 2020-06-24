<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    protected $fillable=['user_id',
'name',
'stripe_id',
'stripe_status',
'stripe_plan',
'quantity',
'trial_ends_at',
'ends_at'];

    /**
    * Date time columns.
    */
    protected $dates=['trial_ends_at',
'ends_at'];


public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

}