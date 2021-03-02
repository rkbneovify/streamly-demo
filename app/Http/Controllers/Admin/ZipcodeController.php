<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Zipcode;


use App\Models\AddressesCheckAvailability;
use Illuminate\Support\Facades\DB;
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
        // $zipcodes = Zipcode::select('zipcodes.*')->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->paginate(10);
        // return view('admin.zipcodes.index', compact('zipcodes'));
       
       return view('admin.zipcodes.index');
    }  
    public function index2()
    {   
        // $zipcodes = Zipcode::select('zipcodes.*')->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->paginate(10);
        // return view('admin.zipcodes.index', compact('zipcodes'));
       
       return view('admin.zipcodes.index2');
    }  
    public function allowedZipcodes()
    { 
        // $zipcodes = Zipcode::select('zipcodes.*')->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->paginate(10);
       
    //    return view('admin.zipcodes.allowedzipcode', compact('zipcodes'));
       return view('admin.zipcodes.allowedzipcode');
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

        return view('admin.zipcodes.create');
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
            
            return redirect('admin/zipcode')->with('info','Zipcode Added Successfully');
        
            } else {
                return redirect('admin/zipcode')->with('info','Some error occured');
        
            }
        
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
        return redirect('admin/allowedzipcode')->with('info','zipcode with id ' . $id . ' cannot be found.'); 
    }
    else{$model->update($input); //update the subscription info

        return redirect('admin/allowedzipcode')->with('info','zipcode updated Successfully');
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
    { $data = Zipcode::select('zipcodes.*')->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->get();
        return DataTables::of($data)
        ->addColumn('action', function($row) {
            return '<a href="/admin/zipcode/view/'. $row->id .'" class="btn btn-xs btn-primary">View</a> <a href="/admin/zipcode/edit/'. $row->id .'" class="btn btn-xs btn-info">Edit</a>';
        })
        ->make(true);
    }
    public function allowedZipdata(Request $request)
    {  
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
    
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
    
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
    
        // Total records
        $totalRecords = Zipcode::select('count(*) as allcount')->where('zipcodes.status',1)->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->count();
        $totalRecordswithFilter = Zipcode::select('count(*) as allcount')->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')
        ->where([
            ['zipcodes.status', '=', '1'],
            ['zipcodes.zipcode', 'like', '%' .$searchValue . '%'],
        ])->count();
    
        // Fetch records
        $records = Zipcode::orderBy($columnName,$columnSortOrder)
        ->where('zipcodes.status', '=',  '1')
        ->where(function ($query) use ($searchValue) {
            $query->where('zipcodes.zipcode', 'like', '%' .$searchValue . '%');
            $query->orWhere('zipcodes.city', 'like', '%' .$searchValue . '%');
            $query->orWhere('zipcodes.state', 'like', '%' .$searchValue . '%');
        })
          ->select('zipcodes.*')
          ->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')
          ->skip($start)
          ->take($rowperpage)
          ->get();
    
        $data_arr = array();
        
        foreach($records as $record){
           $id = $record->id;
           $zipcode = $record->zipcode;
           $city = $record->city;
           $state = $record->state;
           $status = $record->status;
           
    
         // view Button
         $viewButton = "<a href='/admin/zipcode/view/".$id."' class='btn btn-xs btn-primary'>View</a>";
         $editButton = "<a href='/admin/zipcode/edit/".$id."' class='btn btn-xs btn-info'>Edit</a>";
    
    
         $action = $viewButton." ".$editButton;
    
           $data_arr[] = array(
             "id" => $id,
             "zipcode" => $zipcode,
             "city" => $city,
             "state" => $state,
             "status" => $status,
             "action" => $action
           );
        }
    
        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $data_arr
        );
    
        echo json_encode($response);
        exit;
    

    }

    public function map()
    {  $data = Zipcode::select('zipcodes.*',DB::raw("ST_AsGeoJSON(map.`SHAPE`) shape"))->where('zipcodes.status',1)->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->get();
        # Build GeoJSON feature collection array
    $geojson = array(
        'type'      => 'FeatureCollection',
        
        // 'crs' => htmlspecialchars(json_encode($crs), ENT_QUOTES, 'UTF-8'),
        'features'  => array()
     );
        if($data->isEmpty()){
            $response = [
                'success' => false,
                'data' => $data,
                'message'=>'No zipcode is allowed',
                'response_code'=>200,
            ];
            return response()->json($response, 200); 
        }
        
          foreach($data as $fieldnam) {
            $properties =  array(
                'color'=> 'red',
                'title' => $fieldnam['zipcode']
                
            );
            
            $feature = array(
                'type' => 'Feature',
                'properties' => $properties,
                
                'geometry' => $fieldnam->shape,
                 
           );
                            
      
        
             array_push($geojson['features'], $feature);
         }
        return view('admin.zipcodes.map', compact('geojson'));
    }
    
   /*
   AJAX request
   */
   public function categoryData2(Request $request){

    ## Read value
    $draw = $request->get('draw');
    $start = $request->get("start");
    $rowperpage = $request->get("length"); // Rows display per page

    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $search_arr = $request->get('search');

    $columnIndex = $columnIndex_arr[0]['column']; // Column index
    $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    $searchValue = $search_arr['value']; // Search value

    // Total records
    $totalRecords = Zipcode::select('count(*) as allcount')->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->count();
    $totalRecordswithFilter = Zipcode::select('count(*) as allcount')->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')
    ->where('zipcodes.zipcode', 'like', '%' .$searchValue . '%')->count();

    // Fetch records
    $records = Zipcode::orderBy($columnName,$columnSortOrder)
      ->where('zipcodes.zipcode', 'like', '%' .$searchValue . '%')
      ->orWhere('zipcodes.city', 'like', '%' .$searchValue . '%')
      ->orWhere('zipcodes.state', 'like', '%' .$searchValue . '%')
      ->select('zipcodes.*')
      ->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')
      ->skip($start)
      ->take($rowperpage)
      ->get();

    $data_arr = array();
    
    foreach($records as $record){
       $id = $record->id;
       $zipcode = $record->zipcode;
       $city = $record->city;
       $state = $record->state;
       $status = $record->status;
       

     // view Button
     $viewButton = "<a href='/admin/zipcode/view/".$id."' class='btn btn-xs btn-primary'>View</a>";
     $editButton = "<a href='/admin/zipcode/edit/".$id."' class='btn btn-xs btn-info'>Edit</a>";


     $action = $viewButton." ".$editButton;

       $data_arr[] = array(
         "id" => $id,
         "zipcode" => $zipcode,
         "city" => $city,
         "state" => $state,
         "status" => $status,
         "action" => $action
       );
    }

    $response = array(
       "draw" => intval($draw),
       "iTotalRecords" => $totalRecords,
       "iTotalDisplayRecords" => $totalRecordswithFilter,
       "aaData" => $data_arr
    );

    echo json_encode($response);
    exit;
  }
    public function webfenceturf()
    {   $data = Zipcode::select('zipcodes.*',DB::raw("ST_AsGeoJSON(map.`SHAPE`) shape"))->where('zipcodes.status',1)->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->get();
        # Build GeoJSON feature collection array
    $geojson = array(
        'type'      => 'FeatureCollection',
        
        // 'crs' => htmlspecialchars(json_encode($crs), ENT_QUOTES, 'UTF-8'),
        'features'  => array()
     );
        if($data->isEmpty()){
            $response = [
                'success' => false,
                'data' => $data,
                'message'=>'No zipcode is allowed',
                'response_code'=>200,
            ];
            return response()->json($response, 200); 
        }
        
          foreach($data as $fieldnam) {
            $properties =  array(
                'color'=> 'red',
                'title' => $fieldnam['zipcode']
                
            );
            
            $feature = array(
                'type' => 'Feature',
                'properties' => $properties,
                
                'geometry' => $fieldnam->shape,
                 
           );
                            
      
        
             array_push($geojson['features'], $feature);
         }

        return view('admin.zipcodes.mapfence',compact('geojson'));
    }

    public function addressindex()
    {
        
     
        return view('admin.zipcodes.addresses');
    }

    public function addressindexDatatables()
    {  $data = AddressesCheckAvailability::select('id','address2','city','state','zip5')->get();
        return DataTables::of($data)
        ->addColumn('action', function($row) {
            return '<a href="/admin/zipcode/view/'. $row->id .'" class="btn btn-xs btn-primary">View</a> <a href="/admin/addressupdateedit/'. $row->id .'" class="btn btn-xs btn-info">Edit</a>';
        })

        ->addIndexColumn()
        ->make(true);
    }

    public function addressupdateedit($id){

        $zipstate = AddressesCheckAvailability::find($id); 
        return view('admin.zipcodes.addressedit', compact('zipstate'));
    }
    public function addressupdate(Request $request, $id){
        $this->validate($request, [
            'zip5' => 'required|max:20',
            'city' => 'required',
            'state' => 'required',
            'address2' => 'required',
            
            
    
        ]);
    
        $input = $request->only('zip5', 'city', 'state','address2');
        
        $model = AddressesCheckAvailability::find($id);
        if(!$model){
            return redirect('admin/address')->with('info','AddressesCheckAvailability with id ' . $id . ' cannot be found.'); 
        }
        else{$model->update($input); //update the subscription info
    
            return redirect('admin/address')->with('info','AddressesCheckAvailability updated Successfully');
       }    }
    
}

