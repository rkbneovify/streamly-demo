<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessSupportTicketFile;
use App\Models\File;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Traits\ApiResponseTrait;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    use ApiResponseTrait, FileUploadTrait;
    public function store(Request $request, SupportTicket $supportTicket,  SupportTicketMessage $supportTicketMessage, File $file)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:225',
            'sub_category' => 'required|string|max:225',
            'subject' => 'required|string|max:225',
            'description' => 'required|string',
            'attachment' => 'nullable',
            'attachment.*' => 'file|mimes:pdf,png,jpg,jpeg,heic,heif,mp4,webm,mov|max:256000', // 500 MB

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
            $supportTicket->status = 'under review'; // Under Review | In Progress | On Hold | Completed | Cancelled
            $supportTicket->priority = 'low';  // High | Medium | Low | Critical
            $supportTicket->save();

            // Support Ticket Message
            $supportTicketMessage->support_ticket_id = $supportTicket->id;
            $supportTicketMessage->sender_id = Auth::id();
            $supportTicketMessage->message = $request->description;
            $supportTicketMessage->save();

            if ($request->hasFile('attachment')) {

                foreach ($request->file('attachment') as $file) {

                    $tempPath = $file->store('support-ticket-temp');

                    ProcessSupportTicketFile::dispatch(
                        $supportTicketMessage->id,
                        $tempPath,
                        $file->getClientOriginalName(),
                        $file->getMimeType()
                    );
                }
            }


            DB::commit();
            return $this->successResponse("Submited successfully !");
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
