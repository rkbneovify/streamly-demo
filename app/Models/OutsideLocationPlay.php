<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int unsigned $users_id users id
@property int $zipcode zipcode
@property varchar $address address
@property varchar $device_info device info
@property double $latitude latitude
@property double $longitude longitude
@property datetime $timestamp timestamp
@property User $user belongsTo
   
 */
class OutsideLocationPlay extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'outside_location_plays';
    public $timestamps = false;
    /**
    * Mass assignable columns
    */
    protected $fillable=[
'users_id',
'zipcode',
'address',
'device_info',
'latitude',
'longitude',
'timestamp'];
/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id','users_id'
    ];
    /**
    * Date time columns.
    */
    protected $dates=['timestamp'];

    /**
    * user
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user()
    {
        return $this->belongsTo('App\User','users_id');
    }




}