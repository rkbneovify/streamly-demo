<?php
namespace App\Models;
use App\Models\FavouriteChannel;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
    // protected $casts = [
    //     'genrename' => 'array'
    //   ];
    /**
    * Mass assignable columns
    */
    protected $fillable=['name',
'url','callSign',
'dash_url',
'smooth_url',
'genre_id','status','genres','image','stationName'];
protected $appends = ['logo'];

protected $casts = [
    'programs' => 'array',
    'Programs' => 'array',
    'Channel' => 'array',
    'events' => 'array','genrename' => 'array',
    'genre_name' => 'string',
];
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
    public function genresync()
    {
        return $this->belongsToMany(Genre::class, 'channels_genre', 
        'channels_id', 'genre_id');
    }
    public function getLogoAttribute($size=null)
{ if ($this->image) {
    $imagePath=asset("/uploads/channellogo/".$this->image);
    return $imagePath;
} else {
    $imagePath=asset("/uploads/channellogo/notfound.png");
    return $imagePath;
}

   
}

public function favorited()
{
    return (bool) FavouriteChannel::where('user_id', Auth::id())
                        ->where('channels_id', $this->id)
                        ->first();
}
// public function getGenrenameAttribute($value)
// {
//   return $value = is_array($value) ? $value : json_decode($value) ;
    
// if (is_null($value)) {
//         $value = [];
//     }

//     return $value;
// }


protected static function boot()
    {
        static::creating(function ($model) {
        });
        static::saving(function ($model) {
        });
        static::saved(function ($model) {
            Cache::forget('channelslistsmooth');
            Cache::forget('channelslistapple');
            Cache::forget('channelslistdash');
        });

        static::updating(function ($model) {
           //blah
        });

        static::deleting(function ($model) {
            // bluh bluh
        });
        static::updated(function ($model) {
            // bluh bluh

        });
        
        parent::boot();
    }


    


}