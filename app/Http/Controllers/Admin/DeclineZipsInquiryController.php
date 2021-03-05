<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DeclineZipInquiry;

use App\Models\Zipcode;
use Yajra\DataTables\DataTables;
class DeclineZipsInquiryController extends Controller
{ 
    
    public function index(Request $request)
    { if ($request->ajax()) {
        return DataTables::of(DeclineZipInquiry::all())
        ->addIndexColumn()
        ->addColumn('action', function($row){

               $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);}
        
    return view('admin.decline_zip_inquiries.index');
} 

public function ajaxIndex(Request $request)
    { 
        return DataTables::of(DeclineZipInquiry::all())
        ->addIndexColumn()
        // ->addColumn('action', function($row){

        //        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

        //         return $btn;
        // })
        // ->rawColumns(['action'])
        ->make(true);
    }

    public function failedzipinquery(Request $request)
    {   
        $validator = \Validator::make($request->all(), [
          
			'email' => 'nullable|max:255',
			'zip' => 'required|max:255',
			'city' => 'required|max:255',
			'state' => 'required|max:255',
			// 'modified_at' => 'required|date',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $declinezipInquiry = DeclineZipInquiry::updateOrCreate(
            ['email' =>  request('email'),'zip' => request('zip'),'city' =>  request('city'),'state' => request('state')],
            [ 'frequency' => \DB::raw('frequency + 1')]
        );
        return response()->json(['success'=>'Data is successfully added']);
    }

       /*
   AJAX request
   */
   public function getZipcodes(Request $request){

    $search = $request->search;

    if($search == ''){
       $Zipcodes = Zipcode::orderby('zipcode','asc')->select('zipcode as id','city','state')->limit(5)->get();
    }else{
       $Zipcodes = Zipcode::orderby('zipcode','asc')->select('zipcode as id','city','state')
    //    ->whereRaw('field_name COLLATE utf8mb4_bin = (?)', $search)
       ->where('zipcode', 'like', '%' .$search . '%')
    //    ->where('zipcode', 'REGEXP', '%' .$search . '%')
       ->limit(5)->get();
    }

    $response = array();
    foreach($Zipcodes as $Zipcode){
       $response[] = array("value"=>$Zipcode->id,"label"=>$Zipcode->id,"state"=>$Zipcode->state,"city"=>$Zipcode->city);
    }

    return response()->json($response);
 }
 
        
}

