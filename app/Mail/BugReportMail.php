<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BugReportMail extends Mailable
{
    use Queueable, SerializesModels;



    public function __construct(public array $bugData = [], public array $files = [])
    {

    }

    public function build()
    {
        $mail = $this
            ->subject(env('APP_NAME').  ' - New Bug Report')
            ->view('emails.bug-report.report');

        foreach ($this->files as $file) {
            $mail->attach(
                Storage::disk($file['disk'])->path($file['path'])
            );
        }


        return $mail;
    }
}
