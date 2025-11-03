<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HoaUser extends Model
{
    protected $table = "hoausers";

    public function session()
    {
        return $this->hasMany(SessionTable::class, 'passcode', 'passcode');
    }

}
