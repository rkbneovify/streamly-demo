<?php

namespace App;

use App\Models\Channel;
use App\Models\Zipcode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalZipcode extends Model
{
    use SoftDeletes;
    protected $table = 'local_zipcodes';

    public function channel()
    {
        return $this->hasOne(Channel::class, 'id', 'channel_id');
    }


    public static function zipcodeStatusCountByUse($zipcode)
    {
        $totalCount = LocalZipcode::where('zipcode', $zipcode)
            ->count();
        $activeCount = LocalZipcode::where('zipcode', $zipcode)
            ->where('status', 1)
            ->count();

        return $activeCount . "/" . $totalCount;

    }




}

