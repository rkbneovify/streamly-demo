<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ChatboatCategory;
use DB;
use Illuminate\Http\Request;


class ChatboatCategoryController extends Controller
{

    public function index()
    {    //die('123');
        $chatboat_category = DB::table('chatboat_category')->get();

        return view('admin.chatboat_category.index',['chatboat_category' => $chatboat_category]);
    }
    public function create()
    {
        return view('admin.chatboat_category.create');
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:chatboat_category'
            
        ]);
        $chatboat_category = new ChatboatCategory;
        $chatboat_category->name = $request->name;
        $chatboat_category->create_date = strtotime(now());
        $chatboat_category->save();
        return redirect('/admin/chatboat_category')
        ->with('success','Category has been created successfully.');
    }

    public function view(Request $request)
    {
        $chatboat_category = DB::select('select * from chatboat_category where id ='.$request->id);
        return view('admin.chatboat_category.view',['chatboat_category'=>$chatboat_category]);
    }
   
    public function edit(Request $request)
    {
        $chatboat_category = DB::select('select * from chatboat_category where id ='.$request->id);
        return view('admin.chatboat_category.edit',['chatboat_category'=>$chatboat_category]);
    }
     
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $chatboat_category = ChatboatCategory::find($id);
        $chatboat_category->name = $request->name;
        $chatboat_category->save();
        return redirect('/admin/chatboat_category')
        ->with('success','Category has been updated successfully');
    }
   
    public function destroy($id)
    {
       
        ChatboatCategory::destroy(['id',$id]);
        return redirect('/admin/chatboat_category')
        ->with('success','Category has been deleted successfully');
    }
}
