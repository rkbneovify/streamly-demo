<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessSupportTicketFile;
use App\Jobs\ProcessSupportTicketReplyMail;
use App\Models\File;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Traits\ActivityLogger;
use App\Traits\ApiResponseTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AdminSupportTicketController extends Controller
{
    use ApiResponseTrait, ActivityLogger;
    public function __construct(
        public Request $request,
        public SupportTicket $supportTicket,
        public SupportTicketMessage $supportTicketMessage,
        public File $file
    ) {}
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        $query = $this->supportTicket->query();
        $query->search($request->search)
            ->status($request->status)
            ->priority($request->priority)
            ->dateRange($request->daterange)
            ->filterDateOrder($request->date)
            ->latest();

        $data = $query->with(['user', 'messages'])->paginate($limit, ['*'], 'page', $page);

        if ($request->ajax()) {
            return view('admin.support-ticket.table', compact('data'));
        }
        return view('admin.support-ticket.index', compact('data'));
    }

    public function priorityUpdate($priority, $id)
    {
        DB::beginTransaction();
        try {
            $ticket = $this->supportTicket->where('id', $id)->first();
            $ticket->priority = $priority;
            $ticket->save();


            $this->activity()
                ->event("Support Ticket")
                ->isAdmin()
                ->eventType(ucfirst($priority))
                ->description("Support Ticket - Priority update")
                ->properties([
                    "data" => $ticket
                ])->save();

            DB::commit();
            toast('Data updated successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error('error', 'Something went wrong ! try again later.')
                ->timerProgressBar()
                ->autoClose(3000);
            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function statusUpdate($status, $id)
    {
        DB::beginTransaction();
        try {
            $ticket = $this->supportTicket->where('id', $id)->first();
            $ticket->status = $status;
            $ticket->save();

            $this->activity()
                ->event("Support Ticket")
                ->isAdmin()
                ->eventType(ucwords($status))
                ->description("Support Ticket - Status update")
                ->properties([
                    "data" => $ticket
                ])->save();

            DB::commit();
            toast('Data updated successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            // Alert::error('error', 'Something went wrong ! try again later.')
            //     ->timerProgressBar()
            //     ->autoClose(3000);
            Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function chat($ticket)
    {


        $query = $this->supportTicket->query();
        $query->where('ticket_number', $ticket);
        $data = $query->with(['messages.user.roles', 'messages.files'])->first();
        // return response()->json($data);

        return view('admin.support-ticket.chat.index', compact('data'));
    }

    public function ticketReply($ticket, Request $request)
    {

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable',
            'attachment.*' => 'file|mimes:pdf,png,jpg,jpeg,heic,heif,mp4,webm,mov|max:256000', // 500 MB

        ]);

        DB::beginTransaction();
        try {
            $supportTicket = $this->supportTicket->where('ticket_number', $ticket)->first();


            if ($supportTicket == null) {
                toast('Ticket not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
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
                        'temp_path' => $tempPath,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'extension' => $file->getClientOriginalExtension(),
                    ];
                })->toArray();


                // return response()->json($files);
                ProcessSupportTicketFile::dispatch($this->supportTicketMessage->id, $files);
            }

            $user = User::where('id', $supportTicket->user_id)->first();
            if ($user == null) {
                toast('user not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $data = [
                'user_name' => $user->name,
                'ticket_no' => $supportTicket->ticket_number,
                'subject' => $supportTicket->subject,
                'description' => $this->supportTicketMessage->message,
                // 'url' => url('/admin/support-ticket/chat/' . $supportTicket->ticket_number)
            ];

            $attachments = [];
            foreach ($files as $file) {
                $attachments[] = storage_path('app/' . $file['temp_path']);
            }


            // return response()->json($attachments);
            ProcessSupportTicketReplyMail::dispatch($supportTicket, $user->email, $request->user()->roles[0]->name, $data, $attachments);

            $this->activity()
                ->event("Support Ticket")
                ->isAdmin()
                ->eventType('Reply')
                ->description("Support Ticket - Reply to user")
                ->properties([
                    "data" => $this->supportTicket
                        ->where('ticket_number', $ticket)
                        ->with(['messages.user.roles', 'messages.files'])->first()
                ])->save();

            $this->activity($supportTicket->user_id)
                ->event("Support Ticket")
                ->isUser()
                ->notification()
                ->eventType('Reply')
                ->descriptionSimple("Support Ticket - Reply from support team")
                ->save();
            DB::commit();

            toast('Reply submited successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            sleep(10);
            return redirect()->back();

            // return $this->successResponse("Submited successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            // toast('Something went wrong ! try again later.', 'error')
            //     ->timerProgressBar()
            //     ->autoClose(3000);
            Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function bulkAction()
    {
        $priorities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['under review', 'on hold', 'completed', 'in progress', 'closed', 'cancelled'];
        $message = '';



        DB::beginTransaction();
        try {
            if (in_array($this->request->action, $priorities)) {
                $this->supportTicket->whereIn('id', $this->request->tickets)
                    ->update([
                        'priority' => $this->request->action
                    ]);

                $this->activity()
                    ->event("Support Ticket")
                    ->isAdmin()
                    ->eventType(ucfirst($this->request->action))
                    ->description("Multiple Support Ticket - Priority update")
                    ->properties([
                        "data" => $this->supportTicket->whereIn('id', $this->request->tickets)->get()
                    ])->save();
                DB::commit();
                toast('Priority updated successfully !', 'success')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            } elseif (in_array($this->request->action, $statuses)) {
                $this->supportTicket->whereIn('id', $this->request->tickets)
                    ->update([
                        'status' => $this->request->action
                    ]);

                $this->activity()
                    ->event("Support Ticket")
                    ->isAdmin()
                    ->eventType(ucfirst($this->request->action))
                    ->description("Multiple Support Ticket - Status update")
                    ->properties([
                        "data" => $this->supportTicket->whereIn('id', $this->request->tickets)->get()
                    ])->save();

                DB::commit();
                toast('Status updated successfully !', 'success')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            toast('Something went wrong ! try again later.', 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        }
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
