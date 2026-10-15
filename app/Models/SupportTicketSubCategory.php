<?php

namespace App\Models;

use App\Traits\CommonQueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketSubCategory extends Model
{
    use HasFactory, CommonQueryTrait;

    protected $table = 'support_ticket_sub_categories';

    protected $fillable = [
        'id',
        'category',
        'title',
        'status'
    ];
}
