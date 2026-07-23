<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessSupportTicketFile;
use App\Jobs\ProcessSupportTicketMail;
use App\Models\File;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Traits\ApiResponseTrait;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    use ApiResponseTrait, FileUploadTrait;

    public function __construct(public SupportTicket $supportTicket) {}


    public function index(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $query  =  $this->supportTicket->query();
            $query->with('message.files.thambnail');
            $query->where('user_id', Auth::id());
            $data =  $query->paginate($limit, ['*'], 'page', $page);

            return $this->paginateResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }

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
            $supportTicket->status = 'under review'; // Under Review | In Progress | On Hold | Completed | Cancelled | Closed
            $supportTicket->priority = 'low';  // High | Medium | Low | Critical
            $supportTicket->save();

            // Support Ticket Message
            $supportTicketMessage->support_ticket_id = $supportTicket->id;
            $supportTicketMessage->sender_id = Auth::id();
            $supportTicketMessage->message = $request->description;
            $supportTicketMessage->save();

            $files = [];
            if ($request->hasFile('attachment')) {

                //  Clear all files from support-ticket-temp
                Storage::disk('local')->deleteDirectory('support-ticket-temp');

                // Recreate the directory
                Storage::disk('local')->makeDirectory('support-ticket-temp');
                $files = collect($request->file('attachment'))->map(function ($file) {

                    $tempPath = $file->store('support-ticket-temp', 'local');

                    return [
                        'temp_path'     => $tempPath,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type'     => $file->getMimeType(),
                        'size'          => $file->getSize(),
                        'extension'     => $file->getClientOriginalExtension(),
                    ];
                })->toArray();


                // return response()->json($files);
                ProcessSupportTicketFile::dispatch($supportTicketMessage->id, $files);
            }

            $data = [

                'ticket_no' => $supportTicket->ticket_number,
                'subject' => $supportTicket->subject,
                'description' => $supportTicketMessage->message
            ];

            $attachments = [];
            foreach ($files as $file) {
                $attachments[] =  storage_path('app/' . $file['temp_path']);
            }

            // return response()->json($attachments);
            ProcessSupportTicketMail::dispatch($supportTicket, Auth::user()->email, $data, $attachments);


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
