<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = "blogs";

    protected $fillable = [
        'status'
    ];
    protected $cast = [
        'status' => 'boolean'
    ];
    public function scopeSearch($query, $txt)
    {
        if ($txt != null) {
            $query->where('title', 'like', '%' . $txt . '%')
                ->orWhere('category', 'like', '%' . $txt . '%')
                ->orWhere('sub_category', 'like', '%' . $txt . '%');
        }
    }
    public function scopeStatus($query, $status)
    {
        if ($status !== null) {
            $query->where('status', $status);
        }
    }
    public function scopeFilterDateOrder($query, ?string $date, string $column = 'created_at')
    {
        if (!$date) {
            return $query;
        }


        return $query->orderBy($column, $date);
    }
}

