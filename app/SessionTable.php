<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\HotelRoomTable;
use App\Models\PersonalDevice;
class SessionTable extends Model
{
    /**
     * @var string
     */
    
    protected $table = 'session_table';
    public $timestamps = false;
    protected $casts = [
     'device' => 'array'
   ];
   protected $appends = ['device_img','device_detail','device_type', 'authtokenofDevice']; 
   protected $hidden = ['device_detail'];//device detail from json
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

    public function hotel()
    {
        return $this->hasOne(HotelRoomTable::class,'session_id');
    }

    public function pdname(){
        return $this->hasOne(PersonalDevice::class, 'device_id','device_id');  
    }
    /**
    protected static function boot()
    {
        static::creating(function ($model) {
            print('creating session triggered');
            // print_r(var_dump($model));
        });
        static::saving(function ($model) {
            print('saving session triggered');
            print_r(var_dump($model->getKey()));
        });
        static::saved(function ($model) {
            print('saved session triggered');
            print_r(var_dump($model->getKey()));
        });

        static::updating(function ($model) {
            dump("hello");
            \Log::info('updating session triggered');
            print_r(var_dump($model));
            if($model->status==0){
                \Log::info($model);
                
                $hotelroom = HotelRoomTable::where('session_id', '=', $model->id)->first();
                if ($hotelroom !== null) {

                \Log::info('$hotelroom !== null');
                    $user = $hotelroom->update([
                        'session_id' => null
                    ]);
                  }
            }
        });

        static::deleting(function ($model) {
            // bluh bluh
        });
        static::updated(function ($model) {
            // bluh bluh

            print('updated session triggered');
            print_r(var_dump($model->getKey()));
            print_r(var_dump($model->status));
            if($model->status==0){
                \Log::info($model);
                
                $hotelroom = HotelRoomTable::where('session_id', '=', $model->getKey())->first();
                if ($hotelroom !== null) {

                \Log::info('$hotelroom !== null');
                    $user = $hotelroom->update([
                        'session_id' => null
                    ]);
                  }
            }
        });
        
        parent::boot();
    }
    */
    public function getId()
    {
        return $this->getKey();
    }

    public function getDeviceDetailAttribute($size=null){
    return $this->device['device'];
    }
    public function getDeviceImgAttribute($size=null)
    { 
    //     if ($this->device['device']=='desktop') {
    //     $imagePath="send desktop imgee";
    //     return $imagePath;
    // } else {
    //     $imagePath=$this->device['device'];
    //     return $imagePath;
    // }
    // return $this->attributes;
    return [
        'desktop'=>asset("/uploads/devicelogo/").'/tv/normaltv.png',
        'device-rokutv'=>asset("/uploads/devicelogo/").'/tv/roku.png',
        'device-appletv'=>asset("/uploads/devicelogo/").'/tv/appletv.png',
        'device-firetv'=>asset("/uploads/devicelogo/").'/tv/firetv.png',
        'device-smarttv'=>asset("/uploads/devicelogo/").'/tv/smarttv.png',
        'device-mobile'=>asset("/uploads/devicelogo/").'/mobile/mobile.png',
        'device-tablet'=>asset("/uploads/devicelogo/").'/mobile/tablet.png',
        'device-ipad'=>asset("/uploads/devicelogo/").'/mobile/ipad.png',
        'device-iphone'=>asset("/uploads/devicelogo/").'/mobile/iphone.png'
    ][$this->device['device']?? null] ?? asset("/uploads/devicelogo/").'/tv/normaltv.png';
       
    }
    public function getDeviceTypeAttribute($size=null)
    { 
    // return [
    //     "data-browser='1'"=>'web',
    //     "data-browser='2'"=>'web',
    //     "data-browser='3'"=>'web',
    //     "data-browser='4'"=>'web',
    //     "data-browser='5'"=>'web',
    //     "data-browser='6'"=>'web',
    //     "data-browser='7'"=>'web'
    // ][$this->device['browserclass']?? null] ?? "non-web";
       return $this->pdname?'non-web':'web';
    }
    public function getsubscriptionStatusAttribute()
    { 
        $subscriptionStatus=DB::table('subscriptions')->where('user_id', $this->users_id)->value('stripe_status');
        return $subscriptionStatus ?? 'unsubscribe';
    }
    public function getauthtokenofDeviceAttribute()
    { 
        $authToken=DB::table('session_table')->where('device_id', $this->device_id)->value('access_token');
        return $authToken ?? 'null';
    }
    
}