<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Thumbnail;
use App\Traits\SupportTiketFileUploadTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSupportTicketFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SupportTiketFileUploadTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct(
        public int $supportTicketMessageId,
        public array $files,
        public string $disk = 'public'



    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info(response()->json($this->files));



        foreach ($this->files as $file) {

            $uploadPath = str_starts_with($file['mime_type'], 'video/')
                ? 'uploads/videos'
                : 'uploads/images';

            $uploadedFile = $this->uploadTempFile(
                $file['temp_path'],      // instead of temp_path
                $file['original_name'],      // instead of original_name
                $file['mime_type'],
                $uploadPath,
                $this->disk
            );

            if (!$uploadedFile['success']) {
                throw new \Exception($uploadedFile['message']);
            }

            if (str_starts_with($uploadedFile['mime_type'], 'video/')) {

                $file = new File();
                $file->support_ticket_message_id = $this->supportTicketMessageId;
                $file->disk = $uploadedFile['disk'];
                $file->file_name = $uploadedFile['file_name'];
                $file->path = $uploadedFile['path'];
                $file->url = $uploadedFile['url'];
                $file->mime_type = $uploadedFile['mime_type'];
                $file->extension = $uploadedFile['extension'];
                $file->size = $uploadedFile['size'];
                $file->save();

                $thumbnail = new Thumbnail();
                $thumbnail->video_id = $file->id;
                $thumbnail->disk = $uploadedFile['disk'];
                $thumbnail->file_name = $uploadedFile['thumbnail']['file_name'];
                $thumbnail->path = $uploadedFile['thumbnail']['path'];
                $thumbnail->url = $uploadedFile['thumbnail']['url'];
                $thumbnail->mime_type = $uploadedFile['thumbnail']['mime_type'];
                $thumbnail->extension = $uploadedFile['thumbnail']['extension'];
                $thumbnail->size = $uploadedFile['thumbnail']['size'];
                $thumbnail->save();
            } else {

                $file = new File();
                $file->support_ticket_message_id = $this->supportTicketMessageId;
                $file->disk = $uploadedFile['disk'];
                $file->file_name = $uploadedFile['file_name'];
                $file->path = $uploadedFile['path'];
                $file->url = $uploadedFile['url'];
                $file->mime_type = $uploadedFile['mime_type'];
                $file->extension = $uploadedFile['extension'];
                $file->size = $uploadedFile['size'];
                $file->save();
            }
        }
    }
}
