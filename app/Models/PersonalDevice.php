<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
@property int $user_id user_id
@property text $device_id device_id
@property text $personal_devicename personal_devicename
@property timestamp $created_at created at
@property timestamp $updated_at updated at
   
 */
class PersonalDevice extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'personal_device';

    /**
    * Mass assignable columns
    */
    protected $fillable = [
        'user_id','device_id','personal_devicename'
    ];

    /**
    * Date time columns.
    */
    protected $dates = ['updated_at','created_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function session()
    {
        return $this->belongsTo(SessionTable::class,'device_id');
    }
}