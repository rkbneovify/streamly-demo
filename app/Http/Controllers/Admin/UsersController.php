<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomUserExport;
use App\Exports\UserExport;
use App\Exports\UserSessionExport;
use App\SessionTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Zipcode;
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
use App\Models\RoleUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::all()->pluck('name', 'id');

        return view('admin.users.index', compact('roles'));
    }

    public function create()
    {

        // $predefinedLocalZipcodes = ['85120', '85215', '85205', '75662', '08872', '08904', '18344', '85022', '18438', '18302', '18326']; // or fetch from DB
        $predefinedLocalZipcodes = DB::table('zipcodes')->pluck('zipcode')->toArray();

        $roles = Role::all()->pluck('name', 'id');
     
        // Fetch dynamic local zipcodes from channels 134–140
        $localZipcodes = DB::table('channels')
            ->whereBetween('id', [134, 140])
            ->pluck('zip_code')
            ->map(function ($zips) {
                return json_decode($zips, true) ?? [];
            })
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        return view('admin.users.create', compact('roles', 'predefinedLocalZipcodes'));
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
                'connected_device' => 'required|in:0,1',

            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
    
                if ($request->ajax()) {
                    return response()->json([
                        'errors' => $validator->errors()
                    ], 422);
                }
    
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // 2️⃣ Check ZIP exists in DB
            $zipcodeExists = Zipcode::where('zipcode', $request->zipcode)->exists();
            if (!$zipcodeExists) {
                Log::warning("Invalid zipcode entered: " . $request->zipcode);
    
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'invalid_zip',
                        'message' => 'Invalid Zipcode. User not created'
                    ], 200);
                }
    
                return redirect()->back()->with('errormsg', 'Invalid Zipcode. User not created');
            }
    
            // 3️⃣ Predefined zipcodes
            $predefinedLocalZipcodes = [
                "85120",
                "85215",
                "85205",
                "75662",
                "08872",
                "08904",
                "18344",
                "18438",
                "18302",
                "18326"
            ];
            $localChannelIds = [134, 135, 136, 137, 138, 139, 140];
            
            $newZip = $request->zipcode;
            if (
                $request->channel_availability == "2" &&
                !in_array($newZip, $predefinedLocalZipcodes) &&
                (!$request->has('force_add') || $request->force_add != 1)
            ) {
                Log::info("ZIP not in local and force_add not set: " . $newZip);
    
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'zipcode_not_in_local',
                        'message' => "This zipcode isn't available in your local channels. Do you want to add it?"
                    ], 422);
                }
    
                return redirect()->back()->with('errormsg', "This zipcode isn't available in your local channels.");
            }
    
            // 5️⃣ Add ZIP to local channels if needed
            if ($request->channel_availability == "2" && !in_array($newZip, $predefinedLocalZipcodes)) {
                foreach ($localChannelIds as $channelId) {
                    $channel = DB::table('channels')->where('id', $channelId)->first();
                    if ($channel) {
                        $existingZipcodes = json_decode($channel->zip_code ?? '[]', true);
                        if (!in_array($newZip, $existingZipcodes)) {
                            $existingZipcodes[] = $newZip;
                            DB::table('channels')->where('id', $channelId)->update([
                                'zip_code' => json_encode(array_values($existingZipcodes)),
                            ]);
                            Log::info("ZIP $newZip added to channel $channelId");
                        }
                    }
                }
            }
    
            
            // 4. Proceed with user creation (as you already do)



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
                'connected_device' => $request->connected_device,
                'email_verified_at' => null,
                'stripe_id' => 'sub_K6XMZT4knnWBOI',
                'card_brand' => 'visa',
                'card_last_four' => '4242',
                'is_active' => 0,
                'forced_change' => 1,
                'mode' => 'Testing',
                'user_type' => 'custom'
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


            $channelAvailability = $request->channel_availability ?? [];
            if (!is_array($channelAvailability)) {
                $channelAvailability = [$channelAvailability];
            }

            // Auto-add Local Channels if All Channels selected AND ZIP is local
            if (in_array(1, $channelAvailability) && in_array($request->zipcode, $predefinedLocalZipcodes) && !in_array(2, $channelAvailability)) {
                $channelAvailability[] = 2;
            } 

            // Save rules
            foreach ($channelAvailability as $availability) {
                DB::table('user_zipcode_rules')->insert([
                    'zipcode' => $request->zipcode,
                    'email' => $request->email,
                    'validate' => $availability,
                ]);
            }


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

            if (env('APP_URL') === 'https://streamlytv.com') {

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
                'stripe_id' => 'sub_custom_' . Str::random(16),
                'stripe_status' => 'active',
                'stripe_plan' => $stripePlanId,
                'quantity' => $request->number_of_devices ?? 1,
                'ends_at' => null,
                'renews_at' => $endsAt,
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

            // $html = "<html>
            //     Hi, {$request->name}<br><br>
            //     Thank you for registering.<br>
            //     Please verify: <a href='{$account_verification_link}' target='_blank'>Verify</a><br>
            //     Email: {$request->email}<br>
            //     Temp Password: {$request->password}<br><br>
            //     Thank you!<br>
            // </html>";

            $html = "<html>
                Hi, {$request->name}<br><br>
                Thank you for registering.<br>
                Email: {$request->email}<br>
                Password: {$request->password}<br><br>
                Thank you!<br>
            </html>";

            Mail::send('emails.general', ["html" => $html], function ($msg) use ($request) {
                $msg->to($request->email, $request->name)
                    ->bcc('rahul.gupta@oodles.io')
                    ->subject(env('APP_NAME') . ': Login Details');
            });

            $user->is_custom_user = 1;
            $user->email_verified_at = now();
            $user->is_active = 1;
            $user->save();

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
            'channel_availability' => 'required',
            'zipcode'=> 'nullable|string',
            'city'=> 'nullable|string',
            'state'=> 'nullable|string',
            'connected_device' => 'required|in:0,1',

        ]); 


        $input = $request->only('name', 'email', 'password', 'dob', 'gender', 'city', 'state','address', 'zipcode', 'contact', 'connected_device');

        $input['is_active'] = $request->has('toggleswitch') ? 0 : 1;

        // Handle password
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }


        $zipcodeExists = Zipcode::where('zipcode', $request->zipcode)->exists();

        $predefinedLocalZipcodes = [
            "85120", "85215", "85205", "75662", "08872",
            "08904", "18344", "18438", "18302", "18326"
        ];
        
        $localChannelIds = [134, 135, 136, 137, 138, 139, 140];
        $newZip = $request->zipcode;
        
        // 1. If not a valid zipcode
        if (!$zipcodeExists) {
            Log::warning("Invalid zipcode entered: " . $newZip);
            return response()->json([
                'status' => 'invalid_zip',
                'message' => 'Invalid Zipcode. User not created'
            ], 421);
        }
            
        // 2. If it's a local channel but ZIP not in predefined and force_add not present
        if (
            $request->channel_availability == "2" &&
            !in_array($newZip, $predefinedLocalZipcodes) &&
            (!$request->has('force_add') || $request->force_add != 1)
        ) {
            Log::info("ZIP not in local and force_add not set: " . $newZip);
        
            // Force JSON response manually for AJAX call
            return response()->json([
                'status' => 'zipcode_not_in_local',
                'message' => "This zipcode isn't available in your local channels. Do you want to add it?"
            ], 422);
        }
        
        
        // 3. Add zip to local channels if needed
        if ($request->channel_availability == "2" && !in_array($newZip, $predefinedLocalZipcodes)) {
            foreach ($localChannelIds as $channelId) {
                $channel = DB::table('channels')->where('id', $channelId)->first();
                if ($channel) {
                    $existingZipcodes = json_decode($channel->zip_code ?? '[]', true); // Make sure this matches your DB column
                    if (!in_array($newZip, $existingZipcodes)) {
                        $existingZipcodes[] = $newZip;
        
                        DB::table('channels')->where('id', $channelId)->update([
                            'zip_code' => json_encode(array_values($existingZipcodes)), // Use same column name here
                        ]);
        
                        Log::info("ZIP $newZip added to channel $channelId");
                    }
                }
            }
        }
        

        
        $user = User::find($id);

        if ($request->has('connected_device')) {
            $input['connected_device'] = $request->connected_device; // use new value
        } else {
            $input['connected_device'] = $user->connected_device; // keep old value
        }

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

        // Step 3: Device & hotel room handling
        $newDeviceCount = $request->filled('number_of_devices') ? (int)$request->number_of_devices : 0;

        if (in_array($tieruserId, $request->input('roles'))) {
            $existingRooms = DB::table('hotel_rooms')->where('user_id', $user->id)->orderBy('id')->get();
            $existingCount = $existingRooms->count();

            if ($newDeviceCount > $existingCount) {
                // Add only the difference
                $roomsToAdd = $newDeviceCount - $existingCount;
                for ($i = 0; $i < $roomsToAdd; $i++) {
                    DB::table('hotel_rooms')->insert([
                        'user_id' => $user->id,
                        'hotel_room' => 'Room' . ($existingCount + $i),
                        'session_id' => null,
                        'created_on' => now(),
                        'modified_on' => now(),
                    ]);
                }
            } elseif ($newDeviceCount < $existingCount) {
                // Delete extra rooms from the end


                $extraRooms = $existingRooms->slice($newDeviceCount);
                foreach ($extraRooms as $room) {
                    DB::table('hotel_rooms')->where('id', $room->id)->delete();
                }
            }
        } else {
            // Not a tier user — delete all hotel rooms
            DB::table('hotel_rooms')->where('user_id', $user->id)->delete();
        }

        // Step 4: Update zipcode rule
        DB::table('user_zipcode_rules')
            ->where('email', $user->email)
            ->update([
                'validate' => $request->channel_availability,
                'zipcode' => $request->zipcode
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
        // Step 6: Subscription logic
        $isNowTierUser = in_array($tieruserId, $request->input('roles'));

        if ($isNowTierUser) {
            // If user is a tier user, insert or update subscription with new quantity
            DB::table('subscriptions')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'name' => 'main',
                    'stripe_status' => 'active',
                    'ends_at' => null,
                    'renews_at' => $endsAt,
                    'quantity' => $newDeviceCount,
                    'updated_at' => now(),
                ]
            );
        } elseif ($wasTierUser && !$isNowTierUser) {
            // If user was a tier user and is no longer => update quantity to 1
            DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->update([
                    'quantity' => 1,
                    'updated_at' => now(),
                ]);
        }


        return redirect('admin/users')->with('info', 'User Updated Successfully');
    }

 





    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get();
        $userRoles = $user->roles->pluck('id')->toArray();

        $subscription = DB::table('subscriptions')
            ->where('user_id', $id)
            ->latest('created_at')
            ->first();
        // dd($subscription);
        $channelRule = DB::table('user_zipcode_rules')
            ->where('email', $user->email)
            ->first();

        $currentDeviceCount = DB::table('hotel_rooms')->where('user_id', $user->id)->count();

        $planDuration = '';

        if (empty($subscription->renews_at)) {
            $planDuration = 'Never';
        } else {
            $endsAt = Carbon::parse($subscription->renews_at)->startOfDay();
            $now = Carbon::now()->startOfDay();

            if ($endsAt->eq($now->copy()->addMonth())) {
                $planDuration = '1 Month';
            } elseif ($endsAt->eq($now->copy()->addMonths(3))) {
                $planDuration = '3 Months';
            } elseif ($endsAt->eq($now->copy()->addMonths(6))) {
                $planDuration = '6 Months';
            } elseif ($endsAt->eq($now->copy()->addMonths(12))) {
                $planDuration = '12 Months';
            } else {
                $planDuration = '';
            }
        }


        return view('admin.users.edit', compact(
            'user',
            'roles',
            'userRoles',
            'subscription',
            'channelRule',
            'planDuration',
            'currentDeviceCount'
        ));
    }


    public function getZip(Request $request)
    {

        $search = $request->input('search');

        $zipcodes = Zipcode::where('zipcode', 'like', $search . '%')
            ->select('zipcode as value', 'city', 'state')
            ->limit(5)
            ->get();


        return response()->json($zipcodes);
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

    // public function destroy($id)
    // {
    //     // Step 1: Get user details (especially email)
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json(['success' => 'User Not found.']);
    //     }

    //     $email = $user->email;

    //     // Step 2: Delete related zipcode rules
    //     DB::table('user_zipcode_rules')->where('email', $email)->delete();

    //     // Step 3: Delete user
    //     $user->delete();

    //     return response()->json(['success' => 'User deleted successfully.']);
    // }


    public function destroy(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                \Log::warning("User delete attempt failed: User ID {$id} not found.");
                return response()->json(['error' => 'User not found.'], 404);
            }

            $email = $user->email;

            \Log::info("Attempting to delete related data for user ID {$id}");

            // Delete related rows in foreign key tables first
            DB::table('outside_location_plays')->where('users_id', $user->id)->delete();

            DB::table('session_table')->where('users_id', $user->id)->delete();
            DB::table('user_zipcode_rules')->where('email', $email)->delete();

            $user->delete();

            \Log::info("User ID {$id} successfully deleted.");
            if ($request->ajax()) {
                return response()->json(['success' => 'User deleted successfully.']);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error("Delete user failed for ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while deleting the user.'], 500);
        }
    }




    public function massDestroy(Request $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    // public function activeSession(Request $request)
    // {


    //     // $user = JWTAuth::user();//all the user info
    //     $uid = 6;
    //     //  $user['id'];
    //     //and get the number of last connected device
    //     //     $prevConnectDevicecount = SessionTable::selectRaw(" connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name")
    //     //     ->where([
    //     //     ['status', '=', '1'],
    //     //     ['users_id', '=', $uid],
    //     //      ])->orderBy('connected_devices', 'asc');
    //     //     $s='';$numofdevice='';
    //     // $s=$prevConnectDevicecount->toSql();
    //     // echo $s; 
    //     // $numofdevice=$prevConnectDevicecount->count();


    //     //  $response = array('success' =>true ,'response_code'=>200,'numofdevice'=>$numofdevice,'message'=>'Connected device found successfully','data'=>$s);
    //     //  return response()->json($response, 200);
    //     //get the users with there session

    //     //old query
    //     //  $allSession=SessionTable::with('user')->selectRaw("users_id , count(id) activesession")->where("status",'1')->groupBy('users_id')->get();// echo $allSession; exit;

    //     //new query
    //     $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');



    //     $query = User::query()
    //         ->where('mode', $mode);
    //     //  search conditions
    //     if (!empty($request->search)) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('email', 'like', '%' . $request->search . '%')
    //                 ->orWhere('name', 'like', '%' . $request->search . '%');
    //         });
    //     }
    //     if (!empty($request->role)) {
    //         $userIds = RoleUser::where('role_id', $request->role)->pluck('user_id');
    //         $query->whereIn('id', $userIds);
    //     }
    //     $userIds = $query->pluck('id')->toArray();

    //     $allSession = SessionTable::with(['user.roles'])
    //         ->selectRaw("users_id, count(id) as activesession")
    //         ->where("status", '1')
    //         ->whereIn('users_id', $userIds)
    //         ->groupBy('users_id')
    //         ->paginate(10);

    //     // return response()->json(['success' => true, "datalist" => $allSession]);
    //     $roles = Role::get();
    //     if ($request->ajax()) {
    //         return view('admin.users.partials.table', compact('allSession', 'roles'));
    //     }
    //     return view('admin.users.sessions', compact('allSession', 'roles'));
    // }


    // new code of active session

    public function activeSession(Request $request)
    {
        // Step 1: Build base User query (no mode filter, both test/live users)
        $query = User::query();

        // Step 2: Apply search filter (email or name)
        if (!empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        // Step 3: Apply role filter
        if (!empty($request->role)) {
            $userIdsByRole = RoleUser::where('role_id', $request->role)->pluck('user_id');
            $query->whereIn('id', $userIdsByRole);
        }

        // Step 4: Get filtered user IDs
        $filteredUserIds = $query->pluck('id')->toArray();

        // Step 5: Get active sessions grouped by user
        $allSession = SessionTable::with(['user.roles'])
            ->selectRaw("users_id, COUNT(id) as activesession")
            ->where("status", '1')
            ->whereIn('users_id', $filteredUserIds)
            ->groupBy('users_id')
            ->paginate(10);

        // Step 6: Attach `mode_label` to each session
        foreach ($allSession as $session) {
            $user = $session->user;

            if ($user->mode === 'Testing' && $user->user_type === 'custom') {
                $session->mode_label = 'Custom Test';
            } elseif ($user->mode === 'Testing' && is_null($user->user_type)) {
                $session->mode_label = 'Web Test';
            } elseif ($user->mode === 'Live') {
                $session->mode_label = 'Live';
            }
        }

        // Step 7: Get all roles
        $roles = Role::all();

        // Step 8: Return view
        if ($request->ajax()) {
            return view('admin.users.partials.table', compact('allSession', 'roles'));
        }

        return view('admin.users.sessions', compact('allSession', 'roles'));
    }






    public function connecteddevice($id)
    {
        $user = User::find($id);
        $roles = Role::get(); //get all roles
        $userRoles = $user->roles->pluck('id')->toArray();
        //dd($roles);
        $allSession = SessionTable::with('user', 'hotel', 'pdInfo')->selectRaw("id,users_id , connected_devices,access_token, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,created_on, JSON_EXTRACT(device,'$.useragent') AS useragent")
            ->where([
                ['status', '=', '1'],
                ['users_id', '=', $id],
            ])->orderBy('connected_devices', 'asc')->get();
        // return response()->json(['success' => true, "datalist" => $allSession]);
        return view('admin.users.connected', compact('allSession'));
    }

    public function sessiondestroy($id, Request $request)
    {
        $input = $request->only('access_token');
        $tokenString = $input['access_token'];
        $myarr = [];
        array_push($myarr, $id);
        // print_r($myarr);
        $user = SessionTable::whereIn('id', $myarr)->update(['status' => 0, 'modified_on' => Carbon::now()]);
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
        $errors = [];
        try {
            JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($tokenString), $forceForever = false);
        } catch (Exception $e) {
            $errors['error'] = $e->getMessage();
        } catch (JWTException $e) {
            $errors['error'] = $e->getMessage();
        }

        if ($user) {
            if ($errors) {
                return redirect()->back()->with('info', 'User Session Deleted Successfully' . json_encode($errors));
            }

            return redirect()->back()->with('info', 'User Session Deleted Successfully');
        } else {
            return redirect()->back()->with('info', 'User Session Not found');
        }
    }

    private function destroyJWTtoken($JwtValues)
    {
        // \Tymon\JWTAuth\Blacklist::has
        // false === \Tymon\JWTAuth\Blacklist::has(
        //     \Tymon\JWTAuth\Facades\JWTAuth::getPayload($token)
        // )

        $errors = [];
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
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                    $value['error'] = $e->getMessage();
                    array_push($errors, $value);
                } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                    $value['error'] = $e->getMessage();
                    array_push($errors, $value);
                } else {
                    $value['error'] = $e->getMessage();
                    array_push($errors, $value);
                }
                continue;
            }
        }
        // dump($errors);
        if (!empty($errors)) {
            return $errors;
        } else {
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
        $user = SessionTable::select('id', 'connected_devices', 'users_id', 'access_token')->whereIn('id', $ids)->get();
        //    dump($user->toArray());

        $r = $this->destroyJWTtoken($user->toArray());


        $userid = $user['0']['users_id'];
        $q = '';
        if (!$r == 'no error') {
            $q = count($r) . ' error were found';
        }
        // dump($userid);
        // dd($ids);
        // return response()->json(['message'=>json_encode($items)],200);
        $sessionRemove = SessionTable::whereIn('id', $ids)->update(['status' => 0, 'modified_on' => Carbon::now()]);
        SessionTable::where('users_id', $userid)->where([
            ['status', '=', '1'],
            ['connected_devices', '!=', '1'],
        ])
            ->update([
                'connected_devices' => DB::raw('`connected_devices` - 1 ')
            ]);
        $hotelroomcount = HotelRoomTable::whereIn('session_id', $ids)->count();
        if ($hotelroomcount > 0) {
            HotelRoomTable::whereIn('session_id', $ids)->update([
                'session_id' => null
            ]);
        }
        if ($sessionRemove) {
            if (!$q) {
                return response()->json(['message' => "User Session Deleted Successfully." . $q, 'jwterror' => $r], 200);
            }

            return response()->json(['message' => "User Session Deleted Successfully."], 200);
        } else {
            return response()->json(['message' => "User Session Not found"], 400);
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
        if ($columnName_arr[3]['search']['value'] == '') {
            $searchroleValue = '';
        } else {
            $searchroleValue = $columnName_arr[3]['search']['value'];
        }
        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');
        // Total records
        $totalRecords = User::whereHas('roles', function ($query) use ($searchroleValue) {
            $query->where('roles.name', 'like',  '%' . $searchroleValue . '%');
        })->select('count(*) as allcount')->where('email', 'like', '%' . $searchValue . '%')->count();
        $totalRecordswithFilter = User::whereHas('roles', function ($query) use ($searchroleValue) {
            $query->where('roles.name', 'like',  '%' . $searchroleValue . '%');
        })->select('count(*) as allcount')
            ->where('email', 'like', '%' . $searchValue . '%')->count();

        // Fetch records
        $records = User::whereHas('roles', function ($query) use ($searchroleValue) {
            $query->where('roles.name', 'like',  '%' . $searchroleValue . '%');
        })->where('email', 'like', '%' . $searchValue . '%')
            ->orderBy($columnName, $columnSortOrder)

            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $email = $record->email;
            $name = $record->name;
            $status = $record->is_active;
            //    $role = $record->roles->first()->display_name;
            $role = $record->roles;


            // view Button
            $viewButton = "<a href='/admin/users/view/" . $id . "' class='btn btn-xs btn-primary'>View</a>";
            $editButton = "<a href='/admin/users/edit/" . $id . "' class='btn btn-xs btn-info'>Edit</a>";
            $deleteButton = '<a href="javascript:void(0)" id="btn-delete" data-toggle="tooltip"  data-id="' . $id . '" data-original-title="Delete" class="deleteItem btn btn-danger btn-xs ">Delete</a>';

            $action2 = "<input type='checkbox' class='update_check' id='delcheck_" . $id . "'  value='" . $id . "'>";

            $action = $viewButton . " " . $editButton . " " . $deleteButton;

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
    {
        $this->validate($request, [
            'id' => 'required|array',
            "id.*"  => "required|numeric|distinct",
            'status' => 'required|numeric|min:0|max:1',

        ]);
        $ids = $request->id;
        $status = $request->status;

        //    print_r($ids);exit;
        $input['is_active'] = $status;
        if (User::whereIn('id', $ids)->update($input)) {
            return response()->json(['message' => "User Updated successfully."], 200);
        } else {
            return response()->json(['message' => "User Updated Error Occured"], 400);
        }
    }

    public function customUserList(Request $request)
    {

        $query = User::query();
        $query->with('roles')->where('is_custom_user', 1);

        if (!empty($request->role)) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role);
            });
        }
        if (!empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $users =   $query->paginate(10);
        // return response()->json($users);
        $roles = Role::all();
        if ($request->ajax()) {
            return view('admin.users.partials.custom-table', compact('users', 'roles'));
        }
        return view('admin.users.custom', compact('users', 'roles'));
    }

    public function exportUser(Request $request, $type)
    {

        $filters = $request->only(['role']);
        // dd($filters);
        $filename = 'users_' . now()->format('d-m-Y_H-i-s') . '.' . $type;
        return Excel::download(new UserExport($filters), $filename);
    }

    public function exportUserSession(Request $request, $type)
    {


        $filters = $request->only(['role']);
        // dd($filters);
        $filename = 'users-sessions_' . now()->format('d-m-Y_H-i-s') . '.' . $type;
        return Excel::download(new UserSessionExport($filters), $filename);
    }

    public function exportCustomUser(Request $request, $type)
    {

        $filters = $request->only(['role']);
        // dd($filters);
        $filename = 'custom-users_' . now()->format('d-m-Y_H-i-s') . '.' . $type;
        return Excel::download(new CustomUserExport($filters), $filename);
    }
}
