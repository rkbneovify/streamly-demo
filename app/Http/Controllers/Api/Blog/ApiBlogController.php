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
            $query = $this->blog->query();
            $data = $query->where('status', 1)
                ->search($this->request->search)
                ->paginate($limit, ['*'], 'page', $page);
            return $this->paginateResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }

    public function byId($id)
    {
        try {
            $blog = $this->blog->find($id);
            $relatedBlogs = $this->blog->whereNot('id', $id)
                ->where('category', $blog->category)
                ->where('status', 1)->get();
            return $this->dataResponse([
                'blog' => $blog,
                'related' => $relatedBlogs
            ]);
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
                ->with('subCategory')
                ->withCount('blog')
                ->withSearchBlog($this->request->search)
                ->paginate($limit, ['*'], 'page', $page);
            $data->getCollection()->each(function ($item) {
                $item->setRelation(
                    'subCategory',
                    $item->subCategory->take(3)
                );
            });
            return $this->paginateResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
    public function categoriesListNoExtraData()
    {

        try {
            $query = $this->blogCategory->query();
            $data = $query->where('status', 1)->latest('id')->take(6);

            return $this->paginateResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
}
