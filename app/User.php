<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Laravel\Cashier\Billable;
use App\Models\Channel;
use App\Models\Subscription;
use App\Models\UserZipcodeRule;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use EntrustUserTrait;
    use Billable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact',
        'gender',
        'dob',
        'email_verified_at',
        'is_active',
        'zipcode',
        'forced_change',
        'address',
        'country',
        'mode',
        'state',
        'city',
        'stripe_id',
        'card_brand',
        'card_last_four',
        'connected_device',
        'user_type',
        'is_custom_user',
        'access_premium_channel'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        return [
            'name' => $this->name,
            'roles' => $this->roles
        ];
    }

    public static function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password' => 'required|max:255',
            'zipcode' => 'required|regex:/^[0-9]{3,7}$/',
            'zipcode' => 'required|exists:zipcodes,zipcode,status,1',
            'dob' => 'date|date_format:Y-m-d',
            'gender' => 'max:1',

        ], ['zipcode.exists' => 'Unfortunately service is not yet available for the zip code you submitted.']);
    }

    public static function updateValidator(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required',
            'dob' => 'date|date_format:Y-m-d',
            'gender' => 'max:1'
        ]);
    }


    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_user');
    }

    public function favorites()
    {
        return $this->belongsToMany(Channel::class, 'favourite_channel', 'user_id', 'channels_id')->select('channels.id', 'name', DB::raw('IFNULL(channels.callSign,channels.name) callSign'), DB::raw('IFNULL(channels.stationName,channels.name) stationName'), 'url', 'smooth_url', 'dash_url', 'image')->where('status', '1')->withTimeStamps();
    }



    public function recentschannel()
    {
        return $this->belongsToMany(Channel::class, 'recent_channel', 'user_id', 'channels_id')->where('status', '1')->latest()->take(10)->distinct();
    }

    // public function subscriptions()
    // {
    //     return $this->hasMany(Subscription::class,'users_id');
    // }

    /**
     * outsideLocationPlays
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outsideLocationPlays()
    {
        return $this->hasMany(OutsideLocationPlay::class, 'users_id');
    }

    public function customSubscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }
    public function subscriptionLatest()
    {
        return $this->hasOne(Subscription::class);
    }
    public function sessions()
    {
        return $this->hasMany(SessionTable::class, 'users_id', 'id')
            ->where('status', 1);
    }

    public function rooms()
    {
        return $this->hasMany(HotelRoomTable::class, 'user_id', 'id');
    }

    public function hoaHouses()
    {
        return $this->hasMany(HoaUser::class, 'user_id', 'id');
    }

    public function local()
    {
        return $this->hasMany(LocalZipcode::class, 'zipcode', 'zipcode')
            ->where('status', 1);
    }

    public function zipcodeRule()
    {
        return $this->hasMany(UserZipcodeRule::class, 'email', 'email');
    }
    public function premium()
    {
        return $this->hasMany(PremiumChannelAccess::class, 'user_id', 'id')
            ->where('status', 1);
    }

    // user type
    public function scopeUserType($query, $value)
    {
        if (empty($value))
            return $query;
        if ($value == 'all') {
            $query->having('local_count', '<=', 0)->having('premium_count', '<=', 0);
        } elseif ($value == 'local') {
            $query->having('local_count', '>', 0)->having('premium_count', '<=', 0)
                ->wherehas('zipcodeRule', function ($q) {
                    $q->where('validate', 2);
                });
        } elseif ($value == 'all_local') {
            $query->having('local_count', '>', 0)->having('premium_count', '<=', 0)
                ->wherehas('zipcodeRule', function ($q) {
                    $q->where('validate', 1);
                });
        } elseif ($value == 'all_premium') {
            $query->having('local_count', '<=', 0)->having('premium_count', '>', 0);
        } elseif ($value == 'local_premium') {
            $query->having('local_count', '>', 0)->having('premium_count', '>', 0)
                ->wherehas('zipcodeRule', function ($q) {
                    $q->where('validate', 2);
                });
        } elseif ($value == 'all_local_premium') {
            $query->having('local_count', '>', 0)->having('premium_count', '>', 0);
        }
        return $query;
    }

    public function hasActiveSubscription()
    {
        // if ($this->customSubscription->plan_duration != null && strtolower($this->customSubscription->plan_duration) == 'never') {
        //     return false;
        // }
        // return !($this->customSubscription &&
        //     $this->customSubscription->renews_at > now());

        if (
            $this->customSubscription &&
            $this->customSubscription->plan_duration !== null &&
            strtolower($this->customSubscription->plan_duration) === 'never'
        ) {
            return false;
        }

        return $this->customSubscription &&
            $this->customSubscription->renews_at <= now();
    }
}
