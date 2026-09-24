<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\HotelRoomTable;
use App\Models\PersonalDevice;
use Carbon\Carbon;
use DeviceDetector\DeviceDetector;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    protected $appends = ['logout_at', 'token_expire_at', 'remaining_days', 'device_img', 'device_detail', 'device_type', 'subscriptionStatus', 'deviceSign'];
    protected $hidden = ['device_detail']; //device detail from json
    protected $fillable = [
        'users_id',
        'access_token',
        'user_ip',
        'logintime',
        'mac_address',
        'connected_devices',
        'device',
        'status',
        'created_on',
        'modified_on'
    ];
    /**
     * Date time columns.
     */
    protected $dates = [
        'logintime',
        'created_on',
        'modified_on'
    ];
    /**
     * @var array
     */
    protected $guarded = [];


    public function scopeFilterStatus($query, $status)
    {
        if ($status !== null) {
            return $query->where('status', $status);
        }
    }

    public function scopeFilterSearch($query, $search)
    {

        if (!empty($search)) {

            if (strtolower($search) !== 'web') {



                $query->whereHas('pdInfo', function ($query) use ($search) {
                    $query->where('personal_devicename', 'like', "%{$search}%")
                        ->orWhere('platform', 'like', "%{$search}%")
                        ->orWhere('device_model', 'like', "%{$search}%")
                        ->orWhere('build_version', 'like', "%{$search}%")
                        ->orWhere('zipcode', 'like', "%{$search}%");
                });
            } else {

                // Filter web sessions directly in SQL
                $query->whereRaw("LOWER(device_type) = 'web'");
            }
        }
    }

    public function status() {}

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function hotel()
    {
        return $this->hasOne(HotelRoomTable::class, 'session_id');
    }

    public function pdname()
    {
        return $this->hasOne(PersonalDevice::class, 'device_id', 'device_id')
            ->latest('updated_at');
    }
    public function pdInfo()
    {
        return $this->hasOne(PersonalDevice::class, 'device_id', 'device_id')
            ->latest();
    }
    public function pdInfoPasscode()
    {
        return $this->hasOne(PersonalDevice::class, 'device_id', 'device_id')
            // ->whereNotNull('passcode')
            ->latest('updated_at');
    }

    public function subSession()
    {
        return $this->hasMany(SubSession::class, 'session_id', 'id')->latest();
    }
    public function subSessionPasscode()
    {
        return $this->hasMany(SubSession::class, 'session_id', 'id')
            ->whereNotNull('passcode')
            ->where('is_main_account', 0)
            ->latest();
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


    public function getLogoutAtAttribute()
    {
        if ($this->users_id) {
            return null;
        }

        try {
            return  ActivityLog::where('properties->data->users_id', $this->users_id)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getTokenExpireAtAttribute()
    {
        if (!$this->access_token) {
            return null;
        }

        try {
            $payload = JWTAuth::setToken($this->access_token)->getPayload();

            return Carbon::createFromTimestamp(
                $payload->get('exp')
            )->toDateTimeString();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getRemainingDaysAttribute()
    {
        if (!$this->access_token) {
            return null;
        }

        try {
            $payload = JWTAuth::setToken($this->access_token)->getPayload();

            $expiresAt = Carbon::createFromTimestamp($payload->get('exp'));

            return max(
                0,
                (int) ceil(now()->diffInSeconds($expiresAt) / 86400)
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getId()
    {
        return $this->getKey();
    }

    public function getDeviceDetailAttribute($size = null)
    {
        return $this->device['device'];
    }
    public function getDeviceImgAttribute($size = null)
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
            'desktop' => asset("/uploads/devicelogo/") . '/tv/normaltv.png',
            'device-rokutv' => asset("/uploads/devicelogo/") . '/tv/roku.png',
            'device-appletv' => asset("/uploads/devicelogo/") . '/tv/appletv.png',
            'device-firetv' => asset("/uploads/devicelogo/") . '/tv/firetv.png',
            'device-smarttv' => asset("/uploads/devicelogo/") . '/tv/smarttv.png',
            'device-mobile' => asset("/uploads/devicelogo/") . '/mobile/mobile.png',
            'device-tablet' => asset("/uploads/devicelogo/") . '/mobile/tablet.png',
            'device-ipad' => asset("/uploads/devicelogo/") . '/mobile/ipad.png',
            'device-iphone' => asset("/uploads/devicelogo/") . '/mobile/iphone.png'
        ][$this->device['device'] ?? null] ?? asset("/uploads/devicelogo/") . '/tv/normaltv.png';
    }
    public function getdeviceSignAttribute($size = null)
    {
        return [
            'desktop' => 'normaltv',
            'device-rokutv' => 'roku',
            'device-appletv' => 'appletv',
            'device-firetv' => 'firetv',
            'device-smarttv' => 'smarttv',
            'device-mobile' => 'mobile',
            'device-tablet' => 'tablet',
            'device-ipad' => 'ipad',
            'device-iphone' => 'iphone'
        ][$this->device['device'] ?? null] ?? 'normaltv';
    }
    public function getDeviceTypeAttribute($size = null)
    {

        $ua = strtolower($this->device_id ?? '');

        if (strpos($ua, 'web') !== false) {
            return 'web';
        } else {
            return 'non-web';
        }



        // $ua = strtolower($this->useragent);

        // // Rule 1: Smart TVs → non-web
        // if (preg_match('/smart-tv|hbbtv|tizen|netcast|appletv|googletv|tv safari/', $ua)) {
        //     return 'non-web';
        // }

        // // Rule 2: Common web browsers
        // if (
        //     strpos($ua, 'mozilla/5.0') !== false &&
        //     preg_match('/(chrome|safari|firefox|edge|opr|samsungbrowser)/', $ua) &&
        //     preg_match('/(applewebkit|gecko|trident|blink)/', $ua)
        // ) {
        //     return 'web';
        // }

        // Rule 3: Everything else → non-web
        // return 'non-web';

        // return [
        //     "data-browser='1'"=>'web',
        //     "data-browser='2'"=>'web',
        //     "data-browser='3'"=>'web',
        //     "data-browser='4'"=>'web',
        //     "data-browser='5'"=>'web',
        //     "data-browser='6'"=>'web',
        //     "data-browser='7'"=>'web'
        // ][$this->device['browserclass']?? null] ?? "non-web";
        // return ($this->pdname->personal_devicename ?? null) ? 'non-web' : 'web';
    }
    public function getsubscriptionStatusAttribute()
    {
        $subscriptionStatus = DB::table('subscriptions')->where('user_id', $this->users_id)->value('stripe_status');
        return $subscriptionStatus ?? 'unsubscribe';
    }

    public static function getCurrentUserByToken()
    {
        $session =  SessionTable::where('access_token', request()->bearerToken())->first();
        if ($session != null) {
            return User::where('id', $session->users_id)->first();
        }
        return null;
    }
}
