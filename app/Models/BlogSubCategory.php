<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogSubCategory extends Model
{
    protected $table = "blog_sub_categories";

    protected $cast = [
        'status' => 'boolean'
    ];
}
