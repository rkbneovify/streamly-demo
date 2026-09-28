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


    public function blog()
    {
        return $this->hasMany(Blog::class, 'category', 'title');
    }
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category', 'title');
    }

    public function subCategory()
    {
        return $this->hasMany(BlogSubCategory::class, 'category', 'title')
            ->where('status', 1);
    }

    public function scopeWithSearchBlog($query, $txt)
    {
        if ($txt != "") {
            return $query->where(function ($query) use ($txt) {
                $query->where('title', 'like', "%{$txt}%")
                    ->orWhereHas('blog', function ($query) use ($txt) {
                        $query->where('title', 'like', "%{$txt}%")
                            ->orWhere('sub_category', 'like', "%{$txt}%");
                    });
            })->withWhereHas('blog', function ($query) use ($txt) {
                $query->where('title', 'like', "%{$txt}%")
                    ->orWhere('sub_category', 'like', "%{$txt}%");
            });
        }
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
            return $query->latest();
        }


        return $query->orderBy($column, $date);
    }
}
