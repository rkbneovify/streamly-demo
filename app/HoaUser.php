<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HoaUser extends Model
{
    protected $table = "hoausers";

    public function session()
    {
        return $this->hasMany(SessionTable::class, 'passcode', 'passcode');
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

    public static function getHoaUserWithDetails($userId)
    {
        $user = User::find($userId);
        $currentUser = HoaUser::where('user_id', $userId)
            ->where('passcode', request()->passcode)
            ->first();
        $roleId = $user->roles->pluck('id')[0];
        if ($roleId == 7 && $currentUser == null) {
            return HoaUser::where('user_id', $userId)->with('session')->get();
        }

        return [];
    }

    public static function getCurrentHoaUser($userId)
    {
        $currentUser = HoaUser::where('user_id', $userId)
            ->where('passcode', request()->passcode)
            ->with('session')
            ->first();


        return $currentUser;
    }

}
