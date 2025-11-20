<?php

namespace App;

use App\Models\Channel;
use App\Models\Zipcode;
use Illuminate\Database\Eloquent\Model;

class LocalZipcode extends Model
{
    protected $table = 'local_zipcodes';

    public function channel()
    {
        return $this->hasOne(Channel::class, 'id', 'channel_id');
    }





}

