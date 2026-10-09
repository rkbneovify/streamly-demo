<?php

namespace App\Models;

use App\Traits\CommonQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

class CrashReport extends Model
{
    use HasFactory, CommonQueryTrait;

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


    public function scopeSelfSearchFilter($query, ?string $txt)
    {
        if ($txt === null) {
            return $query;
        }

        return  $query->where('screen', 'like', '%' . trim($txt) . '%')
            ->orWhere('api_url', 'like', '%' . trim($txt) . '%')
            ->orWhere('message', 'like', '%' . trim($txt) . '%')
            ->orWhere('exception', 'like', '%' . trim($txt) . '%');
    }
}
