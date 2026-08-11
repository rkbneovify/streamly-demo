<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class AdminBlogCategoryController extends Controller
{
    public function __construct(public Request $request, public BlogCategory $blogCategory) {}

    public function index()
    {
        try {
            $limit = $this->request->input('limit', 10);
            $page = $this->request->input('page', 1);
            $query = $this->blogCategory->query();
            $data = $query->paginate($limit, ['*'], 'page', $page);
            return response()->json($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store()
    {
        try {
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
