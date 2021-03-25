<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionTable extends Model
{
    /**
     * @var string
     */
    
    protected $table = 'session_table';
    public $timestamps = false;
    
    protected $fillable=[
        'users_id',
        'access_token',
        'user_ip',
        'logintime',
        'mac_address',
        'connected_devices',
        'device',
        'status',
        'created_on',
        'modified_on'];
        /**
    * Date time columns.
    */
    protected $dates=['logintime',
    'created_on',
    'modified_on'];
    /**
     * @var array
     */
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class,'users_id');
    }
}
