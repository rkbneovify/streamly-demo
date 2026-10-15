<?php

namespace App\Models;

use App\Traits\CommonQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketCategory extends Model
{
    use HasFactory, CommonQueryTrait;

    protected $table = 'support_ticket_categories';

    protected $fillable = [
        'id',
        'title',
        'status'
    ];
}
