<?php

namespace App\Models;

use App\Traits\CommonQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory, CommonQueryTrait;

    protected $table = "announcements";
    protected $fillable = [
        'status'
    ];
}
