<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ChatboatCategory extends Model
{
    //
    public $table = "chatboat_category";
    
    public $timestamps = false;
    
    protected $fillable  = ['name'];
}
