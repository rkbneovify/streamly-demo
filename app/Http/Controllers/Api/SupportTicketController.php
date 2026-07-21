<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Traits\ApiResponseTrait;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    use ApiResponseTrait, FileUploadTrait;
    public function store(Request $request, SupportTicket $supportTicket)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:225',
            'sub_category' => 'required|string|max:225',
            'subject' => 'required|string|max:225',
            'description' => 'required|string',
            'attachment' => 'nullable',
            'attachment.*' => 'file|mimes:pdf,png,jpg,jpeg,heic,heif,mp4,webm,mov|max:512000', // 500 MB

        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());
        }
        DB::beginTransaction();
        try {
            $supportTicket->user_id = Auth::id();
            $supportTicket->category = $request->category;
            $supportTicket->sub_category = $request->sub_category;
            $supportTicket->subject = $request->subject;
            // $supportTicket->description = $request->description;
            $supportTicket->status = 'under review'; // Under Review | In Progress | On Hold | Completed | Cancelled
            $supportTicket->priority = 'low';  // High | Medium | Low | Critical
            $supportTicket->save();
            DB::commit();
            return $this->successResponse("Data submited successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'attachment' => 'required|file|max:512000'
        ]);

        $file = $this->uploadFile(
            $request->file('attachment'),
            'uploads/videos',   // folder
            's3'     // public or 's3'
        );

        return response()->json($file);
    }
}
