<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTicketCategory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ApiSupportTicketCategoryController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        protected SupportTicketCategory $supportTicketCategory
    ) {}

    public function index()
    {
        try {
            $data = $this->supportTicketCategory->where('status', 1)
                ->pluck('title');

            return $this->dataResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }
}
