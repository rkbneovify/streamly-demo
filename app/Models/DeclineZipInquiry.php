<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $email email
@property varchar $zip zip
@property varchar $city city
@property varchar $state state
@property timestamp $created_at created at
@property timestamp $modified_at modified at
   
 */
class DeclineZipInquiry extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'decline_zip_inquiries';

    /**
    * Mass assignable columns
    */
    protected $fillable=['created_at',
'email',
'zip',
'city',
'frequency',
'state',
'updated_at'];

    /**
    * Date time columns.
    */
    protected $dates=['updated_at','created_at'];




}