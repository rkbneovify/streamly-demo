<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasscodeResetToken extends Model
{
    use SoftDeletes;

    protected $table = "passcode_reset_tokens";
}
