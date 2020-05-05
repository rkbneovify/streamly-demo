<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property tinyint $status status
   
 */
class ChannelStatus extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'channel_status';

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'status'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}