<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $language_id language id
@property int $genre_id genre id
@property varchar $title title
@property varchar $description description
@property int $release_year release year
@property int $length length
@property int $rating rating
@property varchar $stream_url stream url
@property int $status status
@property datetime $created_on created on
@property datetime $modified_on modified on
@property Genre $genre belongsTo
@property Language $language belongsTo
   
 */
class Channel extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'channel';
    public $timestamps = false;

    /**
    * Mass assignable columns
    */
    protected $fillable=[
'language_id',
'genre_id',
'title',
'description',
'release_year',
'length',
'rating',
'stream_url',
'status',
'created_on',
'modified_on'];

    /**
    * Date time columns.
    */

    /**
    * genre
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function genre()
    {
        return $this->belongsTo(Genre::class,'genre_id');
    }

    /**
    * language
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function language()
    {
        return $this->belongsTo(Language::class,'language_id');
    }




}