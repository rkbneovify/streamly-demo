<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    /**
     * Database table name
    */
    protected $table = 'email_otp';

    /**
     * Mass assignable columns
    */
    protected $fillable = ['email', 'otp', 'otp_expire'];
}
