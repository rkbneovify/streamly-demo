<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $address2 address2
@property varchar $city city
@property varchar $state state
@property char $zip5 zip5
   
 */
class AddressesCheckAvailability extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'addresses_check_availability';

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'address2',
'city',
'state',
'zip5'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}