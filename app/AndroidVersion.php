<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AndroidVersion extends Model
{
    protected $table = "android_version";
    protected $fillable = ['version', 'apk_url'];
}
