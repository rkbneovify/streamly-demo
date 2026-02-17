<?php

namespace App;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Model;

class GracenoteSource extends Model
{
    protected $table = 'gracenote_sources';

    // Define the fillable attributes
    protected $fillable = [
        'stationName',
        'prgSvcId',
        'callSign',
        'enhancedCallSign',
        'timeZone',
        'url',
        'assets'
    ];

    protected $casts = [
        'assets' => 'array'
    ];

    public $timestamps = true;

    // If you want to define any relationships or additional methods, you can add them here.

    public function channel()
    {
        return $this->hasOne(Channel::class, 'url', 'url');
    }
}
