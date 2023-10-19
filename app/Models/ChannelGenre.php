<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $channels_id channels id
@property Channel $channel belongsTo
   
 */
class ChannelGenre extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'channels_genre';

    /**
    * Mass assignable columns
    */
    protected $fillable=['channels_id',
'genre_id'];
public $timestamps = false;
    /**
    * Date time columns.
    */
    protected $dates=[];

    /**
    * channel
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channels_id');
    }
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