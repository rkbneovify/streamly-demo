<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


        /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
              public function sessions()
             {
                return $this->hasMany(SessionTable::class,'users_id','id');
              }
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


              public $timestamps = false;
}
