<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentChannel extends Model
{
     
    /**
    * Database table name
    */
    protected $table = 'recent_channel';

    /**
    * Mass assignable columns
    */
    protected $fillable=['channels_id',
'user_id'];

    /**
    * Date time columns.
    */
    protected $dates=['created_at','updated_at'];

    /**
    * channel
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channels_id');
    }



}
