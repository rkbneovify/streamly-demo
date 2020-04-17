<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $role_id role id
@property varchar $email email
@property varchar $password password
@property varchar $first_name first name
@property varchar $last_name last name
@property varchar $phone phone
@property varchar $address address
@property varchar $address2 address2
@property varchar $postal_code postal code
@property varchar $state state
@property varchar $country country
@property char $active active
@property datetime $created_on created on
@property datetime $modified_on modified on
@property Role $role belongsTo
@property \Illuminate\Database\Eloquent\Collection $payment hasMany
@property \Illuminate\Database\Eloquent\Collection $sessionTable hasMany
@property \Illuminate\Database\Eloquent\Collection $subscription hasMany
   
 */
class User extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'users';

    /**
    * Mass assignable columns
    */
    protected $fillable=['modified_on',
'role_id',
'email',
'first_name',
'last_name',
'phone',
'address',
'address2',
'postal_code',
'state',
'country',
'active',
'created_on',
'modified_on'];

    /**
    * Date time columns.
    */
    protected $dates=['created_on',
'modified_on'];

    /**
    * role
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }

    /**
    * payments
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function payments()
    {
        return $this->hasMany(Payment::class,'users_id');
    }
    /**
    * sessionTables
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function sessionTables()
    {
        return $this->hasMany(SessionTable::class,'users_id');
    }
    /**
    * subscriptions
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class,'users_id');
    }



}