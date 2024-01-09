<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\FaqChatboat;
use DB;
use Illuminate\Http\Request;


class FaqChatboatController extends Controller
{

    public function index()
    {    

        $chatboat_faq = DB::table('faqs')
            ->join('chatboat_category', 'chatboat_category.id', '=', 'faqs.cat_id')
            ->select('faqs.*', 'chatboat_category.name')
            ->orderBy('faqs.id', 'DESC')
            ->get();

        return view('admin.chatboat_faq.index',['chatboat_faq' => $chatboat_faq]);
    }
    public function create()
    {
        $chatboat_faq = DB::table('faqs')->get();
        $chatboat_category = DB::table('chatboat_category')->get();
        return view('admin.chatboat_faq.create',['chatboat_faq' => $chatboat_faq,'chatboat_category' => $chatboat_category]);
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'question_key' => 'required|unique:faqs',
            'question_answer' => 'required|unique:faqs',
            'faq_category' => 'required',
            
        ]);
        $faqChatboat = new FaqChatboat;
        $faqChatboat->question_key = $request->question_key;
        $faqChatboat->question_answer = $request->question_answer;
        $faqChatboat->cat_id = $request->faq_category;
        $faqChatboat->save();
        return redirect('/admin/faq')
        ->with('success','Faq has been created successfully.');
    }

    public function view(Request $request)
    {
       
         $chatboat_faq = DB::table('faqs')
            ->join('chatboat_category', 'chatboat_category.id', '=', 'faqs.cat_id')
            ->select('faqs.*', 'chatboat_category.name')
            ->get();

        $chatboat_category = DB::table('chatboat_category')->get();

        return view('admin.chatboat_faq.view',['chatboat_faq'=>$chatboat_faq,'chatboat_category' => $chatboat_category]);
    }
   
    public function edit(Request $request)
    {
          $chatboat_faq = DB::table('faqs')
            ->join('chatboat_category', 'chatboat_category.id', '=', 'faqs.cat_id')
            ->where('faqs.id', '=' ,$request->id)
            ->select('faqs.*', 'chatboat_category.name')
            ->get();

        $chatboat_category = DB::table('chatboat_category')->get();

        return view('admin.chatboat_faq.edit',['chatboat_faq'=>$chatboat_faq,'chatboat_category' => $chatboat_category]);
    }
     
    public function update(Request $request, $id)
    {
        $request->validate([
            'question_key' => 'required',
            'question_answer' => 'required',
            'faq_category' => 'required',
        ]);
        $faqChatboat = FaqChatboat::find($id);
        $faqChatboat->question_key = $request->question_key;
        $faqChatboat->question_answer = $request->question_answer;
        $faqChatboat->cat_id = $request->faq_category;
        $faqChatboat->save();
        return redirect('/admin/faq')
        ->with('success','Faq has been updated successfully');
    }
   
    public function destroy($id)
    {
       
        FaqChatboat::destroy(['id',$id]);
        return redirect('/admin/faq')
        ->with('success','Faq has been deleted successfully');
    }
}
