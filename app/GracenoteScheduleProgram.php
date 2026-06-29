<?php

namespace App;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Model;

class GracenoteScheduleProgram extends Model
{
    protected $table = 'gracenote_schedule_program';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prgSvcId',
        'date',
        'TMSId',
        'rootId',
        'time',
        'duration',
        'start',
        'end',
        'quals',
        'title',
        'description',
        'assets',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'time' => 'datetime:H:i:s',
        'start' => 'integer',
        'end' => 'integer',
        'assets' => 'array',
    ];

    /**
     * Get the source associated with the program.
     */
    public function source()
    {
        return $this->belongsTo(GracenoteSource::class, 'prgSvcId', 'prgSvcId');
    }
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'prgSvcId', 'prgSvcId');
    }
}
