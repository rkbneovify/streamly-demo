<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AdminSupportTicketController extends Controller
{
    public function __construct(public SupportTicket $supportTicket) {}
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        $query  = $this->supportTicket->query();
        $query->search($request->search)
            ->status($request->status)
            ->priority($request->priority);
        $data = $query->with(['user', 'message'])->paginate($limit, ['*'], $page);

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
            $ticket =  $this->supportTicket->where('id', $id)->first();
            $ticket->status = $status;
            $ticket->save();
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
}
