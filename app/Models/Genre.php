<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $name name
@property int $status status
@property datetime $created_on created on
@property datetime $modified_on modified on
@property \Illuminate\Database\Eloquent\Collection $channel hasMany
   
 */
class Genre extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'genre';

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'name',
'status',
'created_on',
'modified_on'];

    /**
    * Date time columns.
    */
    protected $dates=['created_on',
'modified_on'];

    /**
    * channels
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function channels()
    {
        return $this->hasMany(Channel::class,'genre_id');
    }



}