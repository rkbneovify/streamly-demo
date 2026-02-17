<?php

namespace App;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Model;

class PremiumChannelAccess extends Model
{
    protected $table = 'premium_channel_accesses';

    public function channel()
    {
        return $this->hasOne(Channel::class, 'id', 'channel_id');
    }
}
