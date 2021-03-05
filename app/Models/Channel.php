<?php
namespace App\Models;
use App\Models\FavouriteChannel;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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





    


}