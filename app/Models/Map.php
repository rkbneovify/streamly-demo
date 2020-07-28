<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property geometry $SHAPE SHAPE
@property varchar $zcta5ce10 zcta5ce10
@property varchar $affgeoid10 affgeoid10
@property varchar $geoid10 geoid10
@property varchar $aland10 aland10
@property varchar $awater10 awater10
   
 */
class Map extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'map';
    protected $casts = [
        'shape' => 'json',
      ];

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'SHAPE',
'zcta5ce10',
'affgeoid10',
'geoid10',
'aland10',
'awater10'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}