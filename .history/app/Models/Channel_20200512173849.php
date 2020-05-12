<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $name name
@property varchar $url url
@property int $genre_id genre id
@property timestamp $created_at created at
@property timestamp $updated_at updated at
@property Genre $genre belongsTo
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
'url',
'genre_id','status','genres'];

    /**
    * Date time columns.
    */
    protected $dates=[];

    /**
    * genre
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function genre()
    {
        return $this->belongsTo(Genre::class,'genre_id');
    }

    
    


}