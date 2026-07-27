<?php

namespace App\Jobs;

use App\Mail\SupportTicketReplyMail;
use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessSupportTicketReplyMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public SupportTicket $supportTicket,
        public string $email,
        public string $role,
        public array $data,
        public array $attachments = [],
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

        if (in_array($this->role, ['admin', 'superadmin'])) {
            Mail::to($this->email)->send(new SupportTicketReplyMail($this->supportTicket, $this->data, $this->attachments));
        } else {
            Mail::to(env('MAIL_USERNAME'))->send(new SupportTicketReplyMail($this->supportTicket, $this->data, $this->attachments));
        }
    }
}
