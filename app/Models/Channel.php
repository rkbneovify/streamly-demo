<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $name name
@property varchar $url url
@property timestamp $created_at created at
@property timestamp $updated_at updated at
@property \Illuminate\Database\Eloquent\Collection $status belongsToMany
   
 */
class Channel extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'channels';

    /**
    * Mass assignable columns
    */
    protected $fillable=['name',
'url'];

    /**
    * Date time columns.
    */
    protected $dates=[];

    /**
    * statuses
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function statuses()
    {
        return $this->belongsToMany(Status::class,'channel_status');
    }



}