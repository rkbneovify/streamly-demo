<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $StationName StationName
   
 */
class Gracenoteevents extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'gracenote_events';

    /**
    * Mass assignable columns
    */
    protected $fillable=['EventTitle',
'CallSign'];


    /**
    * Date time columns.
    */
    protected $dates=[];

    public function gracenote()
    {
        return $this->belongsTo(Gracenote::class,'CallSign');
    }

    public function gracenotesprograms()
    {
        return $this->hasMany(Gracenote::class,'CallSign');
    }


}