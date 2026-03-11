<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class HoaSubUserSubscription extends Model
{
    protected $table = 'hoa_sub_user_subscriptions';



    public function hoaSubUser()
    {
        $this->hasOne(HoaUser::class, 'sub_user_id', 'id');
    }

    public static function calcExpiryDate($month)
    {
        switch ($month) {
            case 1:
                $daysToAdd = 30;
                break;
            case 3:
                $daysToAdd = 90;
                break;
            case 6:
                $daysToAdd = 180;
                break;
            case 12:
                $daysToAdd = 365;
                break;
            default:
                $daysToAdd = 0;
                break;
        }
        return Carbon::now()->addDays($daysToAdd);
    }



}
