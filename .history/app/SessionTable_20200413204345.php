<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionTable extends Model
{
    /**
     * @var string
     */
    protected $table = 'tasks';

    /**
     * @var array
     */
    protected $guarded = [];
}
