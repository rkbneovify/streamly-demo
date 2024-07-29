<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelSlider extends Model
{
    protected $table = "channel_slider";
    protected $fillable = ['channel_id', 'description', 'status', 'image', 'optimised_image', 'background_image']; // Define fillable properties

    // Define relationship with Channel model assuming it exists
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
