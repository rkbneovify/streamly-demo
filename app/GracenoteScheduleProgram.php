<?php

namespace App;

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
        'date' => 'date',
        'time' => 'time',
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
}
