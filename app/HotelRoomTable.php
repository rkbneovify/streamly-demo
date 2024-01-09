<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelRoomTable extends Model
{
    /**
     * @var string
     */
    
    protected $table = 'hotel_rooms';
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'modified_on'; 
    
    protected $fillable=[
        'user_id',
        'hotel_room',
        'session_id',
        'created_on',
        'modified_on'];
        /**
    * Date time columns.
    */
    protected $dates=[
    'created_on',
    'modified_on'];
    /**
     * @var array
     */
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    } 
     public function session()
    {
        return $this->belongsTo(SessionTable::class,'session_id');
    }
}
