<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\FaqChatboat;
use DB;
use Illuminate\Http\Request;


class ChatHistoryController extends Controller
{

     public function index()
    {    
        
        $arr_history = DB::table('chat_history')
                      ->orderBy('id', 'DESC')
                      ->get();
        
        //echo "<pre>";print_r($arr_history); exit;
        return view('admin.chat_history.index',['chat_history' => $arr_history]);
    }
}
