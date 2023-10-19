<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class FaqChatboat extends Model
{
    //
    public $table = "faqs";
    
    public $timestamps = false;
    
    protected $fillable  = ['cat_id','question_key','question_answer'];
}
