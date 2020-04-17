<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $subscriptions_id subscriptions id
@property int $users_id users id
@property decimal $amount amount
@property datetime $payment_date payment date
@property varchar $receipt_url receipt url
@property datetime $created_on created on
@property datetime $modified_on modified on
@property User $user belongsTo
@property Subscription $subscription belongsTo
   
 */
class Payment extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'payment';

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'subscriptions_id',
'users_id',
'amount',
'payment_date',
'receipt_url',
'created_on',
'modified_on'];

    /**
    * Date time columns.
    */
    protected $dates=['payment_date',
'created_on',
'modified_on'];

    /**
    * user
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user()
    {
        return $this->belongsTo(User::class,'users_id');
    }

    /**
    * subscription
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class,'subscriptions_id');
    }




}