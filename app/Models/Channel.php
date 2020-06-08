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
    protected $casts = [
        'genrename' => 'array',
      ];
    /**
    * Mass assignable columns
    */
    protected $fillable=['name',
'url',
'genre_id','status','genres','image'];
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

    public function getLogoAttribute($size=null)
{ 
    $imagePath=asset("/uploads/channellogo/".$this->image);
   return $imagePath;
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