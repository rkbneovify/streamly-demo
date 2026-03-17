<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class HoaUser extends Model
{
    protected $table = "hoausers";
    protected $appends = ['subscription_expire_in'];
    public function session()
    {
        return $this->hasMany(SessionTable::class, 'passcode', 'passcode')
            ->where('status', 1);
    }

    public function subscription()
    {
        return $this->hasOne(HoaSubUserSubscription::class, 'sub_user_id', 'id')
            ->whereNotNull('plan_start_at')
            ->latest();
    }
    public function subscriptionLatest()
    {
        return $this->hasOne(HoaSubUserSubscription::class, 'sub_user_id', 'id')->latest();
    }


    public function getSubscriptionExpireInAttribute()
    {
        // Get the latest two subscriptions for this user
        $subscription = HoaSubUserSubscription::where('sub_user_id', $this->id)
            ->latest()->first();

        // If no subscriptions found
        if ($subscription == null) {
            return null;
        }


        // Function to check and format expiry
        $checkExpiry = function ($subscription) {
            if (!$subscription || !$subscription->plan_expires_at) {
                return null;
            }

            $expire = Carbon::parse($subscription->plan_expires_at);
            $now = Carbon::now();

            if ($expire->isPast()) {
                return 0; // Expired
            }



            return $expire->diffInDays($now);
        };

        // ✅ Check second latest first
        $result = $checkExpiry($subscription);



        return $result ?? 0;
    }


    public static function markedEmailVerified($userId)
    {
        $user = HoaUser::where('id', $userId)->first();
        if ($user != null) {
            $user->email_verified_at = now();
            $user->save();
        }
    }

    public static function isEmailVerified($userId)
    {
        $user = HoaUser::where('id', $userId)

            ->first();
        if ($user != null && $user->email_verified_at != null) {
            return true;
        }

        return false;
    }

    public static function generatePasscodeForHoa(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $passcode = '';
        do {
            for ($i = 0; $i < 10; $i++) {
                $passcode .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $exists = DB::table('hoausers')
                ->where('passcode', $passcode)
                ->exists();
        } while ($exists);

        return $passcode;
    }

    public static function getHoaHouseList()
    {
        if (request()->has('passcode')) {
            $uid = UnHashUserID(request()->passcode);
            $user = User::find($uid);
            if ($user != null) {
                $role = $user->roles->pluck('name')[0];
                if ($role == 'hoa') {
                    return HoaUser::where('user_id', $uid)
                        ->with('session')
                        ->get();
                }
            }

        }

        if (request()->has('email')) {
            $user = User::where('email', request()->email)->first();
            if ($user != null) {
                $role = $user->roles->pluck('name')[0];
                if ($role == 'hoa') {
                    return HoaUser::where('user_id', $user->id)
                        ->with('session')
                        ->get();
                }
            }

        }


        return [];


    }

    public static function getCurrentHoaUser()
    {



        if (!empty(request()->passcode)) {
            return HoaUser::where('passcode', request()->passcode)
                ->where('status', 1)
                ->with('session')
                ->first();
        }


        return null;

    }

    public static function isHoaMainUser()
    {


        if (request()->has('passcode')) {
            $uid = UnHashUserID(request()->passcode);
            $user = User::find($uid);
            if ($user != null) {
                $role = $user->roles->pluck('name')[0];

                if ($role == 'hoa') {
                    return true;
                }
            }
        }

        if (request()->has('email')) {
            $user = User::where('email', request()->email)
                ->first();
            if ($user != null) {
                $role = $user->roles->pluck('name')[0];

                if ($role == 'hoa') {
                    return true;
                }
            }

        }

        return false;





    }

    public static function getActiveHousesCount($userId)
    {
        return HoaUser::where('user_id', $userId)
            ->count();

    }

    public static function isHouseAvailable()
    {
        $numOfHouses = auth()->user()->num_of_houses;
        $numOfHousesAssignCount = HoaUser::where('user_id', auth()->user()->id)
            ->count();
        if ($numOfHousesAssignCount >= $numOfHouses) {
            return false;
        }
        return true;

    }

    public static function updateDevice($user, Request $request)
    {

        // $houseCount = ($user->num_of_houses - $request->num_of_houses);
        // $deviceLimitCountPerHouse = ($user->device_per_house - $request->device_per_house);

        $addedHouseCount = HoaUser::where('user_id', $user->id)
            ->count();

        if ((int) $request->num_of_houses <= 0) {
            return [
                'status' => false,
                'message' => 'Invalid number of houses'
            ];
        }

        if ((int) $request->num_of_houses < $addedHouseCount) {
            return [
                'status' => false,
                'message' => 'Cannot reduce houses below added House count: ' . $addedHouseCount
            ];
        }


        if ($user->device_per_house != $request->device_per_house) {
            if ($user->device_per_house > $request->device_per_house) {
                $passcodeList = HoaUser::where('user_id', $user->id)->pluck('passcode');
                SessionTable::where('status', 0)->delete();
                $sessions = SessionTable::whereNotNull('passcode')
                    ->whereIn('passcode', $passcodeList)
                    ->get();
                foreach ($sessions as $item) {
                    JWTAuth::setToken($item->access_token)->invalidate(true);
                    $item->delete();
                }

            }
        }
        $user->num_of_houses = $request->num_of_houses;
        $user->device_per_house = $request->device_per_house;
        $user->save();
        return null;

    }

    public static function getCurrentSubUserByToken()
    {
        $session = SessionTable::where('access_token', request()->bearerToken())
            ->where("status", 1)
            ->where('is_main_account', 0)
            ->whereNotNull('passcode')
            ->first();
        if ($session != null) {
            return HoaUser::where('passcode', $session->passcode)
                ->where('status', 1)
                ->first();
        }
        return null;
    }

    public static function getSubUserActiveSessions($user)
    {
        // here $user as hoa sub user
        $uid = $user->user_id;
        return SessionTable::with([
            'pdname'
        ])
            ->where('passcode', $user->passcode)
            ->where("status", 1)
            ->select('user_ip', 'logintime')
            ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id, SUBSTRING_INDEX(mac_address, '#~', 1) device_name, device")
            ->latest("created_on")->get()->makeHidden("pdname")->map(function ($address) {
                $address['session_id'] = HashUserID($address['id']);
                $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? null;
                $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);

                // ✅ Fix logintime format (remove microseconds)
                if (!empty($address['logintime'])) {
                    $address['logintime'] = Carbon::parse($address['logintime'])->setTimezone('UTC')->toISOString();
                }

                unset($address['pdname'], $address['device'], $address['id']);
                return $address;
            });

    }

    public static function activeHouseCount()
    {
        $userId = auth()->user()->id;
        return HoaUser::where('user_id', $userId)
            ->where('status', 1)
            ->count();
    }
    public static function inactiveHouseCount()
    {
        $userId = auth()->user()->id;
        return HoaUser::where('user_id', $userId)
            ->where('status', 0)
            ->count();
    }

    public static function getRemainingDeviceCount()
    {
        // remainig device calc
        $totalDevices = (auth()->user()->num_of_houses * auth()->user()->device_per_house);
        $allotedDevices = HoaUser::where('user_id', auth()->id())
            ->whereNotNull('device_limit')
            ->sum('device_limit');

        $remainingDevices = $totalDevices - $allotedDevices;
        return $remainingDevices;

    }

    public static function getDeviceCount()
    {

        return (int) HoaUser::where('user_id', auth()->id())
            ->whereNotNull('device_limit')
            ->sum('device_limit');



    }

    public function scopeStatus($query, $status)
    {
        if (in_array($status, ['0', '1'])) {
            $query->where('status', $status);
        }
        return $query;
    }

    public function scopeSearch($query, $term)
    {
        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('ordering', 'like', "%{$term}%")
                    ->orWhere('passcode', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('house_no', 'like', "%{$term}%");
            });
        }
        return $query;
    }

    public function scopeSort($query, $sort)
    {
        if ($sort == 'latest') {
            $query->latest();
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }
        return $query;
    }

    public static function mainUserFcmToken($userId)
    {
        $tokens = SessionTable::where('users_id', $userId)
            ->where('status', 1)
            ->where('is_main_account', 1)
            ->whereNotNull('fcm_token')
            ->latest('created_on')
            ->pluck('fcm_token')->toArray();

        if (count($tokens) > 0) {
            return $tokens;
        }
        return null;
    }

}
