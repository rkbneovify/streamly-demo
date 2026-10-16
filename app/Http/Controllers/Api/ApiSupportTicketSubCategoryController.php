<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicketSubCategory;
use App\Traits\ApiResponseTrait;

class ApiSupportTicketSubCategoryController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        protected SupportTicketSubCategory $supportTicketSubCategory
    ) {}

    public function index($category)
    {
        try {
            $data =  $this->supportTicketSubCategory->where('status', 1)
                ->where('category', $category)->pluck('title');

            if ($data == null || count($data) == 0) {
                return  $this->notFoundResponse("Data not found !");
            }

            return $this->dataResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
}
