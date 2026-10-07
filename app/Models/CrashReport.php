<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrashReport extends Model
{
    use HasFactory;

    protected $table = 'crash_report';

    protected $fillable = [
        'id',
        'user_id',
        'session_id',
        'build_version',
        'platform',
        'os_version',
        'device_model',
        'screen',
        'api_url',
        'network_type',
        'exception',
        'message',
        'stack_trace',
        'created_at',
        'updated_at'
    ];
}
