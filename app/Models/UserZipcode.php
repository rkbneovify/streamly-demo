<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserZipcode extends Model
{
    protected $table = 'user_zipcodes';

    protected $fillable = ['user_id', 'zipcode'];
}
