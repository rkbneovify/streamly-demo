<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionTable extends Model
{
    /**
     * @var string
     */
    protected $table = 'session_table';
    public $timestamps = false;
    protected $fillable = [
        'connected_devices', 'users_id', 'status',
    ];
    /**
     * @var array
     */
    protected $guarded = [];
}
