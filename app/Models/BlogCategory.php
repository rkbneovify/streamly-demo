<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $table = "blog_categories";

    protected $fillable = [
        'status'
    ];
    protected $cast = [
        'status' => 'boolean'
    ];

    public function subCategories()
    {
        return $this->hasMany(BlogSubCategory::class, 'category', 'title');
    }

    public function scopeSearch($query, $txt)
    {
        if ($txt != null) {
            $query->where('title', 'like', '%' . $txt . '%');
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
