<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActivityEvent;
use App\Enums\ActivityEventType;
use App\Exports\CustomUserExport;
use App\Exports\UserExport;
use App\Exports\UserSessionDeviceExport;
use App\Exports\UserSessionExport;
use App\HoaUser;
use App\Models\EastCostLocal;
use App\SessionTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Traits\ActivityLogger;
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
use App\LocalZipcode;
use App\Models\Channel;
use App\Models\PersonalDevice;
use App\Models\RoleUser;
use App\Models\Subscription;
use App\PhxLocalChannelAccess;
use App\PremiumChannelAccess;
use App\Services\StripeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Stripe\Stripe;

class UsersController extends Controller
{
    use ActivityLogger;
    protected $stripeService;
    public function __construct(StripeService $stripeService)
    {

        Stripe::setApiKey(config('services.stripe.secret'));
        $this->middleware('auth');
        $this->stripeService = $stripeService;
    }

    public function index2()
    {
        $roles = Role::all()->pluck('name', 'id');

        return view('admin.users.index', compact('roles'));
    }
    public function index(Request $request)
    {
        SessionTable::where('status', 1)->resolveSession();

        $roles = Role::all()->pluck('name', 'id');
        $query = User::query();
        $query->with('roles');
        $query->with('zipcodeRule');
        $query->withCount('local');
        $query->withCount('premium');
        $query->withCount([
            'sessions' => function ($q) {
                $q->select(DB::raw('count(distinct device_id)'))
                    ->where('status', 1);
            }
        ]);

        $query->withCount('hoaHouses');
        if (!empty($request->selected)) {
            // Put selected IDs first
            $ids = implode(',', $request->selected);
            $query->orderByRaw("FIELD(id, $ids) DESC");
        }

        if (!empty($request->sort_device)) {

            if ($request->sort_device == 'desc') {
                $query->orderByDesc('sessions_count');
            } elseif ($request->sort_device == 'asc') {
                $query->orderBy('sessions_count');
            }
        }





        $query->withCount('rooms');



        if (!empty($request->role)) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if (!empty($request->mode)) {
            if (strtolower($request->mode) == 'live') {
                $query->where('mode', $request->mode);
            } else {
                $query->whereIn('mode', ['Testing', 'Test', null]);
            }
        }
        // elseif (empty($request->mode)) {
        //     $query->where(function ($q) {
        //         $q->whereIn('mode', ['Live', 'Testing', 'Test'])
        //             ->orWhereNull('mode');
        //     })
        //         ->orderByRaw("FIELD(mode, 'Live', 'Testing', 'Test', NULL)");

        // }

        if (!empty($request->status)) {
            $status = null;
            if ($request->status == 'active') {
                $status = 1;
            } elseif ($request->status == 'inactive') {
                $status = 0;
            }
            $query->where('is_active', $status);
        }
        if (!empty($request->search)) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($request->daterange)) {
            $query->where(function ($q) use ($request) {
                $dates = explode(" - ", $request->daterange);
                $start = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();
                $q->whereBetween('created_at', [$start, $end]);
            });
        }


        if ($request->sort_date == 'asc') {

            $query->orderBy('created_at', 'asc');
        } elseif (empty($request->sort_date) && !empty($request->daterange)) {
            $query->orderBy('created_at', 'asc');
            $request->merge(['sort_date' => 'asc']);
        } else {

            $query->latest();
        }

        $query->userType($request->user_type);


        $limit = $request->input('limit', 10);
        $users = $query->paginate($limit);
        // return response()->json($users);

        if (empty($request->daterange)) {
            $dates = [
                'start_date' => User::orderBy('created_at', 'desc')->value('created_at'),
                'end_date' => User::orderBy('created_at', 'desc')->value('created_at'),
                'min_date' => User::orderBy('created_at', 'asc')->value('created_at'),
                'max_date' => User::orderBy('created_at', 'desc')->value('created_at')
            ];
        } else {


            $daterange = explode(' - ', $request->daterange);
            // dd($daterange);
            $dates = [
                'start_date' => $daterange[0],
                'end_date' => $daterange[1],
                'min_date' => User::orderBy('created_at', 'asc')->value('created_at'),
                'max_date' => User::orderBy('created_at', 'desc')->value('created_at')
            ];
        }



        // return response()->json($users);
        if ($request->ajax()) {

            return view('admin.users.partials.new-table', compact('users', 'roles', 'dates'));
        }
        return view('admin.users.index2', compact('users', 'roles', 'dates'));
    }

    public function create()
    {

        $predefinedLocalZipcodes = LocalZipcode::where('status', 1)->groupBy('zipcode')->pluck('zipcode')->toArray();
        // $predefinedLocalZipcodes = DB::table('zipcodes')->pluck('zipcode')->toArray();

        $roles = Role::where('name', '!=', 'superadmin')->pluck('name', 'id');

        // Fetch dynamic local zipcodes from channels 134–140
        $localZipcodes = LocalZipcode::where('status', 1)->get();
        $premiumChannels = Channel::where('type', 'premium')
            ->where('status', 1)->get();
        $phxChannels = Channel::where('type', 'phx-locals')
            ->where('status', 1)->get();
        $eastCoastChannels = Channel::where('type', 'east-coast-locals')
            ->where('status', 1)->get();

        return view('admin.users.create', compact('roles', 'predefinedLocalZipcodes', 'localZipcodes', 'premiumChannels', 'phxChannels', 'eastCoastChannels'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
                'zipcode' => 'required',
                'roles' => 'required|numeric',
                'plan_duration' => 'required|string',
                'number_of_devices' => 'nullable|integer|min:1',
                'num_of_devices' => 'nullable|integer|min:1',
                'device_per_house' => 'nullable|integer|min:1',
                'connected_device' => 'required|in:0,1',
                'channel_availability' => 'required',

            ]);

            // $channel_availability=$request->channel_availability;
            // dd($channel_availability);

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
            $predefinedLocalZipcodes = LocalZipcode::where('status', 1)->groupBy('zipcode')->pluck('zipcode')->toArray();
            $localChannelIds = Channel::where('type', 'local')->where('status', 1)->pluck('id')->toArray();

            // dd($predefinedLocalZipcodes);

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
                    $channel = DB::table('channels')->where('status', 1)->where('id', $channelId)->first();
                    if ($channel) {
                        $existingZipcodes = json_decode($channel->zip_code ?? '[]', true);
                        if (!in_array($newZip, $existingZipcodes)) {
                            $existingZipcodes[] = $newZip;
                            DB::table('channels')->where('id', $channelId)->update([
                                'zip_code' => json_encode(array_values($existingZipcodes)),
                            ]);

                            $localZipcode = LocalZipcode::where('zipcode', $newZip)
                                ->where('channel_id', $channelId)
                                ->first();
                            if ($localZipcode == null) {
                                $allZipcode = Zipcode::where('zipcode', $newZip)
                                    ->where('status', 1)->first();
                                if ($allZipcode != null) {
                                    $localZipcode = new LocalZipcode();
                                    $localZipcode->channel_id = $channelId;
                                    $localZipcode->zipcode = $allZipcode->zipcode;
                                    $localZipcode->city = $allZipcode->city;
                                    $localZipcode->state = $allZipcode->state;
                                    $localZipcode->state_abbr = $allZipcode->state_abbr;
                                    $localZipcode->county_area = $allZipcode->county_area;
                                    $localZipcode->code = $allZipcode->code;
                                    $localZipcode->latitude = $allZipcode->latitude;
                                    $localZipcode->longitude = $allZipcode->longitude;
                                    $localZipcode->status = 1;
                                    $localZipcode->save();
                                }
                            }

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
                'email_verified_at' => now(),
                'stripe_id' => 'sub_K6XMZT4knnWBOI',
                'card_brand' => 'visa',
                'card_last_four' => '4242',
                'is_active' => 1,
                'forced_change' => 1,
                'mode' => 'Testing',
                'user_type' => 'custom',
                'is_custom_user' => 1,
                'access_premium_channel' => $request->access_premium_channel != null ? 1 : 0,

            ]);

            // $user->is_custom_user = 1;
            // $user->email_verified_at = now();
            // $user->is_active = 1;
            // $user->save();


            Log::info("User created: ID {$user->id}");

            $user->roles()->sync([$request->roles]);

            $roleId = $request->roles;
            $roleType = null;

            if ((int) $roleId === 5) {
                $roleType = 'tieruser';
            } else {
                $roleType = 'user';
            }


            $channelAvailability = $request->channel_availability ?? [];
            if (!is_array($channelAvailability)) {
                $channelAvailability = [$channelAvailability];
            }

            // Auto-add Local Channels if All Channels selected AND ZIP is local
            // if (in_array(1, $channelAvailability) && in_array($request->zipcode, $predefinedLocalZipcodes) && !in_array(2, $channelAvailability)) {
            //     $channelAvailability[] = 2;
            // }

            foreach ($channelAvailability as $availability) {
                DB::table('user_zipcode_rules')->insert([
                    'zipcode' => $request->zipcode,
                    'email' => $request->email,
                    'validate' => $availability,
                ]);
            }

            // // Save rules
            //     DB::table('user_zipcode_rules')->insert([
            //         'zipcode' => $request->zipcode,
            //         'email'   => $request->email,
            //         'validate'=> $request->channel_availability
            //     ]);



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

                if ((int) $roleId === $tieruserId) {
                    $stripePlanId = 'lLqM2zp3oUMO';
                    $roleType = 'tieruser';
                } else {
                    $stripePlanId = 'price_1MqxXZGbCi8tAySGisSrhf0S';
                    $roleType = 'user';
                }
            } else {

                if ((int) $roleId === $tieruserId) {
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
                'plan_duration' => $request->plan_duration,
                'quantity' => $request->number_of_devices ?? 1,
                'ends_at' => null,
                'canceled_at' => null,
                'custom_status' => 'active',
                'current_period_start' => now(),
                'renews_at' => $endsAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info("Subscription added for user: {$user->id}");


            $tieruserId = 5;
            if ((int) $request->roles === $tieruserId) {
                $quantity = $request->number_of_devices ?? 1;
                for ($i = 1; $i <= $quantity; $i++) {
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


            //  for HOA user
            if ($request->roles == 7) {
                // $qty = $request->num_of_houses;
                // for ($i = 1; $i <= $qty; $i++) {
                //     $hoa = new HoaUser();
                //     $hoa->user_id = $user->id;
                //     $hoa->serial_no = $i;
                //     $hoa->passcode = HoaUser::generatePasscodeForHoa();
                //     $hoa->device_limit = $request->device_per_house;
                //     $hoa->save();
                // }

                $user->num_of_houses = $request->num_of_houses;
                $user->device_per_house = $request->device_per_house;
                $user->save();
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


            Log::info("Mail sent to: {$request->email}");


            $user->roles;
            $user->subscriptionLatest;

            // Premium channels
            if ($request->premium_channels != null) {
                foreach ($request->premium_channels as $item) {
                    $channel = Channel::where('id', $item)->first();
                    $premiumChannel = new PremiumChannelAccess();
                    $premiumChannel->user_id = $user->id;
                    $premiumChannel->channel_id = $item;
                    $premiumChannel->name = $channel != null ? $channel->name : null;
                    $premiumChannel->status = 1;
                    $premiumChannel->save();
                }
            }

            // Phx channels
            if ($request->phx_channels != null) {
                foreach ($request->phx_channels as $item) {
                    $channel = Channel::where('id', $item)->first();
                    $phxChannel = new PhxLocalChannelAccess();
                    $phxChannel->user_id = $user->id;
                    $phxChannel->channel_id = $item;
                    $phxChannel->name = $channel != null ? $channel->name : null;
                    $phxChannel->status = 1;
                    $phxChannel->save();
                }
            }
            if ($request->east_coast_channels != null) {
                foreach ($request->east_coast_channels as $item) {
                    $channel = Channel::where('id', $item)->first();
                    $east = new EastCostLocal();
                    $east->user_id = $user->id;
                    $east->channel_id = $item;
                    $east->name = $channel != null ? $channel->name : null;
                    $east->status = 1;
                    $east->save();
                }
            }



            $this->activity()
                ->event("User")
                ->eventType(ActivityEventType::CREATED)
                ->description("New user created")
                ->isAdmin()
                ->properties([
                    "data" => $user
                ])->save();
            DB::commit();
            return redirect('admin/users')->with('info', 'User Added Successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            // dd($e->getMessage());
            // Log::error('Exception occurred while creating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred. Check logs.');
        }
    }


    public function update(Request $request, $id)
    {

        // dd($request->all());

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,

            'password' => $request->password_field ? 'required|confirmed' : '',
            'roles' => 'required|array',
            'number_of_devices' => 'nullable|integer|min:1',
            'plan_duration' => 'nullable|string',
            'channel_availability' => 'required',
            'zipcode' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'connected_device' => 'required|in:0,1',

        ]);


        DB::beginTransaction();
        try {
            $input = $request->only('name', 'email', 'password', 'dob', 'gender', 'city', 'state', 'address', 'zipcode', 'contact', 'connected_device', 'access_premium_channel');

            $input['is_active'] = $request->has('toggleswitch') ? 0 : 1;

            // Handle password
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, ['password']);
            }

            $input['access_premium_channel'] = $request->access_premium_channel != null ? 1 : 0;


            $zipcodeExists = Zipcode::where('zipcode', $request->zipcode)->exists();

            // $localZipcodes = DB::table('channels')
            //     ->whereBetween('id', [134, 140])
            //     ->pluck('zip_code')
            //     ->toArray();

            $predefinedLocalZipcodes = LocalZipcode::where('status', 1)->groupBy('zipcode')->pluck('zipcode')->toArray();

            //  channels
            $localChannelIds = Channel::where('status', 1)->pluck('id')->toArray();
            // return response()->json($localChannelIds);

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

                            $localZipcode = LocalZipcode::where('zipcode', $newZip)
                                ->where('channel_id', $channelId)
                                ->first();
                            if ($localZipcode == null) {
                                $allZipcode = Zipcode::where('zipcode', $newZip)
                                    ->where('status', 1)->first();
                                if ($allZipcode != null) {
                                    $localZipcode = new LocalZipcode();
                                    $localZipcode->channel_id = $channelId;
                                    $localZipcode->zipcode = $allZipcode->zipcode;
                                    $localZipcode->city = $allZipcode->city;
                                    $localZipcode->state = $allZipcode->state;
                                    $localZipcode->state_abbr = $allZipcode->state_abbr;
                                    $localZipcode->county_area = $allZipcode->county_area;
                                    $localZipcode->code = $allZipcode->code;
                                    $localZipcode->latitude = $allZipcode->latitude;
                                    $localZipcode->longitude = $allZipcode->longitude;
                                    $localZipcode->status = 1;
                                    $localZipcode->save();
                                }
                            }

                            Log::info("ZIP $newZip added to channel $channelId");
                        }
                    }
                }
            }



            $user = User::find($id);
            $roleId = $user->roles[0]->id;
            $roleName = $user->roles[0]->name;
            $subscription = $user->subscriptionLatest;


            $this->activity()
                ->event("User")
                ->eventType(ActivityEventType::UPDATED)
                ->isAdmin()
                ->description("User details updated")
                ->oldData($user);

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




            // If role try to change/ remove old session and device
            if (!in_array($roleId, $request->roles)) {
                $sessions = SessionTable::where('users_id', $user->id)->get();
                foreach ($sessions as $item) {
                    $session = SessionTable::where("id", $item->id)->first();
                    JWTAuth::setToken($session->access_token)->invalidate();
                    $session->delete();
                }



                HoaUser::where('user_id', $user->id)->delete();
                HotelRoomTable::where('user_id', $user->id)->delete();
            }

            // Step 2: Update roles
            DB::table('role_user')->where('user_id', $id)->delete();
            foreach ($request->input('roles') as $value) {
                $user->attachRole($value);
            }



            // Step 3: Device & hotel room handling
            $newDeviceCount = $request->filled('number_of_devices') ? (int) $request->number_of_devices : 0;

            if (in_array($tieruserId, $request->input('roles'))) {
                $existingRooms = DB::table('hotel_rooms')->where('user_id', $user->id)->orderBy('id')->get();
                $existingCount = $existingRooms->count();

                if ($newDeviceCount > $existingCount) {
                    // Add only the difference
                    $roomsToAdd = $newDeviceCount - $existingCount;
                    for ($i = 1; $i <= $roomsToAdd; $i++) {
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


                    foreach ($extraRooms as $item) {
                        if ($item->session_id != null) {
                            $session = SessionTable::where("id", $item->session_id)->first();
                            JWTAuth::setToken($session->access_token)->invalidate();
                            $session->delete();
                        }
                    }

                    foreach ($extraRooms as $room) {
                        DB::table('hotel_rooms')->where('id', $room->id)->delete();
                    }
                }
            } else {
                // Not a tier user — delete all hotel rooms
                $rooms = DB::table('hotel_rooms')->where('user_id', $user->id)
                    ->whereNotNull('session_id')
                    ->get();
                foreach ($rooms as $item) {
                    $session = SessionTable::where("id", $item->session_id)->first();
                    JWTAuth::setToken($session->access_token)->invalidate();
                    $session->delete();
                }

                DB::table('hotel_rooms')->where('user_id', $user->id)->delete();
            }

            // Step 4: Update zipcode rule
            DB::table('user_zipcode_rules')
                ->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'validate' => $request->channel_availability,
                        'zipcode' => $request->zipcode
                    ]
                );
            // ->update([
            //     'validate' => $request->channel_availability,
            //     'zipcode' => $request->zipcode
            // ]);

            // Step 5: Calculate subscription end date


            // Step 6: Update subscription if tier user
            // Step 6: Subscription logic
            /*$isNowTierUser = in_array($tieruserId, $request->input('roles'));

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
            }*/

            // new subscription logic



            // Hoa update logic
            if (in_array(7, $request->input('roles'))) {

                $updateStatus = HoaUser::updateDevice($user, $request);
                if ($updateStatus != null && $updateStatus['status'] === false) {
                    return redirect('admin/users')->with('info', $updateStatus['message']);
                }
            }

            // subscription logic
            if ($request->plan_duration != null || $request->plan_duration != '') {

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

                $subscription = Subscription::where('user_id', $id)->first();

                if ($subscription != null) {
                    $stripeSubscription = $this->stripeService->getAllSubscriptionsById($subscription->stripe_id);



                    if ($subscription->plan_duration == null) {
                        // if user have stripe Subscription cancel then apply custom subscription
                        $stripeSubscription = $this->stripeService->getAllSubscriptionsById($subscription->stripe_id);
                        // return response()->json($stripeSubscription);

                        if (count($stripeSubscription) > 0 && in_array($stripeSubscription->first()->status, ['active', 'past_due'])) {

                            if ($stripeSubscription->first()->livemode == true) {
                                $this->stripeService->cancelSubscriptionNow($subscription->stripe_id);
                            } elseif ($stripeSubscription->first()->livemode == false) {
                                $this->stripeService->cancelSubscriptionNow($subscription->stripe_id, 'Testing');
                            }


                            DB::table('subscriptions')->where('user_id', $user->id)->update(
                                [
                                    'name' => 'main',
                                    'stripe_status' => 'canceled',
                                    'plan_duration' => $request->plan_duration,
                                    'is_custom' => 1,
                                    'ends_at' => null,
                                    'custom_status' => 'active',
                                    'current_period_start' => now(),
                                    'renews_at' => $endsAt,
                                    'quantity' => $newDeviceCount,
                                    'updated_at' => now(),
                                ]
                            );

                            $user->is_custom_user = 1;
                            $user->mode = 'custom';
                            $user->save();
                        }
                    }
                }
            }

            if ($request->plan_duration != null && $request->plan_duration != $subscription->plan_duration) {
                DB::table('subscriptions')->where('user_id', $user->id)->update(
                    [
                        'name' => 'main',
                        'stripe_status' => 'canceled',
                        'plan_duration' => $request->plan_duration,
                        'is_custom' => 1,
                        'ends_at' => null,
                        'canceled_at' => null,
                        'custom_status' => 'active',
                        'current_period_start' => now(),
                        'renews_at' => $endsAt,
                        'quantity' => $newDeviceCount,
                        'updated_at' => now(),
                    ]
                );
                $user->is_custom_user = 1;
                $user->mode = 'custom';
                $user->save();
            }


            // premium_channels
            if ($request->premium_channels == null) {
                PremiumChannelAccess::where('user_id', $user->id)->update([
                    'status' => 0
                ]);
            } else {

                foreach ($request->premium_channels as $item) {
                    $premiumChannel = PremiumChannelAccess::where('user_id', $user->id)
                        ->where('channel_id', $item)->first();
                    if ($premiumChannel != null) {
                        $channel = Channel::where('id', $item)->first();
                        $premiumChannel->name = $channel != null ? $channel->name : null;
                        $premiumChannel->status = 1;
                        $premiumChannel->save();
                    } else {
                        $channel = Channel::where('id', $item)->first();
                        $premiumChannel = new PremiumChannelAccess();
                        $premiumChannel->user_id = $user->id;
                        $premiumChannel->channel_id = $item;
                        $premiumChannel->name = $channel != null ? $channel->name : null;
                        $premiumChannel->status = 1;
                        $premiumChannel->save();
                    }
                }

                PremiumChannelAccess::where('user_id', $user->id)->whereNotIn('channel_id', $request->premium_channels)->update([
                    'status' => 0
                ]);
            }


            // PHX locals_channels
            if ($request->phx_channels == null) {
                PhxLocalChannelAccess::where('user_id', $user->id)->update([
                    'status' => 0
                ]);
            } else {

                foreach ($request->phx_channels as $item) {
                    $premiumChannel = PhxLocalChannelAccess::where('user_id', $user->id)
                        ->where('channel_id', $item)->first();
                    if ($premiumChannel != null) {
                        $channel = Channel::where('id', $item)->first();
                        $premiumChannel->name = $channel != null ? $channel->name : null;
                        $premiumChannel->status = 1;
                        $premiumChannel->save();
                    } else {
                        $channel = Channel::where('id', $item)->first();
                        $premiumChannel = new PhxLocalChannelAccess();
                        $premiumChannel->user_id = $user->id;
                        $premiumChannel->channel_id = $item;
                        $premiumChannel->name = $channel != null ? $channel->name : null;
                        $premiumChannel->status = 1;
                        $premiumChannel->save();
                    }
                }

                PhxLocalChannelAccess::where('user_id', $user->id)->whereNotIn('channel_id', $request->phx_channels)->update([
                    'status' => 0
                ]);
            }

            // east coast locals_channels
            if ($request->east_coast_channels == null) {
                EastCostLocal::where('user_id', $user->id)->update([
                    'status' => 0
                ]);
            } else {

                foreach ($request->east_coast_channels as $item) {
                    $eastCoast = EastCostLocal::where('user_id', $user->id)
                        ->where('channel_id', $item)->first();
                    if ($eastCoast != null) {
                        $channel = Channel::where('id', $item)->first();
                        $eastCoast->name = $channel != null ? $channel->name : null;
                        $eastCoast->status = 1;
                        $eastCoast->save();
                    } else {
                        $channel = Channel::where('id', $item)->first();
                        $eastCoast = new EastCostLocal();
                        $eastCoast->user_id = $user->id;
                        $eastCoast->channel_id = $item;
                        $eastCoast->name = $channel != null ? $channel->name : null;
                        $eastCoast->status = 1;
                        $eastCoast->save();
                    }
                }

                EastCostLocal::where('user_id', $user->id)->whereNotIn('channel_id', $request->east_coast_channels)->update([
                    'status' => 0
                ]);
            }


            $this->newData($user->refresh())
                ->save();

            DB::commit();

            return redirect('admin/users')->with('info', 'User Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return redirect('admin/users')
                ->with('info', $th->getMessage());
        }
    }







    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('name', '!=', 'superadmin')->get();
        $userRoles = $user->roles->pluck('id')->toArray();
        // return response()->json($user);
        $subscription = DB::table('subscriptions')
            ->where('user_id', $id)
            ->latest('created_at')
            ->first();
        // dd($subscription);
        // $data = $this->stripeService->getAllSubscriptionsById($subscription->stripe_id);
        // return response()->json($data);
        $channelRule = DB::table('user_zipcode_rules')
            ->where('email', $user->email)
            ->first();



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

        if ($subscription && $subscription->is_custom == 0 && $subscription->plan_duration == null) {
            $planDuration = '';
        } elseif ($subscription && $subscription->is_custom == 1 && $subscription->plan_duration != null) {
            $planDuration = $subscription->plan_duration;
        }

        $currentDeviceCount = 0;
        // tier user
        if ($userRoles && in_array(5, $userRoles)) {
            $currentDeviceCount = DB::table('hotel_rooms')->where('user_id', $user->id)->count();
        }
        // Hoa user
        if ($userRoles && in_array(7, $userRoles)) {
            $currentDeviceCount = HoaUser::getActiveHousesCount($user->id);
        }

        // premium channel list

        $premiumChannels = Channel::with([
            'premium' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])->where('type', 'premium')
            ->where('status', 1)
            ->get();

        $phxChannels = Channel::with([
            'phxLocal' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])->where('type', 'phx-locals')
            ->where('status', 1)
            ->get();


        $eastCoastChannels = Channel::with([
            'eastCoast' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])->where('type', 'east-coast-locals')
            ->where('status', 1)
            ->get();


        // return response()->json(compact('eastCoastChannels'));
        return view('admin.users.edit', compact(
            'user',
            'roles',
            'userRoles',
            'subscription',
            'channelRule',
            'planDuration',
            'currentDeviceCount',
            'premiumChannels',
            'phxChannels',
            'eastCoastChannels'
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




    public function show($id, StripeService $stripeService)
    {







        $user = User::where('id', $id)
            ->with('roles')
            ->first();


        //Get the permissions linked to the role
        // $permissions =
        //     Permission::join("permission_role","permission_role.permission_id","=","permissions.id")
        //     ->where("permission_role.role_id",$id)
        //     ->get();

        //return the view with the role info and its permissions
        // return view('roles.show',compact('role','permissions'));
        $subscription = DB::table('subscriptions')
            ->where('user_id', $id)
            ->latest('updated_at')
            ->first();
        // dd($subscription);


        $channelRule = DB::table('user_zipcode_rules')
            ->where('email', $user->email)
            ->first();

        $currentDeviceCount = DB::table('hotel_rooms')->where('user_id', $user->id)->count();

        $planDuration = '';

        if (empty($subscription->renews_at) && empty($subscription->ends_at)) {
            $planDuration = 'Never';
        } else {

            $renewsAt = Carbon::parse($subscription->trial_ends_at ?? $subscription->renews_at ?? $subscription->ends_at);
            $updatedAt = Carbon::parse($subscription->updated_at);

            // Calculate difference in days
            $days = $renewsAt->diffInDays($updatedAt);

            if ($days === 0) {
                $planDuration = "1 Day";
            } elseif ($days < 30) {
                $planDuration = $days . ' Days';
            } elseif ($days >= 30) {

                $months = $renewsAt->diffInMonths($updatedAt);
                $planDuration = $months == 0 ? 1 . ' Month' : $months . ' Month' . ($months > 1 ? 's' : '');
            }
        }

        try {
            $plan = null;
            if ($subscription != null) {
                $plans = $stripeService->getAllPlans();
                $plan = $plans->where('id', $subscription->stripe_plan)->first();
            } else {
                $plan = null;
            }

            // return response()->json($user);
            return view('admin.users.show', compact(
                'user',
                'channelRule',
                'planDuration',
                'currentDeviceCount',
                'subscription',
                'plan'
            ));
        } catch (\Throwable $th) {
            //throw $th;
            Alert::error('Oops !', "Something went wrong. Please try again later.");
            return redirect()->back();
        }
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
            $this->activity()
                ->event("User")
                ->eventType(ActivityEventType::DELETED)
                ->isAdmin()
                ->description("User deleted")
                ->properties([
                    "data" => $user
                ])->save();
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
        try {
            $user = User::find($id);
            $roles = Role::get(); //get all roles
            $userRoles = $user->roles->pluck('id')->toArray();
            //dd($roles);
            $allSession = SessionTable::with([
                'user.roles',
                'hotel',
                'pdInfo' => fn($query) => $query->where('user_id', $id),
            ])
                ->selectRaw("id, users_id, connected_devices, access_token, device_id,
        SUBSTRING_INDEX(mac_address, '#~', 1) as device_name,device_title,device_model,
        build_version,device_os_version,zipcode,
        created_on,
        JSON_UNQUOTE(JSON_EXTRACT(device, '$.useragent')) as useragent")
                ->where('status', 1)
                ->where('users_id', $id)
                ->orderBy('connected_devices', 'desc')
                ->get();
            if ($allSession->count() <= 0) {
                toast('No active device.', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $totalRooms = HotelRoomTable::where('user_id', $id)->count();
            // return response()->json(['success' => true, "datalist" => $allSession]);
            return view('admin.users.connected', compact('allSession', 'totalRooms'));
        } catch (\Throwable $th) {
            //throw $th;
            toast('Something went wrong ! try again later.', 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        }
    }

    public function connectedDeviceNew_08_09_26($id, Request $request)
    {

        // $session =   SessionTable::where('users_id', $id)->latest('created_on')->first();

        // // return response()->json($session);
        // $payload = JWTAuth::setToken($session->access_token)->getPayload();

        // $createdAt = Carbon::createFromTimestamp($payload->get('iat'));
        // $expiresAt = Carbon::createFromTimestamp($payload->get('exp'));

        // $diff = $createdAt->diff($expiresAt);
        // $expireInDays = max(0, now()->diffInDays($expiresAt, false));
        // return response()->json([
        //     'created_at' => $createdAt->toDateTimeString(),
        //     'expires_at' => $expiresAt->toDateTimeString(),
        //     'duration' => "{$diff->m} months {$diff->d} days",
        //     // Total token duration
        //     'total_days' => $createdAt->diffInDays($expiresAt),
        //     'remaining_days' => $expireInDays,
        // ]);

        try {
            $user = User::find($id);
            $roles = Role::get(); //get all roles
            $userRoles = $user->roles->pluck('id')->toArray();
            $query = SessionTable::query();
            $query->with([
                'user.roles',
                'hotel',
                'pdInfo' => fn($query) => $query->where('user_id', $id),
                'subSession'
            ]);
            $query->selectRaw("*,
        SUBSTRING_INDEX(mac_address, '#~', 1) as device_name,
        JSON_UNQUOTE(JSON_EXTRACT(device, '$.useragent')) as useragent")
                ->where('users_id', $id);
            // ->where('status', 1);


            if (!empty($request->search) && strtolower($request->search) != 'web') {


                $query->whereHas('pdInfo', function ($query) use ($request) {
                    $query->where('personal_devicename', 'like', '%' . $request->search . '%')
                        ->orWhere('platform', 'like', '%' . $request->search . '%')
                        ->orWhere('device_model', 'like', '%' . $request->search . '%')
                        ->orWhere('build_version', 'like', '%' . $request->search . '%')
                        ->orWhere('zipcode', 'like', '%' . $request->search . '%');
                });



                $query->orderBy('created_on', 'desc');
                $allSession = $query->get();
            } elseif (!empty($request->search) && strtolower($request->search) == 'web') {
                $query->orderBy('created_on', 'desc');
                $allSession = $query->get();
                if (!empty($request->search) && strtolower($request->search) == 'web') {
                    $allSession = $allSession->filter(function ($item) {
                        return strtolower($item->device_type) === 'web';
                    })->values();
                }
            } else {
                // $query->orderBy('created_on', 'desc');
            }

            $allSession = $query
                ->orderBy('status', 'desc')       // status = 1 first
                ->orderBy('created_on', 'desc')   // latest first within status
                ->get();

            $totalRooms = HotelRoomTable::where('user_id', $id)->count();

            // return response()->json($allSession);

            if ($request->ajax()) {
                return view('admin.users.partials.connected-table', compact('allSession', 'totalRooms'));
            }


            if ($allSession->count() <= 0) {
                toast('No active device.', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            // return response()->json(['success' => true, "datalist" => $allSession]);
            return view('admin.users.connected-new', compact('allSession', 'totalRooms'));
        } catch (\Throwable $th) {
            // throw $th;
            toast('Something went wrong ! try again later.', 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function connectedDeviceNew($id, Request $request)
    {
        try {
            $user = User::find($id);

            $roles = Role::get();
            $userRoles = $user->roles->pluck('id')->toArray();

            $query = SessionTable::query();

            $query->with([
                'user.roles',
                'hotel',
                'pdInfo' => fn($query) => $query->where('user_id', $id),
                'subSession'
            ]);

            $query->selectRaw("
            *,
            SUBSTRING_INDEX(mac_address, '#~', 1) as device_name,
            JSON_UNQUOTE(JSON_EXTRACT(device, '$.useragent')) as useragent
        ")->where('users_id', $id);

            /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

            $query->filterSearch($request->search)
                ->filterStatus($request->status);

            /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

            $perPage = $request->get('per_page', 10);

            $allSession = $query
                ->orderBy('status', 'desc')
                ->orderBy('created_on', 'desc')
                ->paginate($perPage)
                ->withQueryString();

            $totalRooms = HotelRoomTable::where('user_id', $id)->count();

            /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

            // return response()->json($allSession);
            if ($request->ajax()) {
                return view(
                    'admin.users.partials.connected-table',
                    compact('allSession', 'totalRooms')
                );
            }

            if ($allSession->total() <= 0) {
                toast('No active device.', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);

                return redirect()->back();
            }

            return view(
                'admin.users.connected-new',
                compact('allSession', 'totalRooms')
            );
        } catch (\Throwable $th) {

            toast('Something went wrong! Try again later.', 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            // Alert::error('error', $th->getMessage());
            return redirect()->back();
        }
    }


    public function removeDeviceName($id)
    {
        DB::beginTransaction();
        try {
            $device =  PersonalDevice::find($id);
            if ($device == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);

                return redirect()->back();
            }
            $device->delete();
            DB::commit();
            toast('Device name removed successfully  !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);

            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            toast('Something went wrong! Try again later.', 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            // Alert::error('error', $th->getMessage());
            return redirect()->back();
        }
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
            "id.*" => "required|numeric|distinct",

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

    public function destroyUserSessionById($id)
    {
        DB::beginTransaction();
        try {

            $session = SessionTable::where("id", $id)->first();
            if ($session == null) {
                toast("Session not found !")->timerProgressBar()->autoClose(3000);
                return redirect()->back();
            }
            $sessionCount = SessionTable::where("users_id", $session->users_id)->count();
            $this->activity()
                ->effectedUser($session->users_id)
                ->event("Session")
                ->eventType(ActivityEventType::REMOVED)
                ->isAdmin()
                ->description("User session removed")
                ->properties(["session" => $session])
                ->save();
            JWTAuth::setToken($session->access_token)->invalidate();
            $session->delete();

            toast("Session removed successfully !")->timerProgressBar()->autoClose(3000);

            // return response()->json($sessionCount);
            if ($sessionCount == 1) {
                DB::commit();
                return redirect()->route('admin.users');
            }
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            toast("Something went wrong ! Try again later.")->timerProgressBar()->autoClose(3000);
            return redirect()->back();
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
            $query->where('roles.name', 'like', '%' . $searchroleValue . '%');
        })->select('count(*) as allcount')->where('email', 'like', '%' . $searchValue . '%')->count();
        $totalRecordswithFilter = User::whereHas('roles', function ($query) use ($searchroleValue) {
            $query->where('roles.name', 'like', '%' . $searchroleValue . '%');
        })->select('count(*) as allcount')
            ->where('email', 'like', '%' . $searchValue . '%')->count();

        // Fetch records
        $records = User::whereHas('roles', function ($query) use ($searchroleValue) {
            $query->where('roles.name', 'like', '%' . $searchroleValue . '%');
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
            "id.*" => "required|numeric|distinct",
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

    public function changeStatus($id)
    {

        DB::beginTransaction();
        try {
            $this->activity()
                ->event("User")
                ->isAdmin();


            $user = User::find($id);
            if ($user == null) {

                toast("user not found !", 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect()->back();
            }
            if ($user->is_active == 1) {
                $user->is_active = 0;
                $this->eventType(ActivityEventType::STATUSDISABLE)
                    ->description("User disable")
                    ->properties([
                        "data" => $user
                    ])->save();
            } else {
                $user->is_active = 1;
                $this->eventType(ActivityEventType::STATUSENABLE)
                    ->description("User enable")
                    ->properties([
                        "data" => $user
                    ])->save();
            }
            $user->save();
            DB::commit();
            toast("User status changed successfully", 'success')
                ->autoClose(3000)
                ->timerProgressBar();
            return redirect()->back()->with('remove', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Status change failed for user ID {$id}: " . $th->getMessage());

            Alert::error('Oop !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroyById($id)
    {

        DB::beginTransaction();
        try {
            $user = User::find($id);
            if ($user == null) {
                toast("user not found !", 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect()->back();
            }
            DB::table('outside_location_plays')->where('users_id', $user->id)->delete();

            DB::table('session_table')->where('users_id', $user->id)->delete();
            DB::table('user_zipcode_rules')->where('email', $user->email)->delete();

            $user->roles;
            $user->subscriptions;
            $this->activity()
                ->event("User")
                ->eventType(ActivityEventType::DELETED)
                ->isAdmin()
                ->description("User deleted")
                ->properties([
                    "data" => $user
                ])->save();

            $user->delete();




            DB::commit();
            toast("user deleted successfully !", 'success')
                ->autoClose(3000)
                ->timerProgressBar();
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Status change failed for user ID {$id}: " . $th->getMessage());

            Alert::error('Oop !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function customUserList(Request $request)
    {

        $query = User::query()
            ->with('roles', 'customSubscription')
            ->where('is_custom_user', 1);
        if ($request->sort_subscribe_at == null || $request->sort_subscribe_at == 'desc') {
            $query->orderBy(
                Subscription::select('updated_at')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->limit(1),
                'desc'
            );
        } else {
            $query->orderBy(
                Subscription::select('updated_at')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->limit(1),
                'asc'
            );
        }

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
        // $query->orderByDesc("updated_at");
        $users = $query->paginate(10)->appends($request->query());

        // $users =   $query->paginate(10);
        // return response()->json($users);
        $roles = Role::all();
        if ($request->ajax()) {
            return view('admin.users.partials.custom-table', compact('users', 'roles'));
        }
        return view('admin.users.custom', compact('users', 'roles'));
    }

    public function bulkAction(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:1',
        ], [
            'user_ids.required' => 'Please select at least one userx.',
            'user_ids.array' => 'Invalid data format for selected user.',
            'user_ids.min' => 'You must select at least one user before proceeding.',
        ]);
        if ($validator->fails()) {
            toast($validator->errors()->first(), "error")
                ->timerProgressBar()
                ->autoClose(5000);
            return redirect()->back();
        }

        DB::beginTransaction();
        switch ($request->action) {
            case 'activate':

                try {
                    User::whereIn('id', $request->user_ids)->update(['is_active' => 1]);


                    $this->activity()
                        ->event("User")
                        ->eventType(ActivityEventType::STATUSENABLE)
                        ->isAdmin()
                        ->description("Multiple user active")
                        ->properties([
                            "data" => User::whereIn('id', $request->user_ids)->get()
                        ])->save();
                    DB::commit();
                    toast("User(s) activated successfully !", "success")
                        ->timerProgressBar()
                        ->autoClose(5000);
                    return redirect()->back()->with('remove-all', true);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::success("Error ", "Something went wrong !");
                    return redirect()->back();
                }

            case 'inactivate':
                try {
                    User::whereIn('id', $request->user_ids)->update(['is_active' => 0]);

                    $this->activity()
                        ->event("User")
                        ->eventType(ActivityEventType::STATUSDISABLE)
                        ->isAdmin()
                        ->description("Multiple user inactive")
                        ->properties([
                            "data" => User::whereIn('id', $request->user_ids)->get()
                        ])->save();
                    DB::commit();
                    toast("User(s) inactivate successfully !", "success")
                        ->timerProgressBar()
                        ->autoClose(5000);
                    return redirect()->back()->with('remove-all', true);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::success("Error ", "Something went wrong !");
                    return redirect()->back();
                }

            case "delete":
                try {

                    $this->activity()
                        ->event("User")
                        ->eventType(ActivityEventType::DELETED)
                        ->isAdmin()
                        ->description("Multiple user deleted")
                        ->properties([
                            "data" => User::whereIn('id', $request->user_ids)->get()
                        ])->save();
                    $users = User::whereIn('id', $request->user_ids)->get();
                    foreach ($users as $user) {
                        DB::table('outside_location_plays')->where('users_id', $user->id)->delete();
                        DB::table('session_table')->where('users_id', $user->id)->delete();
                        DB::table('user_zipcode_rules')->where('email', $user->email)->delete();
                        $user->delete();
                    }

                    DB::commit();
                    toast("User(s) deleted successfully !", "success")
                        ->timerProgressBar()
                        ->autoClose(5000);
                    return redirect()->back()->with('remove-all', true);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::success("Error ", "Something went wrong !");
                    return redirect()->back();
                }

            case "session":


                try {

                    $this->activity()
                        ->event("Session")
                        ->eventType(ActivityEventType::REMOVED)
                        ->isAdmin()
                        ->description("Multiple session removed")
                        ->properties([
                            "data" => SessionTable::whereIn("id", $request->user_ids)->get()
                        ])->save();
                    foreach ($request->user_ids as $id) {
                        $session = SessionTable::where("id", $id)->first();
                        JWTAuth::setToken($session->access_token)->invalidate();
                        $session->delete();
                    }


                    DB::commit();
                    toast("Session removed successfully !", "success")->timerProgressBar()->autoClose(3000);
                    return redirect('admin/users');
                } catch (\Throwable $th) {
                    DB::rollBack();
                    toast("Something went wrong ! Try again later.")->timerProgressBar()->autoClose(3000);
                    return redirect()->back();
                }
            default:

                toast("Please select any action", "error")
                    ->timerProgressBar()
                    ->autoClose(5000);
                return redirect()->back();
        }
    }

    public function exportUser(Request $request, $type)
    {

        $filters = $request->all();
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

    public function exportUserSessionDevice($id, $type, Request $request)
    {


        $filters = $request->only(['role']);
        // dd($filters);
        $filename = 'users-sessions-device_' . now()->format('d-m-Y_H-i-s') . '.' . $type;
        return Excel::download(new UserSessionDeviceExport($id), $filename);
    }

    public function exportCustomUser(Request $request, $type)
    {

        $filters = $request->only(['role']);
        // dd($filters);
        $filename = 'custom-users_' . now()->format('d-m-Y_H-i-s') . '.' . $type;
        return Excel::download(new CustomUserExport($filters), $filename);
    }
}
