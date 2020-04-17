<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $name name
@property datetime $created_on created on
@property datetime $modified_on modified on
@property \Illuminate\Database\Eloquent\Collection $user hasMany
   
 */
class Role extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'role';

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'name',
'created_on',
'modified_on'];

    /**
    * Date time columns.
    */
    protected $dates=['created_on',
'modified_on'];

    /**
    * users
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function users()
    {
        return $this->hasMany(User::class,'role_id');
    }



}