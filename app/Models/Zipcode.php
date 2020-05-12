<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property char $zipcode zipcode
@property varchar $city city
@property varchar $state state
@property char $state_abbr state abbr
@property varchar $county_area county area
@property char $code code
@property double $latitude latitude
@property double $longitude longitude
@property tinyint $some_field some field
   
 */
class Zipcode extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'zipcodes';

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'zipcode',
'city',
'state',
'state_abbr',
'county_area',
'code',
'latitude',
'longitude',
'status'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}