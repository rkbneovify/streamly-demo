<?php

namespace App\Http\Controllers\Admin;
use App\SessionTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;
use Crypt;
use Mail;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Yajra\DataTables\DataTables;
use App\HotelRoomTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{     
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::all()->pluck('name', 'id');

        return view('admin.users.index',compact('roles'));
    }

    public function create()
    {
        

        $roles = Role::all()->pluck('name', 'id');

        return view('admin.users.create', compact('roles'));  
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
                'zipcode' => 'required',
                'roles' => 'required|numeric',
                'plan_duration' => 'required|string',
                'number_of_devices' => 'nullable|integer|min:1',
            ]);
    
            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            $existing = User::where('email', $request->email)->first();
            if ($existing) {
                Log::warning("User already exists: " . $request->email);
                return redirect()->back()->with('existuser', $existing->id);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'contact' => $request->contact ?? null,
                'gender' => $request->gender ?? null,
                'dob' => $request->dob ?? null,
                'country' => $request->country ?? null,
                'city' => $request->city ?? null,
                'state' => $request->state ?? null,
                'address' => $request->address ?? null,
                'zipcode' => $request->zipcode,
                'email_verified_at' => null,
                'stripe_id' => 'sub_K6XMZT4knnWBOI',
                'card_brand' => 'visa',
                'card_last_four' => '4242',
                'is_active' => 0,
                'forced_change' => 1,
                'mode' => 'Testing'
            ]);
        
            Log::info("User created: ID {$user->id}");
    
            $user->roles()->sync([$request->roles]);

            $roleId = $request->roles;
            $roleType = null;

            if ((int)$roleId === 5) {
                $roleType = 'tieruser';
            } else {
                $roleType = 'user';
            }

    
            DB::table('user_zipcode_rules')->insert([
                'zipcode' => $request->zipcode,
                'email' => $request->email,
                'validate' => $request->channel_availability,
            ]);
    
            $endsAt = null;
            switch ($request->plan_duration) {
                case '1 Month':
                    $endsAt = Carbon::now()->addMonth();
                    break;
                case '3 Months':
                    $endsAt = Carbon::now()->addMonths(3);
                    break;
                case '6 Months':
                    $endsAt = Carbon::now()->addMonths(6);
                    break;
                case '12 Months':
                    $endsAt = Carbon::now()->addMonths(12);
                    break;
                case 'Never':
                    $endsAt = null;
                    break;
            }

            // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            // $plans = \Stripe\Plan::all(['active' => true, 'expand' => ['data.tiers']]);
            
            // $formattedPlans = collect($plans->data)->map(function ($plan) {
            //     return [
            //         'id' => $plan->id,
            //         'metadata' => $plan->metadata,
            //     ]; 
            // });
            
            // $roleType = ((int)$request->roles === 5) ? 'tieruser' : 'user';
            
            // $matchedPlan = $formattedPlans->first(function ($plan) use ($roleType) {
            //     return isset($plan['metadata']['role']) && $plan['metadata']['role'] === $roleType;
            // }); 

            // foreach ($formattedPlans as $plan) {
            //     Log::info("Plan: {$plan['id']} | Role: " . ($plan['metadata']['role'] ?? 'none'));
            // }
            
            
            // if (!$matchedPlan) {
            //     Log::error("No Stripe plan found for role: $roleType");
            //     return redirect()->back()->with('errormsg', "No Stripe plan available for this role.");
            // }
            
            // $stripePlanId = $matchedPlan['id'];

            $stripeId = 'sub_' . Str::random(16);
            $now = now(); 
            
           $roleId = $request->roles;
           $tieruserId = 5; 

           if (env('APP_URl') === 'https://streamlytv.com') {

            if ((int)$roleId === $tieruserId) {
                $stripePlanId = 'lLqM2zp3oUMO'; 
                $roleType = 'tieruser';
            } else {
                $stripePlanId = 'price_1MqxXZGbCi8tAySGisSrhf0S'; 
                $roleType = 'user'; 
            }  
        
        } else {
        
            if ((int)$roleId === $tieruserId) {
                $stripePlanId = 'bIDgJlA2fLmS'; 
                $roleType = 'tieruser';
            } else {
                $stripePlanId = 'FJZCNhouM8Nr'; 
                $roleType = 'user'; 
            }  
        
        }
        
        DB::table('subscriptions')->insert([
            'user_id' => $user->id,
            'name' => 'main',
            'stripe_id' => 'sub_' . Str::random(16),
            'stripe_status' => 'active',
            'stripe_plan' => $stripePlanId, 
            'quantity' => $request->number_of_devices ?? 1,
            'ends_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
            Log::info("Subscription added for user: {$user->id}");
    

            $tieruserId = 5;
            if ((int)$request->roles === $tieruserId) {
                $quantity = $request->number_of_devices ?? 1;
                for ($i = 0; $i < $quantity; $i++) {
                    DB::table('hotel_rooms')->insert([
                        'user_id' => $user->id,
                        'hotel_room' => 'Room' . ($i),
                        'session_id' => null,
                        'created_on' => $now,
                        'modified_on' => $now,
                    ]);
                }
                Log::info("Hotel rooms added: {$quantity} for user {$user->id}");
            }
    
            $verification_code = Crypt::encrypt($user->email);
            $FRONTEND_URL = env('FRONTEND_URL');
            $account_verification_link = $FRONTEND_URL . '/api/auth/verify?token=' . $verification_code;
    
            $html = "<html>
                Hi, {$request->name}<br><br>
                Thank you for registering.<br>
                Please verify: <a href='{$account_verification_link}' target='_blank'>Verify</a><br>
                Email: {$request->email}<br>
                Temp Password: {$request->password}<br><br>
                Thank you!<br>
            </html>";
    
            Mail::send('emails.general', ["html" => $html], function ($msg) use ($request) {
                $msg->to($request->email, $request->name)
                    ->bcc('rahul.gupta@oodles.io')
                    ->subject(env('APP_NAME') . ': Account Verification');
            });
    
            Log::info("Mail sent to: {$request->email}");
    
            return redirect('admin/users')->with('info', 'User Added Successfully');
    
        } catch (\Exception $e) {
            Log::error('Exception occurred while creating user: ' . $e->getMessage());
            return redirect()->back()->with('errormsg', 'An error occurred. Check logs.');
        }
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'confirmed',
            'roles' => 'required|array',
            'number_of_devices' => 'nullable|integer|min:1',
            'plan_duration' => 'nullable|string',
        ]);
    
        $input = $request->only('name', 'email', 'password', 'dob', 'gender', 'country', 'address', 'zipcode', 'contact');
        $input['is_active'] = $request->has('toggleswitch') ? 0 : 1;
    
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }
    
        $user = User::find($id);
        $user->update($input);
    
        $tieruserId = 5;
    
        // Step 1: Get previous roles
        $previousRoles = DB::table('role_user')->where('user_id', $user->id)->pluck('role_id')->toArray();
        $wasTierUser = in_array($tieruserId, $previousRoles);
    
        // Step 2: Update roles
        DB::table('role_user')->where('user_id', $id)->delete();
        foreach ($request->input('roles') as $value) {
            $user->attachRole($value);
        }
    
        // Step 3: Device & hotel room handling (always reset if tier role selected)
        $newDeviceCount = $request->filled('number_of_devices') ? (int) $request->number_of_devices : 0;
    // dd($newDeviceCount);  
        if (in_array($tieruserId, $request->input('roles'))) {
            // Became or still is a tier user — reset hotel rooms
            DB::table('hotel_rooms')->where('user_id', $user->id)->delete();
    
            if ($newDeviceCount > 0) {
                for ($i = 0; $i < $newDeviceCount; $i++) {
                    DB::table('hotel_rooms')->insert([
                        'user_id' => $user->id,
                        'hotel_room' => 'Room' . $i,
                        'session_id' => null,
                        'created_on' => now(),
                        'modified_on' => now(),
                    ]);
                }
            }
        } else {
            // Not a tier user — delete any existing hotel rooms
            DB::table('hotel_rooms')->where('user_id', $user->id)->delete();
        }
    
        // Step 4: Update zipcode rule
        DB::table('user_zipcode_rules')
            ->where('email', $user->email)
            ->where('zipcode', $request->zipcode)
            ->update([
                'validate' => $request->channel_availability
            ]);
    
        // Step 5: Calculate subscription end date
        $endsAt = null;
        switch ($request->plan_duration) {
            case '1 Month':
                $endsAt = Carbon::now()->addMonth();
                break;
            case '3 Months':
                $endsAt = Carbon::now()->addMonths(3);
                break;
            case '6 Months':
                $endsAt = Carbon::now()->addMonths(6);
                break;
            case '12 Months':
                $endsAt = Carbon::now()->addMonths(12);
                break;
            case 'Never':
            default:
                $endsAt = null;
                break;
        }
    
        // Step 6: Update subscription if tier user
        if (in_array($tieruserId, $request->input('roles'))) {
            DB::table('subscriptions')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'name' => 'main',
                    'stripe_status' => 'active',
                    'ends_at' => null,
                    'quantity' => $newDeviceCount,
                    'updated_at' => now(),
                ]
            );
        }
    
        return redirect('admin/users')->with('info', 'User Updated Successfully');
    }
    
    
    



    public function edit($id)
{
    $user = User::find($id);
    $roles = Role::get();
    $userRoles = $user->roles->pluck('id')->toArray();

    // Fetch latest subscription for the user
    $subscription = DB::table('subscriptions')
                    ->where('user_id', $id)
                    ->latest('created_at')
                    ->first();

    // Fetch zipcode rule for the user based on email
    $channelRule = DB::table('user_zipcode_rules')
                    ->where('email', $user->email)
                    ->first();

   $currentDeviceCount = DB::table('hotel_rooms')->where('user_id', $user->id)->count();

                  

        $planDuration = '';
        
        if (!empty($subscription->ends_at)) {
            $endsAt = Carbon::parse($subscription->ends_at)->startOfDay();
            $now = Carbon::now()->startOfDay();
        
            if ($endsAt->equalTo($now->copy()->addMonth())) {
                $planDuration = '1 Month';
            } elseif ($endsAt->equalTo($now->copy()->addMonths(3))) {
                $planDuration = '3 Months';
            } elseif ($endsAt->equalTo($now->copy()->addMonths(6))) {
                $planDuration = '6 Months';
            } elseif ($endsAt->equalTo($now->copy()->addMonths(12))) {
                $planDuration = '12 Months';
            } else {
                $planDuration = '';
            }
        } else {
            $planDuration = 'Never';
        }
                    
                
    return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'subscription', 'channelRule','planDuration','currentDeviceCount'));
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
        // Step 1: Get user details (especially email)
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['success' => 'User Not found.']);
        }
    
        $email = $user->email;
    
        // Step 2: Delete related zipcode rules
        DB::table('user_zipcode_rules')->where('email', $email)->delete();
    
        // Step 3: Delete user
        $user->delete();
    
        return response()->json(['success' => 'User deleted successfully.']);
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

        //old query
        //  $allSession=SessionTable::with('user')->selectRaw("users_id , count(id) activesession")->where("status",'1')->groupBy('users_id')->get();// echo $allSession; exit;

        //new query
        $mode =DB::table('Stripe_keys')->where('status', 'active')->value('type');

        $userIds = DB::table('users')
        ->where('mode', $mode)
        ->pluck('id')
        ->toArray();

        $allSession = SessionTable::with('user')
        ->selectRaw("users_id, count(id) as activesession")
        ->where("status", '1')
        ->whereIn('users_id', $userIds)
        ->groupBy('users_id')
        ->get();

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
               $hotelroomcount = HotelRoomTable::whereIn('session_id', $ids)->count();
                if ($hotelroomcount>0) {
                  HotelRoomTable::whereIn('session_id', $ids)->update([
                        'session_id' => null
                    ]);
                  }
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
        
        // return var_dump($columnName_arr[3]['search']['value']);//role column
       if ($columnName_arr[3]['search']['value']==''){
        $searchroleValue='';
        }else{
            $searchroleValue=$columnName_arr[3]['search']['value'];
        }
        $mode =DB::table('Stripe_keys')->where('status', 'active')->value('type');
        // Total records
        $totalRecords = User::whereHas('roles', function ($query)use ($searchroleValue) {
            $query->where('roles.name','like',  '%' .$searchroleValue. '%' );
 })->select('count(*) as allcount')->where('email', 'like', '%' .$searchValue . '%')->count();
        $totalRecordswithFilter = User::whereHas('roles', function ($query)use ($searchroleValue) {
            $query->where('roles.name','like',  '%' .$searchroleValue. '%' );
 })->select('count(*) as allcount')
        ->where('email', 'like', '%' .$searchValue . '%')->count();
    
        // Fetch records
        $records = User::whereHas('roles', function ($query)use ($searchroleValue) {
            $query->where('roles.name','like',  '%' .$searchroleValue. '%' );
 })->where('email', 'like', '%' .$searchValue . '%')
 ->orderBy($columnName,$columnSortOrder)
          
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
           "aaData" => $data_arr,
           "aaDatacount" => count($data_arr),
        //   "vardump"=> $searchroleValue

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
