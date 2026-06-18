<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        try {
            $data = BlogCategory::where('status', 1)->pluck('title');
            return  $this->dataResponse(compact('data'), "Data fetch successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
    public function store(Request $request, BlogCategory $blogCategory)
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

    public function show($id, BlogCategory $blogCategory)
    {
        try {
            $data = $blogCategory::where('id', $id)->first();
            if ($data == null) {
                return $this->errorResponse("Data not found !", 404);
            }
            return $this->dataResponse(compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
}
