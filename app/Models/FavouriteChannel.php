<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $channels_id channels id
@property Channel $channel belongsTo
   
 */
class FavouriteChannel extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'favourite_channel';

    /**
    * Mass assignable columns
    */
    protected $fillable=['channels_id',
'user_id'];

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





}