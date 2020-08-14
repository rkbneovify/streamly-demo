<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property json $frontendjson frontendjson
   
 */
class FrontendText extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'frontend_text';
    public $timestamps = false;
    protected $casts = [
        
        'frontendjson' => 'array'
   ];
    /**
    * Mass assignable columns
    */
    protected $fillable=[
'frontendjson'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}