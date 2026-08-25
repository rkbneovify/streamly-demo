<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Mail\BugReportMail;
use App\Models\BugReporting;
use App\Models\File;
use App\Traits\ApiResponseTrait;
use App\Traits\FileUploadTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BugReportController extends Controller
{
    use ApiResponseTrait, FileUploadTrait;
    public function __construct(
        public Request $request,
        public BugReporting $bugReporting,
        public File $file
    ) {

    }

    public function submitBugReport()
    {
        $validator = Validator::make($this->request->all(), [
            'captcha_token' => 'nullable|string',
            'email' => 'required|email|max:225',
            'name' => 'required|string|max:225',
            'bug_title' => 'required|string|max:225',
            'description' => 'required|string|max:2000',
            'platform' => 'required|string|max:225',
            'priority' => 'required|string|max:225',
            'file' => 'nullable',
            'file.*' => 'file|mimes:png,jpg,jpeg,heic,heif|max:10240', // 10 MB

        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->first(), $validator->errors());
        }
        DB::beginTransaction();
        try {
            $report = $this->bugReporting;
            $report->email = $this->request->email;
            $report->name = $this->request->name;
            $report->bug_title = $this->request->bug_title;
            $report->description = $this->request->description;
            $report->platform = $this->request->platform;
            $report->priority = $this->request->priority;
            $report->save();


            if ($this->request->hasFile('file')) {
                $files = collect($this->request->file('file'))->map(function ($file) use ($report) {
                    $uploadedFile = $this->uploadFile($file, "uploads/bug-report");
                    $newFile = $this->file;
                    $newFile->bug_report_id = $report->id;
                    $newFile->disk = $uploadedFile['disk'];
                    $newFile->file_name = $uploadedFile['file_name'];
                    $newFile->path = $uploadedFile['path'];
                    $newFile->url = $uploadedFile['url'];
                    $newFile->mime_type = $uploadedFile['mime_type'];
                    $newFile->extension = $uploadedFile['extension'];
                    $newFile->size = $uploadedFile['size'];
                    $newFile->save();

                    return [
                        "path" => $uploadedFile['path'],
                        "disk" => $uploadedFile['disk']

                    ];

                })->toArray();


            }

            $attachments = [];
            foreach ($files as $file) {
                $attachments[] = [
                    "path" => $file['path'],
                    "disk" => $file['disk']
                ];

            }

            $bugData = [
                'email' => $this->request->email,
                'name' => $this->request->name,
                'bug_title' => $this->request->bug_title,
                'description' => $this->request->description,
                'priority' => $this->request->priority,
                'platform' => $this->request->platform,
            ];
            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new BugReportMail($bugData, $attachments));
            DB::commit();
            return $this->successResponse("Submitted Successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }
}
