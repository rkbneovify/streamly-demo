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
        public $tempPath,
        public $originalName,
        public $mimeType,

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

        // if (request()->hasFile('attachment')) {
        $uploadPath = 'uploads/images';
        if (str_starts_with($this->mimeType, 'video/')) {
            $uploadPath = 'uploads/videos';
        }
        $uploadedFile = $this->uploadTempFile(
            $this->tempPath,
            $this->originalName,
            $this->mimeType,
            $uploadPath,
            's3'
        );

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
