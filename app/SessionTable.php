<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\HotelRoomTable;
class SessionTable extends Model
{
    /**
     * @var string
     */
    
    protected $table = 'session_table';
    public $timestamps = false;
    protected $casts = [
     'device' => 'array'
   ];
    protected $fillable=[
        'users_id',
        'access_token',
        'user_ip',
        'logintime',
        'mac_address',
        'connected_devices',
        'device',
        'status',
        'created_on',
        'modified_on'];
        /**
    * Date time columns.
    */
    protected $dates=['logintime',
    'created_on',
    'modified_on'];
    /**
     * @var array
     */
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class,'users_id');
    }

    public function hotel()
    {
        return $this->hasOne(HotelRoomTable::class,'session_id');
    }
    /**
    protected static function boot()
    {
        static::creating(function ($model) {
            print('creating session triggered');
            // print_r(var_dump($model));
        });
        static::saving(function ($model) {
            print('saving session triggered');
            print_r(var_dump($model->getKey()));
        });
        static::saved(function ($model) {
            print('saved session triggered');
            print_r(var_dump($model->getKey()));
        });

        static::updating(function ($model) {
            dump("hello");
            \Log::info('updating session triggered');
            print_r(var_dump($model));
            if($model->status==0){
                \Log::info($model);
                
                $hotelroom = HotelRoomTable::where('session_id', '=', $model->id)->first();
                if ($hotelroom !== null) {

                \Log::info('$hotelroom !== null');
                    $user = $hotelroom->update([
                        'session_id' => null
                    ]);
                  }
            }
        });

        static::deleting(function ($model) {
            // bluh bluh
        });
        static::updated(function ($model) {
            // bluh bluh

            print('updated session triggered');
            print_r(var_dump($model->getKey()));
            print_r(var_dump($model->status));
            if($model->status==0){
                \Log::info($model);
                
                $hotelroom = HotelRoomTable::where('session_id', '=', $model->getKey())->first();
                if ($hotelroom !== null) {

                \Log::info('$hotelroom !== null');
                    $user = $hotelroom->update([
                        'session_id' => null
                    ]);
                  }
            }
        });
        
        parent::boot();
    }
    */
    public function getId()
    {
        return $this->getKey();
    }
}