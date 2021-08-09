<?php

namespace App\Http\Controllers\Admin;
use App\SessionTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\Role;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{     
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        
          
        return view('admin.users.index');
    }

    public function create()
    {
        

        $roles = Role::all()->pluck('name', 'id');

        return view('admin.users.create', compact('roles'));  
    }

    public function store(Request $request)
    {
        $email_verified_at=Carbon::now();

        $user = User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'contact'=>$request->contact??'null',
            'gender'=>$request->gender??'null',
            'dob'=>$request->dob??'null',
            'country'=>$request->country??'null',
            'address'=>$request->address??'null',
            'zipcode'=>$request->zipcode,
            'email_verified_at'=>$email_verified_at
        ]);
        $user->roles()->sync($request->input('roles', []));

     return redirect('admin/users')->with('info','User Added Successfully');
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'confirmed',
            'roles' => 'required',
    
        ]);
        $input = $request->only('name', 'email', 'password','dob','gender','country','address','zipcode','contact');
        if ($request->has('toggleswitch')) {
           // $archived="helllo block user on";
            $input['is_active'] = 0;
           // $user->is_active=0;
        } else{
           // $archived="bye block off";
            //$user->is_active=1;
            $input['is_active'] = 1;
        }
    
        
     // dd($archived);
        
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']); //update the password
        }else{
            $input = Arr::except($input,array('password')); //remove password from the input array
        }
       //$allpost= $request->post();
       // dd($input); exit; get all the post data
        $user = User::find($id);
        $user->update($input); //update the user info

        //delete all roles currently linked to this user
        DB::table('role_user')->where('user_id',$id)->delete();

        //attach the new roles to the user
        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }
        return redirect('admin/users')->with('info','User Updated Successfully');
        
        
    }

     public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get(); //get all roles
        $userRoles = $user->roles->pluck('id')->toArray();
        //dd($roles);
        return view('admin.users.edit',compact('user','roles','userRoles'));
    }

    

    public function show($id)
    {

        $user = User::find($id); //Find the requested role

        //Get the permissions linked to the role
        // $permissions =
        //     Permission::join("permission_role","permission_role.permission_id","=","permissions.id")
        //     ->where("permission_role.role_id",$id)
        //     ->get();

        //return the view with the role info and its permissions
       // return view('roles.show',compact('role','permissions'));
        return view('admin.users.show', compact('user'));
    }

    public function destroy($id)
    {
        $user= User::whereId($id)->delete(); 
         if($user){
            return response()->json(['success'=>'User deleted successfully.']);
          //  return redirect('admin/users')->with('info','User Deleted Successfully');
                      
        
        }else{
            return response()->json(['success'=>'User Not found.']);
           // return redirect('admin/users')->with('info','User Not found');
            
        }

        
    }

    public function massDestroy(Request $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, 204);
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
         $allSession=SessionTable::with('user')->selectRaw("users_id , count(id) activesession")->where("status",'1')->groupBy('users_id')->get();// echo $allSession; exit;
        return view('admin.users.sessions', compact('allSession'));
    }

    public function connecteddevice($id)
    {
        $user = User::find($id);
        $roles = Role::get(); //get all roles
        $userRoles = $user->roles->pluck('id')->toArray();
        //dd($roles);
        $allSession=SessionTable::with('user','hotel')->selectRaw("id,users_id , connected_devices,access_token, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,created_on, JSON_EXTRACT(device,'$.useragent') AS useragent")
        ->where([
        ['status', '=', '1'],
        ['users_id', '=', $id],
         ])->orderBy('connected_devices', 'asc')->get();
        return view('admin.users.connected',compact('allSession'));
    }

     public function sessiondestroy($id,Request $request)
    {  $input=$request->only('access_token');
      $tokenString=$input['access_token'];
      $myarr=[];
        array_push($myarr,$id );  
       // print_r($myarr);
        $user= SessionTable::whereIn('id', $myarr)->update(['status' => 0,'modified_on' => Carbon::now()]);
        SessionTable::where([
        ['users_id', '=', $id],
        ['status', '=', '1'],
        ['connected_devices', '!=', '1'],
         ])
->update([
    'connected_devices' => DB::raw('`connected_devices` - 1 ')
]);
//print_r($user); exit;
config([
  'jwt.blacklist_enabled' => true
]);
// JWTAuth::parseToken()->invalidate();
$errors=[];
try{
JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($tokenString), $forceForever = false);
}catch (Exception $e) {
    $errors['error']=$e->getMessage();
}catch (JWTException $e){
    $errors['error']=$e->getMessage();
}
        
         if($user){
             if($errors){
            return redirect()->back()->with('info','User Session Deleted Successfully'.json_encode($errors));
        }

        return redirect()->back()->with('info','User Session Deleted Successfully');

        }else{
            return redirect()->back()->with('info','User Session Not found');
            
        }

        
    }

    private function destroyJWTtoken($JwtValues)
{
    // \Tymon\JWTAuth\Blacklist::has
    // false === \Tymon\JWTAuth\Blacklist::has(
    //     \Tymon\JWTAuth\Facades\JWTAuth::getPayload($token)
    // )

    $errors=[];
        foreach ($JwtValues as $key => $value) {
            
            // if ($details == "error") {
            //     array_push($this->errors, $model);
            //     continue;
            // }
            // $user = JWTAuth::setToken($value['access_token'])->toUser();
            // if($user == null){
            //     dd('user is null bro');
            //     }
            // dd($user);
            try { 
                config([
                    'jwt.blacklist_enabled' => true
                  ]);
                JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($value['access_token']), $forceForever = false);
                 
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    $value['error']=$e->getMessage();
                    array_push($errors, $value);
                    
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    $value['error']=$e->getMessage();
                    array_push($errors, $value);
                    
                }else{
                    $value['error']=$e->getMessage();
                    array_push($errors, $value);
                    
                }
                continue;
            }

        
    }
    // dump($errors);
    if(!empty($errors)){
    return $errors;}else{
        return 'no error';
    }
}
     public function multisessiondestroy(Request $request)
    {  
        $this->validate($request, [
            'id' => 'required|array',
            "id.*"  => "required|numeric|distinct",
       
        ]);
               $ids = $request->id;
            //   dd($ids);
               $user= SessionTable::select('id','connected_devices','users_id','access_token')->whereIn('id', $ids)->get();
            //    dump($user->toArray());
               
           $r= $this->destroyJWTtoken($user->toArray());
           

            $userid=$user['0']['users_id'];
            $q='';
            if(!$r=='no error'){
             $q=count($r).' error were found';
            }
            // dump($userid);
            // dd($ids);
            // return response()->json(['message'=>json_encode($items)],200);
            $sessionRemove= SessionTable::whereIn('id', $ids)->update(['status' => 0,'modified_on' => Carbon::now()]);
            SessionTable::where('users_id', $userid)->where([
            ['status', '=', '1'],
            ['connected_devices', '!=', '1'],
             ])
             ->update([
               'connected_devices' => DB::raw('`connected_devices` - 1 ')
               ]);
            if($sessionRemove){
                if(!$q){
             return response()->json(['message'=>"User Session Deleted Successfully.".$q,'jwterror'=>$r],200);
                }

            return response()->json(['message'=>"User Session Deleted Successfully."],200);
        }else{
         return response()->json(['message'=>"User Session Not found"],400);
        }
    }

    public function UserAjaxData(Request $request)
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
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')
        ->where('email', 'like', '%' .$searchValue . '%')->count();
    
        // Fetch records
        $records = User::with('roles')->orderBy($columnName,$columnSortOrder)
          ->where('email', 'like', '%' .$searchValue . '%')
          ->orWhere('name', 'like', '%' .$searchValue . '%')
        //   ->orWhere('zipcodes.state', 'like', '%' .$searchValue . '%')
          ->select('users.*')
          ->skip($start)
          ->take($rowperpage)
          ->get();
    
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

    public function UpdateAll(Request $request)
    {  $this->validate($request, [
     'id' => 'required|array',
     "id.*"  => "required|numeric|distinct",
     'status' => 'required|numeric|min:0|max:1',

 ]);
        $ids = $request->id;
        $status = $request->status;
        
     //    print_r($ids);exit;
        $input['is_active'] = $status;
        if(User::whereIn('id', $ids)->update($input)){
         return response()->json(['message'=>"User Updated successfully."],200);
        }else{
         return response()->json(['message'=>"User Updated Error Occured"],400);
        }

        
    }
}
