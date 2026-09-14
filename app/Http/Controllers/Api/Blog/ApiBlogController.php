<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ApiBlogController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        public Request $request,
        public BlogCategory $blogCategory,
        public Blog $blog

    ) {}


    public function index()
    {
        $limit = $this->request->input('limit', 10);
        $page = $this->request->input('page', 1);

        try {
            $query = $this->blogCategory->query();
            $data = $query->where('status', 1)
                ->with('subCategory')
                ->withCount('blogs')

                ->paginate($limit, ['*'], 'page', $page);
            return $this->paginateResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
    public function categoriesList()
    {
        $limit = $this->request->input('limit', 10);
        $page = $this->request->input('page', 1);

        try {
            $query = $this->blogCategory->query();
            $data = $query->where('status', 1)
                ->with('subCategory', function ($query) {
                    return  $query->pluck('title');
                })
                ->withCount('blog')
                ->withSearchBlog($this->request->search)
                ->paginate($limit, ['*'], 'page', $page);
            //   $data->getCollection()->each->makeHidden('blog');
            return $this->paginateResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
}
