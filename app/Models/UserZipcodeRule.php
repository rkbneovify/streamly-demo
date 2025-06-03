<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserZipcodeRule extends Model
{
    protected $table = 'user_zipcode_rules';

    protected $fillable = ['email', 'zipcode', 'validate'];
}
