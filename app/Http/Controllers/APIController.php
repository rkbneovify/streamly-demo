<?php

namespace App\Http\Controllers;

use App\Enums\ActivityEventType;
use App\HoaUser;
use App\HotelRoomTable;
use App\Mail\DeleteAccount;
use App\SessionTable;
use App\Traits\ActivityLogger;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use JWTAuth;
use Jenssegers\Agent\Agent;
use App\Models\JwtToken;
use Illuminate\Http\Request;
use App\User;
use App\Models\Role;
use Crypt;
use Hash;
use Mail;
use Carbon\Carbon;
use Validator;
use App\Models\Channel;
use Illuminate\Support\Facades\DB;
use App\Models\Genre;
use App\Models\RecentChannel;
use App\Models\FrontendText;
use App\Models\Gracenote;
use App\Models\Subscription;
use App\Setting;
use Exception;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class APIController extends Controller
{
    use ActivityLogger;
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;
    public $exculdedUsers = ["jmheil@gmail.com", "llipschultz@neovix.com", "brian_marlin@yahoo.com", "qasuresh@yopmail.com", "qajalaj@yopmail.com", "pm13426@gmail.com", "nathan@neovix.com", "willis@neovix.com", "davea@neovix.com"];
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register', 'verify', 'support', 'privacypol', 'termofuse', 'qrcodeapi', 'unhashid', 'qrvisibility', 'connecteddeviceemail']]);
        $this->middleware('subscribed')->only('genrename', 'myRecents', 'myFavorites', 'addToRecent', 'connecteddevice');
    }

    public function index()
    {
        $users = User::all();
        $data = $users->toArray();

        $response = [
            'success' => true,
            'response_code' => 200,
            'data' => $data,
            'message' => 'User retrieved successfully.'
        ];

        return response()->json($response, 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $data = $user->toArray();

        if (is_null($user)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'User not found.',
                'response_code' => 404,
            ];
            return response()->json($response, 404);
        }


        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'User retrieved successfully.',
            'response_code' => 200,
        ];

        return response()->json($response, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required',
            'first_name' => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors(),
                'response_code' => 404,
            ];
            return response()->json($response, 404);
        }
        $updated = $user->fill($request->all())->save();


        $data = $user->toArray();

        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'User updated successfully.',
            'response_code' => 200,
        ];

        return response()->json($response, 200);
    }






    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);


        if (empty(request('email')) || empty(request('password'))) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'both email and password required'], 401);
        }

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'Invalid credentials'], 401);
        }
        $user = JWTAuth::user(); //all the user info
        // if($user->email_verified_at==null){
        //     return response()->json(['success'=>false,'message' => 'Email not verfied, verify your email first.'], 401);
        // }

        if ($user->is_active == 0) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'You are deactivated, kindly contact admin.', 'deleted_at' => $user->deleted_at], 401);
        }

        $uid = $user['id'];
        $this->authenticated($request, $user, $token);

        checkLoginLimit($uid, $token);
    }
    public function unhashid($id)
    {

        $User = UnHashUserID($id);
        ;
        if ($User) {
            $response = array('status' => true, 'message' => "id unhashed retrieved.", 'data' => $User);
            return response()->json($response, 200);
        } else {
            $meta = array('status' => false, 'message' => 'Id unhashed found.');
            $messages = array('data' => array(), 'meta' => $meta);
            return response()->json($messages, 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        // $this->validate($request, [
        //    // 'token' => 'required'
        // ]);
        $token = $request->only('token');

        $acctoken = SessionTable::where([
            ['status', '=', '1'],
            ['access_token', '=', $token],
        ])->get()->toArray();
        //print_r($acctoken); exit;

        try {  //$user = JWTAuth::user();//all the user info
            //$uid=  $user['id'];
            //JWTAuth::invalidate($request->token);
            if ($token == '') {
                //   echo 'user cant be logout';
                return response()->json([
                    'success' => false,
                    'message' => 'the user cannot be logged out because reqired token is not provided',
                    'response_code' => 401,

                ], 401);
            } else if ((count($acctoken) == 0)) {
                $response = [
                    'success' => false,
                    'data' => 'Empty',
                    'message' => 'access token not found.',
                    'response_code' => 404,
                ];
                return response()->json($response, 404);
            } else {

                $id = $acctoken[0]['id'];
                LogoutandDecrementDevice($id);
            }
        } catch (JWTException $exception) {
            //echo 'user cant be logout2';
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
                'error' => $exception,
                'response_code' => 500,

            ], 500);
        }
    }



    protected function authenticated($request, $user, $token)
    {
        $olduser = $user;
        $user->last_login = date("Y-m-d H:i:s");
        $user->save();

        $agent = new Agent();

        $isDesktop = $agent->isDesktop();
        $isPhone = $agent->isPhone();
        $jwtToken = new JwtToken();
        $jwtToken->user_id = $user->id;
        $jwtToken->token = $token;
        $jwtToken->browser = $agent->browser();
        ;
        $jwtToken->platform = $agent->platform();
        $jwtToken->device = $agent->device();
        $mobileHeader = $request->header('x_platform');
        if (isset($mobileHeader) && $mobileHeader == 'mobile') {
            JwtToken::where('user_id', $user->id)->where('phone', 1)->delete();

            $jwtToken->phone = 1;
            $jwtToken->save();
        } else {
            JwtToken::where('user_id', $user->id)->where('desktop', 1)->delete();

            $jwtToken->desktop = 1;
            $jwtToken->save();
        }
    }

    public function connecteddevice()
    {
        $user = JWTAuth::user(); //all the user info
        $uid = $user['id'];
        //and get the number of last connected device
        $userRole = $user->roles->first()->name;
        if ($userRole == 'tieruser') {
            $prevConnectDevicecount = SessionTable::with([
                'hotel:id,hotel_room,session_id',
                'pdname' => function ($q) use ($uid) {
                    // Query the name field in status table
                    $q->select('id', 'personal_devicename', 'device_id', 'user_id')->where('user_id', '=', $uid);
                }
            ])
                ->select('user_ip', 'logintime')
                ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
                ->where([
                    ['status', '=', '1'],
                    ['users_id', '=', $uid],
                ])->orderBy('connected_devices', 'asc');
            $s = '';
            $numofdevice = '';
            $s = $prevConnectDevicecount->get()->makeHidden('pdname')
                ->map(function ($address) {

                    $address['session_id'] = HashUserID($address['id']);
                    if (is_null($address->hotel)) {
                        $address['hotel_room'] = null;
                    } else {
                        $address['hotel_room'] = $address->hotel->hotel_room;
                    }

                    $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? 'empty';
                    $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);
                    unset($address['hotel']);
                    unset($address['device']);
                    unset($address['id']);
                    unset($address['pdname']);

                    return $address;
                });
        } else {
            $prevConnectDevicecount = SessionTable::with([
                'pdname' => function ($q) use ($uid) {
                    // Query the name field in status table
                    $q->select('id', 'personal_devicename', 'device_id', 'user_id')
                        ->where('user_id', '=', $uid);
                }
            ])
                ->where([
                    ['status', '=', '1'],
                    ['users_id', '=', $uid],
                ])
                ->select('user_ip', 'logintime')
                ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
                ->orderBy('connected_devices', 'asc');
            $s = '';
            $numofdevice = '';
            $s = $prevConnectDevicecount->get()->makeHidden('pdname')
                ->map(function ($address) {

                    $address['session_id'] = HashUserID($address['id']);

                    $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? 'empty';
                    $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);
                    unset($address->pdname);
                    unset($address['device']);
                    unset($address['id']);
                    return $address;
                });
        }
        $numofdevice = $prevConnectDevicecount->count();

        //  dd($s);
        $response = array('success' => true, 'response_code' => 200, 'numofdevice' => $numofdevice, 'userDeviceLimit' => GetLimitForUser($uid), 'message' => 'Connected device found successfully', 'data' => $s);
        return response()->json($response, 200);
    }


    // new code show/hide connected deviceapi for andriod api


    public function newconnecteddevice()
    {
        $user = JWTAuth::user(); // All the user info
        $uid = $user->id;
        $userRole = $user->roles->first()->name;

        // Fetch `connected_device` from users table
        $userConnectedDevice = $user->connected_device;
        $hoaUser = HoaUser::getCurrentSubUserByToken();
        if ($hoaUser == null) {


            if ($userRole == 'tieruser') {
                $prevConnectDevicecount = SessionTable::with([
                    'hotel:id,hotel_room,session_id',
                    'pdname' => function ($q) use ($uid) {
                        $q->select('id', 'personal_devicename', 'device_id', 'user_id')
                            ->where('user_id', '=', $uid);
                    }
                ])
                    ->select('user_ip', 'logintime')
                    ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id, SUBSTRING_INDEX(mac_address, '#~', 1) device_name, device")
                    ->where([

                        ['users_id', '=', $uid],
                    ])
                    ->where('status', 1)
                    ->orderBy('connected_devices', 'asc');

                $s = $prevConnectDevicecount->get()->makeHidden('pdname')->map(function ($address) {
                    $address['session_id'] = HashUserID($address['id']);
                    $address['hotel_room'] = $address->hotel ? $address->hotel->hotel_room : null;
                    $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? null;
                    $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);

                    // ✅ Fix logintime format (remove microseconds)
                    if (!empty($address['logintime'])) {
                        $address['logintime'] = Carbon::parse($address['logintime'])->setTimezone('UTC')->toISOString();
                    }


                    unset($address['hotel'], $address['device'], $address['id'], $address['pdname']);
                    return $address;
                });


                $numofdevice = $prevConnectDevicecount->count();



                // Final response
                $response = [
                    'success' => true,
                    'response_code' => 200,
                    'numofdevice' => $numofdevice,
                    'userDeviceLimit' => HotelRoomTable::where('user_id', $user->id)
                        ->count(),
                    'user_connected_device' => $userConnectedDevice,
                    'message' => 'Connected device found successfully',
                    'data' => $s,
                ];

                return response()->json($response, 200);


            } elseif ($userRole == 'hoa') {
                $prevConnectDevicecount1 = SessionTable::with([
                    'pdname' => function ($q) use ($uid) {
                        $q->select('id', 'personal_devicename', 'device_id', 'user_id')
                            ->where('user_id', '=', $uid);
                    }
                ])
                    ->where([
                        ['status', '=', 1],
                        ['users_id', '=', $uid],
                    ])
                    ->whereNull('passcode')
                    ->select('user_ip', 'logintime')
                    ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id, SUBSTRING_INDEX(mac_address, '#~', 1) device_name, device")
                    ->orderBy('connected_devices', 'asc');

                $prevConnectDevicecount2 = SessionTable::with([
                    'pdname' => function ($q) use ($uid) {
                        $q->select('id', 'personal_devicename', 'device_id', 'user_id')
                            ->where('user_id', '=', $uid);
                    }
                ])
                    ->where([
                        ['status', '=', 1],
                        ['users_id', '=', $uid],
                        ['passcode', '=', HashUserID($uid)],
                    ])
                    ->whereNotNull('passcode')
                    ->select('user_ip', 'logintime')
                    ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id, SUBSTRING_INDEX(mac_address, '#~', 1) device_name, device")
                    ->orderBy('connected_devices', 'asc');

                $prevConnectDevicecount = $prevConnectDevicecount1->get()
                    ->merge($prevConnectDevicecount2->get())
                    ->values();

                $sessions = $prevConnectDevicecount
                    ->makeHidden('pdname')
                    ->map(function ($address) {
                        $address['session_id'] = HashUserID($address['id']);
                        $address['personaldevicename'] = $address->pdname->personal_devicename ?? $address['device']['personaldevicename'] ?? null;
                        $address['personaldeviceid'] = HashUserID($address->pdname->id ?? $address['id']);

                        if (!empty($address['logintime'])) {
                            $address['logintime'] = Carbon::parse($address['logintime'])->setTimezone('UTC')->toISOString();
                        }

                        unset($address['pdname'], $address['device'], $address['id']);
                        return $address;
                    });

                $numofdevice = $prevConnectDevicecount->count();



                // Final response
                $response = [
                    'success' => true,
                    'response_code' => 200,
                    'numofdevice' => $numofdevice,
                    'userDeviceLimit' => GetLimitForUser($uid),
                    'user_connected_device' => $userConnectedDevice,
                    'message' => 'Connected device found successfully',
                    'data' => $sessions,
                ];

                return response()->json($response, 200);
            } else {
                $prevConnectDevicecount = SessionTable::with([
                    'pdname' => function ($q) use ($uid) {
                        $q->select('id', 'personal_devicename', 'device_id', 'user_id')
                            ->where('user_id', '=', $uid);
                    }
                ])
                    ->where([
                        ['status', '=', '1'],
                        ['users_id', '=', $uid],
                    ])
                    ->select('user_ip', 'logintime')
                    ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id, SUBSTRING_INDEX(mac_address, '#~', 1) device_name, device")
                    ->orderBy('connected_devices', 'asc');

                $s = $prevConnectDevicecount->get()->makeHidden('pdname')->map(function ($address) {
                    $address['session_id'] = HashUserID($address['id']);
                    $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? null;
                    $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);

                    // ✅ Fix logintime format (remove microseconds)
                    if (!empty($address['logintime'])) {
                        $address['logintime'] = Carbon::parse($address['logintime'])->setTimezone('UTC')->toISOString();
                    }

                    unset($address['pdname'], $address['device'], $address['id']);
                    return $address;
                });
            }

            $numofdevice = $prevConnectDevicecount->count();



            // Final response
            $response = [
                'success' => true,
                'response_code' => 200,
                'numofdevice' => $numofdevice,
                'userDeviceLimit' => GetLimitForUser($uid),
                'user_connected_device' => $userConnectedDevice,
                'message' => 'Connected device found successfully',
                'data' => $s,
            ];

            return response()->json($response, 200);
        }


        $sessions = HoaUser::getSubUserActiveSessions($hoaUser);
        // unset($sessions['device']);
        $response = [
            'success' => true,
            'response_code' => 200,
            'numofdevice' => count($sessions),
            'userDeviceLimit' => $hoaUser->device_limit,
            'user_connected_device' => $userConnectedDevice,
            'message' => 'Connected device found successfully',
            'data' => $sessions,
        ];

        return response()->json($response, 200);


    }




    public function support()
    {
        $frontendjson = FrontendText::find(1)->frontendjson;
        $supportemail = $frontendjson['supportemail'] ?? 'support@streamlytv.com';
        $supportphone = $frontendjson['supportphone2'] ?? '6022450986';
        //$supportphone2= $frontendjson['supportphone']??'1234656477';
        $message = $frontendjson['supportapitext'] ?? "Our team is available 24*7. We'll do our best to respond to your inquiries as soon as possible.\n  \n Need help or have a question? Our support team is here to assist you. You can reach us at the following:";
        $supportnumber = [];
        $supportnumber = ['email' => $supportemail, 'num1' => $supportphone];
        $response = array('success' => true, 'response_code' => 200, 'data' => $supportnumber, 'message' => $message);
        return response()->json($response, 200);
    }
    public function privacypol()
    {
        $breakingpoint = '#~';
        // echo json_encode( explode("\r\n",file_get_contents('data.txt')) );
        $file = file_get_contents('PrivacyPolicy.txt');
        $response = array('success' => true, 'response_code' => 200, 'data' => $file, 'message' => 'Privacy policy');
        return response()->json($response, 200);
    }

    public function termofuse()
    {
        $breakingpoint = '#~';
        // echo json_encode( explode("\r\n",file_get_contents('data.txt')) );
        $file = file_get_contents('Termofuse.txt');
        $response = array('success' => true, 'response_code' => 200, 'data' => $file, 'message' => 'Term of use');
        return response()->json($response, 200);
    }

    public function genrename(Request $request)
    {
        $user = JWTAuth::user();
        $ua = $request->server('HTTP_USER_AGENT');
        $validate = 0;
        $zipcode = $request->get('zipcode');
        if (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode'))
            $validate = 1;
        $isDevice = isDeviceAppleRokuOthers($ua);
        if ($isDevice == 'apple') {
            $type = 'url';
            $record = $this->genrenameFunc($type, $validate);
            $data = $record->get();
        } else {
            $type = 'dash_url';
            $record = $this->genrenameFunc($type, $validate);
            $data = $record->get();
        }

        if ($validate) {
            $a = $data->groupBy('genre_name')->keys();
            foreach ($data as $key => $res) {

                if ($res->zip_code !== ["All"]) {
                    $check = in_array($zipcode, $res->zip_code);
                    if (!$check) {
                        unset($data[$key]);
                    } else {
                        unset($data[$key]['zip_code']);
                    }
                } else {
                    unset($data[$key]['zip_code']);
                }
            }
            $data = json_decode(json_encode($data), true);
            $data = array_values($data);
            $data = $this->groupBy($data, 'genre_name');
        } else {
            $data = $data->groupBy('genre_name');
            $a = $data->keys();
        }

        $response = [
            'success' => true,

            'allGenre' => $a,
            'data' => $data,
            'message' => 'Channel with genre feteched successfully.',
            'response_code' => 200,
        ];
        return response()->json($response, 200);
    }

    public function groupBy($array, $key)
    {
        return array_reduce($array, function ($result, $item) use ($key) {
            $result[$item[$key]][] = $item;
            return $result;
        }, []);
    }

    public function genrenameFunc($type, $validate = 0)
    {

        if (!$validate) {
            $second = Channel::select(
                'channels.id',
                DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) callSign'),
                'channels.image',
                "channels.$type as url",
                DB::raw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
            )
                ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')

                ->where('channels.status', '=', '1')
                ->orderBy('genre_name', 'asc');
        } else {
            $second = Channel::select(
                'channels.id',
                'channels.zip_code',
                DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) callSign'),
                'channels.image',
                "channels.$type as url",
                DB::raw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
            )
                ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')

                ->where('channels.status', '=', '1')
                ->orderBy('genre_name', 'asc');
        }

        return $second;
    }

    public function favoriteChannel(Request $request)
    {
        try {
            $input = $request->all();
            $channelid = $request->input('channelid');
            JWTAuth::user()->favorites()->syncWithoutDetaching($channelid);
            $response = [
                'success' => true,
                'data' => 'Channel id ' . $channelid . ' added',
                'message' => 'Successfully added to favourite channel',
                'response_code' => 200,
            ];
            return response()->json($response, 200);
        } catch (Exception $exception) {

            return response()->json([
                'success' => false,
                'message' => 'Some Error occured',
                'error' => $exception->getMessage(),
                'response_code' => 500,

            ], 500);
        }
    }

    /**
     * Unfavorite a particular post
     *
     * @param  Post $post
     * @return Response
     */
    public function unFavoriteChannel(Request $request)
    {
        try {
            $input = $request->all();
            $channelid = $request->input('channelid');
            JWTAuth::user()->favorites()->detach($channelid);
            $response = [
                'success' => true,
                'data' => 'Channel id ' . $channelid . ' removed',
                'message' => 'Successfully removed from favourite channel list',
                'response_code' => 200,
            ];
            return response()->json($response, 200);
        } catch (Exception $exception) {

            return response()->json([
                'success' => false,
                'message' => 'Some Error occured',
                'error' => $exception->getMessage(),
                'response_code' => 500,

            ], 500);
        }
    }

    public function myFavorites(Request $request)
    {
        try {
            $ua = $request->server('HTTP_USER_AGENT');
            $isDevice = isDeviceAppleRokuOthers($ua);
            $myFavorites = JWTAuth::user()->favorites;


            if ($isDevice == 'apple') {
                $myFavorites->map(function ($address) {

                    unset($address['dash_url']);
                    unset($address['smooth_url']);
                    unset($address['pivot']);
                    return $address;
                });
            } else {

                $myFavorites->map(function ($address) {

                    unset($address['url']);
                    $address['url'] = $address['dash_url'];
                    unset($address['dash_url']);

                    unset($address['smooth_url']);
                    unset($address['pivot']);
                    return $address;
                });
            }

            $response = [
                'success' => true,
                'data' => $myFavorites->unique('id'),
                'message' => 'My favourites.',
                'response_code' => 200,
            ];
            return response()->json($response, 200);
        } catch (Exception $exception) {

            return response()->json([
                'success' => false,
                'message' => 'Some Error occured',
                'error' => $exception->getMessage(),
                'response_code' => 500,

            ], 500);
        }
    }

    public function addToRecent(Request $request)
    {
        $limit = 10;
        try {
            $id = JWTAuth::user()->id;
            $count = RecentChannel::where('user_id', '=', $id)->count();
            if ($count >= $limit) {
                RecentChannel::where('user_id', '=', $id)->orderBy('created_at', 'desc')->skip(5)->delete();
            }
            $now = Carbon::now('utc')->toDateTimeString();

            $ActiveChannelIds = Channel::toBase()->select('id')->where('status', '=', '1')->pluck('id')
                ->toArray();
            $channelid = $request->input('channelids');
            $channels = explode(',', $channelid);

            $channelIDArrByUser = array_unique($channels, SORT_NUMERIC);
            $channelArr = array_intersect($ActiveChannelIds, $channelIDArrByUser);
            $model = new RecentChannel;



            foreach ($channelArr as $value) {
                $model->updateOrCreate(
                    [
                        'channels_id' => $value,
                        'user_id' => $id
                    ],
                    [
                        'created_at' => $now,
                        'updated_at' => $now
                    ]
                );
            }
            $ua = $request->server('HTTP_USER_AGENT');
            $isDevice = isDeviceAppleRokuOthers($ua);
            $recentschannel = $this->getRecentsChannelUrlForDevice($isDevice);

            $response = [
                'success' => true,
                'data' => $recentschannel,
                'message' => 'Added to recent',
                'response_code' => 200,
            ];

            return response()->json($response, 200);
        } catch (Exception $exception) {

            return response()->json([
                'success' => false,
                'message' => 'Some Error occured',
                'error' => $exception->getMessage(),
                'response_code' => 500,

            ], 500);
        }
    }
    public function getRecentsChannelUrlForDevice($isDevice)
    {

        $recentschannel = JWTAuth::user()->recentschannel;
        if ($isDevice == 'apple') {
            $recentschannel->map(function ($address) {

                unset($address['dash_url']);
                return $address;
            });
        } else {

            $recentschannel->map(function ($address) {


                $address['url'] = $address['dash_url'];
                unset($address['dash_url']);
                return $address;
            });
        }
        return $recentschannel;
    }
    public function myRecents(Request $request)
    {
        try {

            $ua = $request->server('HTTP_USER_AGENT');
            $isDevice = isDeviceAppleRokuOthers($ua);


            $recentschannel = $this->getRecentsChannelUrlForDevice($isDevice);

            foreach ($recentschannel as $value) {
                $value['image'] = 'https://streamlytv.com/uploads/channellogo/' . $value->image . '';
            }

            $response = [
                'success' => true,
                'data' => $recentschannel,
                'message' => 'My Recent.',
                'response_code' => 200,
            ];
            return response()->json($response, 200);
        } catch (Exception $exception) {

            return response()->json([
                'success' => false,
                'message' => 'Some Error occured',
                'error' => $exception->getMessage(),
                'response_code' => 500,

            ], 500);
        }
    }

    public function qrcodeapi()
    {
        //use this to generate svg file and then manually convert that svg to png
        // $qrcode=QrCode::size(250)->generate('http://streamlytv.com/');

        $imagePath = asset("/frontend/img/streamlyQRcode.png");

        $response = [
            'success' => true,
            'data' => $imagePath,
            'message' => 'http://streamlytv.com/',
            'response_code' => 200,
        ];
        return response()->json($response, 200);
    }

    public function qrvisibility()
    {
        $setting = Setting::where('key_name', 'qrvisibility')->first();
        $return = array(
            'visible' => $setting->value == '1' ? true : false
        );
        return response()->json($return);
    }

    public function connecteddeviceemail(Request $request)
    {
        $reqType = $request['type'];
        if (!$reqType) {
            return response()->json('Please enter request type', 200);
        }
        if ($reqType == 'passcode' || 'login') {
            if ($reqType == 'passcode') {

                $passcode = $request->only('passcode');
                $uid = UnHashUserID($passcode['passcode']);
                $user = User::find($uid);

                // $user = JWTAuth::user();//all the user info
                $uid = $user['id'];
                // //and get the number of last connected device
                $userRole = $user->roles->first()->name;
                if ($userRole == 'tieruser') {
                    $prevConnectDevicecount = SessionTable::with([
                        'hotel:id,hotel_room,session_id',
                        'pdname' => function ($q) use ($uid) {
                            // Query the name field in status table
                            $q->select('id', 'personal_devicename', 'device_id', 'user_id')->where('user_id', '=', $uid);
                        }
                    ])
                        ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
                        ->where([
                            ['status', '=', '1'],
                            ['users_id', '=', $uid],
                        ])->orderBy('connected_devices', 'asc');
                    $s = '';
                    $numofdevice = '';
                    $s = $prevConnectDevicecount->get()->makeHidden('pdname')
                        ->map(function ($address) {

                            $address['session_id'] = HashUserID($address['id']);
                            if (is_null($address->hotel)) {
                                $address['hotel_room'] = null;
                            } else {
                                $address['hotel_room'] = $address->hotel->hotel_room;
                            }

                            $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? 'empty';
                            $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);
                            unset($address['hotel']);
                            unset($address['device']);
                            unset($address['id']);
                            unset($address['pdname']);

                            return $address;
                        });
                } else {
                    $prevConnectDevicecount = SessionTable::with([
                        'pdname' => function ($q) use ($uid) {
                            // Query the name field in status table
                            $q->select('id', 'personal_devicename', 'device_id', 'user_id')
                                ->where('user_id', '=', $uid);
                        }
                    ])
                        ->where([
                            ['status', '=', '1'],
                            ['users_id', '=', $uid],
                        ])
                        ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
                        ->orderBy('connected_devices', 'asc');
                    $s = '';
                    $numofdevice = '';
                    $s = $prevConnectDevicecount->get()->makeHidden('pdname')
                        ->map(function ($address) {

                            $address['session_id'] = HashUserID($address['id']);

                            $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? 'empty';
                            $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);
                            unset($address->pdname);
                            unset($address['device']);
                            unset($address['id']);
                            return $address;
                        });
                }
                $numofdevice = $prevConnectDevicecount->count();

                //  dd($s);
                $response = array('success' => true, 'response_code' => 200, 'numofdevice' => $numofdevice, 'message' => 'Connected device found successfully', 'data' => $s);
                return response()->json($response, 200);
            }
            if ($reqType == 'login') {
                $credentials = request(['email', 'password']);
                $userEmail = $request['email'];
                $userEmailID = User::where('email', $userEmail)->first()->id;
                $uid = $userEmailID;
                $user = User::find($uid);
                //$user = JWTAuth::user();//all the user info
                // $uid=  $user['id'];
                //and get the number of last connected device
                $userRole = $user->roles->first()->name;

                //return response()->json($userRole,200);
                if (empty(request('email')) || empty(request('password'))) {
                    return response()->json(['success' => false, 'response_code' => 401, 'message' => 'both email and password required'], 401);
                }

                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['success' => false, 'response_code' => 401, 'message' => 'Invalid credentials'], 401);
                } else {
                    if ($userRole == 'tieruser') {
                        $prevConnectDevicecount = SessionTable::with([
                            'hotel:id,hotel_room,session_id',
                            'pdname' => function ($q) use ($uid) {
                                // Query the name field in status table
                                $q->select('id', 'personal_devicename', 'device_id', 'user_id')->where('user_id', '=', $uid);
                            }
                        ])
                            ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
                            ->where([
                                ['status', '=', '1'],
                                ['users_id', '=', $uid],
                            ])->orderBy('connected_devices', 'asc');
                        $s = '';
                        $numofdevice = '';
                        $s = $prevConnectDevicecount->get()->makeHidden('pdname')
                            ->map(function ($address) {

                                $address['session_id'] = HashUserID($address['id']);
                                if (is_null($address->hotel)) {
                                    $address['hotel_room'] = null;
                                } else {
                                    $address['hotel_room'] = $address->hotel->hotel_room;
                                }

                                $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? 'empty';
                                $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);
                                unset($address['hotel']);
                                unset($address['device']);
                                unset($address['id']);
                                unset($address['pdname']);

                                return $address;
                            });
                    } else {
                        $prevConnectDevicecount = SessionTable::with([
                            'pdname' => function ($q) use ($uid) {
                                // Query the name field in status table
                                $q->select('id', 'personal_devicename', 'device_id', 'user_id')
                                    ->where('user_id', '=', $uid);
                            }
                        ])
                            ->where([
                                ['status', '=', '1'],
                                ['users_id', '=', $uid],
                            ])
                            ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
                            ->orderBy('connected_devices', 'asc');
                        $s = '';
                        $numofdevice = '';
                        $s = $prevConnectDevicecount->get()->makeHidden('pdname')
                            ->map(function ($address) {

                                $address['session_id'] = HashUserID($address['id']);

                                $address['personaldevicename'] = $address->pdname ? $address->pdname->personal_devicename : $address['device']['personaldevicename'] ?? 'empty';
                                $address['personaldeviceid'] = HashUserID($address->pdname ? $address->pdname->id : $address['id']);
                                unset($address->pdname);
                                unset($address['device']);
                                unset($address['id']);
                                return $address;
                            });
                    }
                    $numofdevice = $prevConnectDevicecount->count();

                    //  dd($s);
                    $response = array('success' => true, 'response_code' => 200, 'numofdevice' => $numofdevice, 'message' => 'Connected device found successfully', 'data' => $s);
                    return response()->json($response, 200);
                }
            } else {
                return response()->json('Request Invalid type', 200);
            }
        }
    }

    public function userdelete($id)
    {
        $User = User::find($id);

        if ($User) {
            $UserName = $User['name'];
            $UserEmail = $User['email'];
            if ($User['is_active'] == 1) {
                //     DB::table('users')
                //    ->where('id', $id)
                //    ->update(['is_active' => "0",'deleted_at' => date('Y-m-d H:i:s')]);

                //delete subscriptions
                //    $subscribtionCheck = DB::table('subscriptions')
                //    ->where(['user_id' => $id, 'stripe_status' => 'active'])
                //    ->get();

                //    if(count($subscribtionCheck) > 0){
                //    $User->subscription('main')->skipTrial()->cancelNow();
                //    }
                //delete manage connected devices

                //    DB::table('session_table')
                //    ->where('users_id', $id)
                //    ->where('status', "1")
                //    ->update(['status' => "0"]);

                $response = array('status' => true, 'message' => 'User with account name: ' . $UserName . ' Is Deleted Sucessfully.', 'User_email' => $UserEmail);
                return response()->json($response, 200);
            } else {
                $response = array('status' => false, 'message' => 'User Already Deleted.');
                return response()->json($response, 200);
            }
        } else {
            $response = array('status' => false, 'message' => 'User with Id: ' . $id . ' Does Not Exist.', 'UserID' => $id);
            return response()->json($response, 200);
        }
    }




    public function handleTwoWayDeleteAccount()
    {
        $this->activity()
            ->event("User")
            ->eventType(ActivityEventType::DELETED)
            ->description("User account deleted")
            ->isUser()
            ->isAdmin()
            ->properties([
                "data" => Auth::user()
            ])->save();


        DB::table('subscriptions')
            ->where('user_id', Auth::id())
            ->update(['stripe_status' => "canceled"]);
        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'is_active' => 0,
                'deleted_at' => now()
            ]);

        Mail::to(Auth::user()->email)->send(new DeleteAccount(Auth::user()));
        // invalidate active session
        DB::table('session_table')
            ->where('users_id', Auth::user()->id)
            ->where('status', 1)
            ->get()->map(function ($item) {
                JWTAuth::setToken($item->access_token)->invalidate();

            });
        // delete session
        DB::table('session_table')
            ->where('users_id', Auth::user()->id)->delete();


        DB::commit();

        $response = array('status' => true, 'response_code' => 200, 'message' => "Account deleted successfully !");
        return response()->json($response, 200);
    }
    public function userdeletenew(Request $request)
    {
        DB::beginTransaction();


        try {

            if ($request->user()->is_custom_user != 1) {
                $request->user()->subscription('main')->skipTrial()->cancelNow();



            }

            return $this->handleTwoWayDeleteAccount();

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "status" => false,
                'response_code' => 500,
                "message" => "Something went wrong. Please try again later."
            ], 500);
        }






    }



    public function userdeletenew15_04_2026(Request $request)
    {
        DB::beginTransaction();
        try {
            $User = JWTAuth::user();

            if ($User) {
                $this->activity()
                    ->event("User")
                    ->eventType(ActivityEventType::DELETED)
                    ->description("User account deleted")
                    ->properties([
                        "data" => $request->user()
                    ])->save();

                $UserName = $User['name'];
                $UserEmail = $User['email'];
                $id = $User->id;
                if ($User['is_active'] == 1) {

                    if ($request->has('password')) {
                        // $credentials = ['email' => $User->email, 'password' => $request->password];
                        // $token = JWTAuth::attempt($credentials);
                        // if (!$token) {
                        //     DB::rollBack();
                        //     $response = array('success' => false, 'response_code' => 400, 'message' => 'Wrong old password');
                        //     return response()->json($response, 400);
                        // }
                        try {
                            $subscriptin = Subscription::where('user_id', $User->id)->first();

                            if ($subscriptin == null) {
                                DB::rollBack();
                                return response()->json([
                                    'response_code' => 404,
                                    "status" => false,
                                    "message" => "Subscription not found"
                                ], 404);
                            }

                            if ($subscriptin->livemode == 1) {

                                $User->subscription('main')->skipTrial()->cancelNow();

                                DB::table('subscriptions')
                                    ->where(['user_id' => $id, 'stripe_status' => 'active'])
                                    ->update(['stripe_status' => "canceled"]);
                            } else {
                                $User->subscription('main')->skipTrial()->cancelNow();
                                DB::table('subscriptions')
                                    ->where(['user_id' => $id, 'stripe_status' => 'active'])
                                    ->update(['stripe_status' => "canceled"]);

                            }

                        } catch (Exception $e) {
                            DB::rollBack();
                            return response()->json(['response_code' => 500, 'error' => 'Error: ' . $e->getMessage()], 500);
                        }

                        DB::table('users')
                            ->where('id', $id)
                            ->update(['is_active' => "0", 'deleted_at' => date('Y-m-d H:i:s')]);

                        // $subscribtionCheck = DB::table('subscriptions')
                        //     ->where(['user_id' => $id, 'stripe_status' => 'active'])
                        //     ->get();

                        // try {

                        //     if (count($subscribtionCheck) > 0) {
                        //         $User->subscription('main')->skipTrial()->cancelNow();
                        //     }

                        //     DB::table('subscriptions')
                        //         ->where(['user_id' => $id, 'stripe_status' => 'active'])
                        //         ->update(['stripe_status' => "canceled"]);
                        // } catch (Exception $e) {
                        //     DB::table('subscriptions')
                        //         ->where(['user_id' => $id, 'stripe_status' => 'active'])
                        //         ->update(['stripe_status' => "canceled"]);
                        // }


                        Mail::to($User->email)->send(new DeleteAccount($User));
                        DB::table('session_table')
                            ->where('users_id', $id)
                            ->delete();

                        DB::commit();

                        $response = array('status' => true, 'response_code' => 200, 'message' => 'User with account name: ' . $UserName . ' Is Deleted Sucessfully.', 'User_email' => $UserEmail);
                        return response()->json($response, 200);
                    } else {
                        DB::rollBack();
                        return response()->json(['success' => false, 'response_code' => 400, 'message' => 'password is required to delete'], 400);
                    }
                } else {
                    DB::rollBack();
                    $response = array('status' => false, 'response_code' => 200, 'message' => 'User Already Deleted.');
                    return response()->json($response, 200);
                }
            } else {
                DB::rollBack();
                $response = array('status' => false, 'response_code' => 404, 'message' => 'User not found.');
                return response()->json($response, 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                "status" => false,
                'response_code' => 500,
                "message" => "Something went wrong. Please try again later."
            ], 500);
        }
    }



    public function searchOnPlatform(Request $request)
    {
        $q = $request->q;

        if (!$q) {
            $response = [
                'success' => false,
                'data' => "",
                'message' => 'No data found',
                'response_code' => 404,
            ];
            return response()->json($response, 404);
        }
        $imagePath = url("/uploads/channellogo/");
        $first = Gracenote::select('EventTitle', "StationName", DB::raw("'event' as type"), "CallSign", DB::raw("null as image "), DB::raw("null as genrename "))
            ->where('EventTitle', 'LIKE', '%' . $q . '%');
        $second = Gracenote::select(
            DB::raw("null as EventTitle"),
            DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),
            DB::raw("'channel' as type"),
            DB::raw('IFNULL(gracenote.CallSign,channels.CallSign) CallSign'),
            DB::raw("CONCAT('$imagePath/',channels.`image`) image"),
            DB::raw('IFNULL(genre.name,"Others") genre_name'),
        )
            ->where('gracenote.StationName', 'LIKE', '%' . $q . '%')
            ->orWhere('channels.name', 'LIKE', '%' . $q . '%')
            ->join('channels', function ($q) {
                $q->on('gracenote.CallSign', '=', 'channels.callSign')
                    ->where('channels.status', '=', '1'); //second join condition
            })
            ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
            ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')->distinct('gracenote.CallSign')
            ->union($first)->orderByRaw(1)->take(10)->get();

        $response = [
            'success' => true,
            'data' => $second,
            'message' => 'Search result',
            'response_code' => 200,
        ];
        return response()->json($response, 200);
    }
}
