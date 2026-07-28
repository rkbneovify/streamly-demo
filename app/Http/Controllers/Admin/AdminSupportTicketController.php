<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class AdminSupportTicketController extends Controller
{
    public function index(Request $request)
    {

        $data =   SupportTicket::with('message')->get();
        if ($request->ajax()) {
        }
        return view('admin.support-ticket.index', compact('data'));
    }
}
