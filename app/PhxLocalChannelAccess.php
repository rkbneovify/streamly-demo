<?php

namespace App;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Model;

class PhxLocalChannelAccess extends Model
{

    protected $table = 'phx_local_channel_accesses';

    public function channel()
    {
        return $this->hasOne(Channel::class, 'id', 'channel_id');
    }

}
