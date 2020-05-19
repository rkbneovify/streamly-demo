<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $StationName StationName
   
 */
class Gracenotechannel extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'gracenotechannels';

    /**
    * Mass assignable columns
    */
    protected $fillable=['StationName',
'CallSign'];

    /**
    * Date time columns.
    */
    protected $dates=[];

    public function gracenote()
    {
        return $this->belongsTo(Gracenote::class,'CallSign');
    }


}