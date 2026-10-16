<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogSubCategory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogSubCategoryController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        try {
            $data = BlogSubCategory::where('status', 1)->pluck('title');
            return  $this->dataResponse($data, "Data fetch successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
    public function byCategory($category)
    {
        try {
            $data = BlogSubCategory::where('category', $category)->where('status', 1)->pluck('title');
            if ($data == null) {
                return $this->errorResponse("Data not found !", 404);
            }
            return  $this->dataResponse($data, "Data fetch successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }

    public function subcategoryList($category, Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        try {
            $query  = BlogSubCategory::query();
            $query->where('status', 1)->whereHas('blog')
                ->with('blog', function ($query) {
                    $query->where('status', 1);
                })->where('category', $category);

            $data = $query->paginate($limit, ['*'], 'page', $page);
            if (count($data->items()) == 0) {
                return $this->errorResponse("Data not found !", 404);
            }
            return  $this->paginateResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }

    public function store(Request $request, BlogSubCategory $blogCategory)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:225|exits:blog_categories,title'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("validation error", $validator->errors());
        }

        try {
            $blogCategory->title = $request->title;
            $blogCategory->save();
            return $this->successResponse("Data save successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }

    public function show($id, BlogSubCategory $blogCategory)
    {
        try {
            $data = $blogCategory::where('id', $id)->first();
            if ($data == null) {
                return $this->errorResponse("Data not found !", 404);
            }
            return $this->dataResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
}
