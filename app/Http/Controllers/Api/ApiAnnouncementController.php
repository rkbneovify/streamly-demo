<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ApiAnnouncementController extends Controller
{
    use ApiResponseTrait;
    public function __construct(
        public Announcement $announcement
    ) {}

    public function index()
    {
        try {

            $data =   $this->announcement->where('status', 1)->first();
           return $this->dataResponse([$data]);
        } catch (\Throwable $th) {
            //throw $th;
           return $this->exceptionResponse($th);
        }
    }
}
