<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogSubCategory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AdminBlogController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        public Request $request,
        public Blog $blog,
        public BlogCategory $blogCategory,
        public BlogSubCategory $blogSubCategory
    ) {

    }
    public function index()
    {

    }

    public function subCategoriesByCategory($category)
    {
        try {
            $subcategories = $this->blogSubCategory->where('category', $category)
                ->where('status', 1)->pluck('title');
            return $this->dataResponse([
                "data" => $subcategories,
               
            ], "Data fetched successfully !");
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function create()
    {
        try {
            $categories = $this->blogCategory->where('status', 1)->pluck('title');
            return view('admin.blog.create', compact('categories'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function store()
    {
        

    }
}
