<?php

namespace App\Http\Controllers\Api;

use App\Enums\ActivityEventType;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessSupportTicketCreatedMail;
use App\Jobs\ProcessSupportTicketFile;
use App\Jobs\ProcessSupportTicketMail;
use App\Jobs\ProcessSupportTicketReplyMail;
use App\Models\File;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Services\RecaptchaService;
use App\Traits\ActivityLogger;
use App\Traits\ApiResponseTrait;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    use ApiResponseTrait, FileUploadTrait, ActivityLogger;

    public function __construct(
        public SupportTicket $supportTicket,
        public SupportTicketMessage $supportTicketMessage,
        public File $file
    ) {}


    public function index(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $query  =  $this->supportTicket->query();
            // $query->with('messages.files.thambnail');
            $query->where('user_id', Auth::id());
            $query->search($request->txt)
                ->priority($request->priority)
                ->status($request->status)
                ->filterDateOrder($request->date)
                ->latest();


            $data =  $query->paginate($limit, ['*'], 'page', $page);

            return $this->paginateResponse($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }

    public function store(Request $request, SupportTicket $supportTicket,  SupportTicketMessage $supportTicketMessage, File $file, RecaptchaService $recaptcha)
    {
        $validator = Validator::make($request->all(), [
            'captcha_token' => 'nullable|string',
            'category' => 'required|string|max:225',
            'sub_category' => 'required|string|max:225',
            'platform' => 'required|string|max:225',
            'subject' => 'required|string|max:225',
            'description' => 'required|string',
            'attachment' => 'nullable',
            'attachment.*' => 'file|mimes:pdf,png,jpg,jpeg,heic,heif,mp4,webm,mov|max:256000', // 500 MB

        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());
        }

        if (! $recaptcha->verify($request->captcha_token, $request->ip())) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid captcha !'
            ], 422);
        }



        DB::beginTransaction();
        try {
            $supportTicket->user_id = Auth::id();
            $supportTicket->category = $request->category;
            $supportTicket->sub_category = $request->sub_category;
            $supportTicket->platform = $request->platform;
            $supportTicket->device_model = $request->device_model;
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

                // Remove old files
                $this->clearSupportTicketTemp();
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
                'platform' => $supportTicket->platform,
                'device_model' => $supportTicket->device_model,
                'subject' => $supportTicket->subject,
                'description' => $supportTicketMessage->message
            ];

            $attachments = [];
            foreach ($files as $file) {
                $attachments[] =  storage_path('app/' . $file['temp_path']);
            }

            // return response()->json($attachments);
            ProcessSupportTicketMail::dispatch($supportTicket, Auth::user()->email, $data, $attachments);
            // Support ticket created mail to user
            ProcessSupportTicketCreatedMail::dispatch($supportTicket, Auth::user());

            $this->activity()->event("Support Ticket")
                ->eventType(ActivityEventType::CREATED)
                ->description("Support Ticket - Created by user")
                ->isAdmin()
                ->properties([
                    "data" => $supportTicket

                ])->save();

            DB::commit();
            return $this->successResponse("Submited successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }

    public function ticketReply(Request $request)
    {

        // return response()->json($request->user()->roles[0]->name);
        $validator = Validator::make($request->all(), [
            'ticket_number' => 'required|string',
            'message' => 'required|string',
            'attachment' => 'nullable',
            'attachment.*' => 'file|mimes:pdf,png,jpg,jpeg,heic,heif,mp4,webm,mov|max:256000', // 500 MB

        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());
        }
        DB::beginTransaction();
        try {
            $supportTicket =   $this->supportTicket->where('ticket_number', $request->ticket_number)->first();

            if ($supportTicket == null) {
                return $this->notFoundResponse('Data not found !');
            }
            // Support Ticket Message
            $this->supportTicketMessage->support_ticket_id = $supportTicket->id;
            $this->supportTicketMessage->sender_id = Auth::id();
            $this->supportTicketMessage->message = $request->message;
            $this->supportTicketMessage->save();

            $files = [];
            if ($request->hasFile('attachment')) {

                // Remove old files
                $this->clearSupportTicketTemp();
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
                ProcessSupportTicketFile::dispatch($this->supportTicketMessage->id, $files);
            }

            $data = [

                'ticket_no' => $supportTicket->ticket_number,
                'subject' => $supportTicket->subject,
                'description' => $this->supportTicketMessage->message
            ];

            $attachments = [];
            foreach ($files as $file) {
                $attachments[] =  storage_path('app/' . $file['temp_path']);
            }

            // return response()->json($attachments);
            ProcessSupportTicketReplyMail::dispatch($supportTicket, Auth::user()->email, $request->user()->roles[0]->name, $data, $attachments);


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

    private function clearSupportTicketTemp()
    {
        // if file older file remove
        $disk = Storage::disk('local');
        $directory = 'support-ticket-temp';

        foreach ($disk->allFiles($directory) as $file) {
            $lastModified = Carbon::createFromTimestamp($disk->lastModified($file));

            if ($lastModified->lt(now()->subDays(2))) {
                $disk->delete($file);
            }
        }
    }
}
