<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channel';
    public $timestamps = false;
    protected $fillable = [
        'rating', 'stream_url', 'status',
        'language_id', 'genre_id', 'title',
        'description', 'release_year', 'length',
        
    ];
}
