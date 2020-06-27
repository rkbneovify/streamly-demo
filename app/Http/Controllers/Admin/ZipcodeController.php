<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Zipcode;


use Yajra\DataTables\DataTables;




class ZipcodeController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $zipcodes = Zipcode::paginate(10);
       
       return view('admin.zipcodes.index', compact('zipcodes'));
    }  
    public function allowedZipcodes()
    { 
        $zipcodes = Zipcode::paginate(10);
       
       return view('admin.zipcodes.allowedzipcode', compact('zipcodes'));
    }  
   
    public function zipjson() 
    {
        $zipcodes = Zipcode::paginate(10);
        
        return response()->json($zipcodes);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Zipcode  $zipcode
     * @return \Illuminate\Http\Response
     */
     /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  $zipcode = Zipcode::find($id); 
        return view('admin.zipcodes.show', compact('zipcode'));
        

    }     /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        return view('pages.zipcodes.create', [
            'model' => new Zipcode,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model=new Zipcode;
        $model->fill($request->all());

        if ($model->save()) {
            
            session()->flash('app_message', 'Zipcode saved successfully');
            return redirect()->route('zipcodes.index');
            } else {
                session()->flash('app_message', 'Something is wrong while saving Zipcode');
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Zipcode  $zipcode
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
    {
        $zipcode = Zipcode::find($id); 
        return view('admin.zipcodes.edit', compact('zipcode'));
        
    }   /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Zipcode  $zipcode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   $this->validate($request, [
        'zipcode' => 'required|max:20',
        'city' => 'required',
        'state' => 'required',
        
        

    ]);

    $input = $request->only('zipcode', 'city', 'state','state_abbr','county_area','code','latitude','longitude');
    if ($request->has('toggleswitch')) {
        // $archived="helllo block user on";
         $input['status'] = 0;
        // $user->is_active=0;
     } else{
        // $archived="bye block off";
         //$user->is_active=1;
         $input['status'] = 1;
     }
    $model = Zipcode::find($id);
    if(!$model){
        return redirect('admin/zipcode')->with('info','zipcode with id ' . $id . ' cannot be found.'); 
    }
    else{$model->update($input); //update the subscription info

        return redirect('admin/zipcode')->with('info','zipcode updated Successfully');
   }
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Request  $request
     * @param  Zipcode  $zipcode
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Zipcode $zipcode)
    {
        if ($zipcode->delete()) {
                session()->flash('app_message', 'Zipcode successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Zipcode');
            }

        return redirect()->back();
    }

    public function massDestroy(Request $request)
    {
        Zipcode::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function categoryData()
    {
        return DataTables::of(Zipcode::query())
        ->addColumn('action', function($row) {
            return '<a href="/admin/zipcode/view/'. $row->id .'" class="btn btn-xs btn-primary">View</a> <a href="/admin/zipcode/edit/'. $row->id .'" class="btn btn-xs btn-info">Edit</a>';
        })
        ->make(true);
    }
    public function allowedZipdata()
    {  $data = Zipcode::where('status',1)->get();
        return DataTables::of($data)
        ->addColumn('action', function($row) {
            return '<a href="/admin/zipcode/view/'. $row->id .'" class="btn btn-xs btn-primary">View</a> <a href="/admin/zipcode/edit/'. $row->id .'" class="btn btn-xs btn-info">Edit</a>';
        })
        ->make(true);
    }
    
}

