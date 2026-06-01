<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    public static function generateBillNum()
    {
        return strtoupper(\Illuminate\Support\Str::random(8));
    }
}
