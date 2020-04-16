<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionTable extends Model
{
    /**
     * @var string
     */
    protected $table = 'session_table';

    /**
     * @var array
     */
    protected $guarded = [];
}
