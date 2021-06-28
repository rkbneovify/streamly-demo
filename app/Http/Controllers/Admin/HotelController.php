<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\User;
use App\HotelRoomTable;
class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('admin.hotels.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function hotelrooms()
    {
        
        return view('admin.hotels.hotelrooms');
    }

    public function activeSession(){
        // $user = JWTAuth::user();//all the user info
        $uid= 6;
        //  $user['id'];
        //and get the number of last connected device
    //     $prevConnectDevicecount = SessionTable::selectRaw(" connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name")
    //     ->where([
    //     ['status', '=', '1'],
    //     ['users_id', '=', $uid],
    //      ])->orderBy('connected_devices', 'asc');
    //     $s='';$numofdevice='';
    // $s=$prevConnectDevicecount->toSql();
    // echo $s; 
    // $numofdevice=$prevConnectDevicecount->count();
   
     
        //  $response = array('success' =>true ,'response_code'=>200,'numofdevice'=>$numofdevice,'message'=>'Connected device found successfully','data'=>$s);
        //  return response()->json($response, 200);
         //get the users with there session
         $allSession=HotelRoomTable::with('user')->selectRaw("id,user_id , count(id) activesession")->groupBy('user_id')->get();// echo $allSession; exit;
        //  dd($allSession);
        return view('admin.hotels.sessions', compact('allSession'));
    }
    public function connectedhotel($id)
    {
        $user = User::find($id);
        $roles = Role::get(); //get all roles
        $userRoles = $user->roles->pluck('id')->toArray();
        //dd($roles);
        
        
        $allSession=HotelRoomTable::with('user','session')
        ->where([
        ['user_id', '=', $id],
         ])->orderBy('id', 'asc')->get();
        //  dd($allSession);
        return view('admin.hotels.connected',compact('allSession'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function HotelAdminUserAjaxData(Request $request)
    {   ## Read value
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
        $totalRecords = User::select('count(*) as allcount')->whereHas(
            'roles', function($q){
                $q->where('name', 'hoteladmin');
            }
        )->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')->
        whereHas(
            'roles', function($q){
                $q->where('name', 'hoteladmin');
            }
        )
        ->where('email', 'like', '%' .$searchValue . '%')->count();
    
        // Fetch records
        $records = User::with('roles')->orderBy($columnName,$columnSortOrder)
          
        //   ->orWhere('zipcodes.state', 'like', '%' .$searchValue . '%')
          
          ->whereHas(
            'roles', function($q){
                $q->where('name', 'hoteladmin');
            }
        )
        ->select('users.*')
        ->where(function ($query) use ($searchValue) {
            $query->where('name', "like", "%" . $searchValue . "%");
            $query->orWhere('email', "like", "%" . $searchValue . "%");
        })
          ->skip($start)
          ->take($rowperpage)
          ->get();
    // dd($records);
        $data_arr = array();
        
        foreach($records as $record){
           $id = $record->id;
           $email = $record->email;
           $name = $record->name;
           $status = $record->is_active;
        //    $role = $record->roles->first()->display_name;
           $role = $record->roles;
           
    
         // view Button
         $viewButton = "<a href='/admin/users/view/".$id."' class='btn btn-xs btn-primary'>View</a>";
         $editButton = "<a href='/admin/users/edit/".$id."' class='btn btn-xs btn-info'>Edit</a>";
         $deleteButton = '<a href="javascript:void(0)" id="btn-delete" data-toggle="tooltip"  data-id="'.$id.'" data-original-title="Delete" class="deleteItem btn btn-danger btn-xs ">Delete</a>';
    
         $action2 ="<input type='checkbox' class='update_check' id='delcheck_".$id."'  value='".$id."'>";
    
         $action = $viewButton." ".$editButton." ".$deleteButton;
    
           $data_arr[] = array(
             "id" => $id,
             "email" => $email,
             "name" => $name,
             "role" => $role,
             "status" => $status,
             "action" => $action,
             "action2" => $action2
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


        // return DataTables::of(User::with('roles'))
        // ->addColumn('roles', function ($row) {
        //     $options = '';
        //     foreach ($row->roles as $role){
        //         $options .= $role->display_name;
        //     }
        //    return $options;
        //     })
        // ->addColumn('action', function($row) {
        //     return ' <a href="/admin/users/edit/'. $row->id .'" class="btn btn-xs btn-primary">View/Edit</a> <a href="javascript:void(0)" id="btn-delete" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="deleteItem btn btn-danger btn-xs ">Delete</a>';
        // })
        // ->addIndexColumn()
        // ->make(true);
    }

}
