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
   public $login_limit = 3;
    /**
     * @var array
     */
    protected $guarded = [];
}
