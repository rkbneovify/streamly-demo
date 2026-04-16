<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ActivityEventType;
use App\HoaUser;
use App\HotelRoomTable;
use App\Mail\DeleteAccountOtp;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Mail\OtpEmail;
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
use Illuminate\Support\Arr;
use App\SessionTable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Memcached;
use DB;
use App\Models\Zipcode;
use App\Models\DeclineZipInquiry;
use App\Models\EmailOtp;
use App\Models\PersonalDevice;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ActivityLogger;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register', 'verify', 'samedevicelogout', 'loginWithPasscode', 'loginEntry', 'loginCheck', 'hoteladminlogin', 'getZipcodes', 'failedzipinquery', 'checkEmail', 'deviceDetail', 'sendOtp', 'verifyOtp']]);
    }

    public function register(Request $request)
    {
        $validate = User::validator($request);

        if ($validate->fails()) {
            $response = array('success' => false, 'response_code' => 400, 'message' => 'Validation error', 'data' => $validate->messages());
            return response()->json($response, 400);
        }

        $role = Role::where('name', 'user')->first();

        $User = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'zipcode' => $request->zipcode,
            'contact' => $request->contact,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'email_verified_at' => null,
            'is_active' => 0
        ]);

        $User->roles()->sync($role->id);

        $verification_code = Crypt::encrypt($User->email);

        $FRONTEND_URL = env('FRONTEND_URL');

        $account_verification_link = $FRONTEND_URL . '/api/auth/verify?token=' . $verification_code;

        $html = '<html>
        Hi, ' . $request['name'] . '<br><br>

        Thank you for registering for ' . env('APP_NAME') . '.<br><br>
        Please click the following link to verify your account.
        <br><br><a href="' . $account_verification_link . '" target=_blank style="  display: block;
        width: 115px;
        height: 25px;
        background: #004E92 0% 0% no-repeat padding-box;
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        line-height: 25px;">Verify</a><br><br>
        Thank you!<br>
        The ' . env('APP_NAME') . ' Team!
    </html>';

        $html2 = '<html>
        Hi, ' . $request['name'] . '<br><br>

        Thank you for registering for ' . env('APP_NAME') . '.<br>
        Please click the following link to verify your account.
        <br><br><a href="' . $account_verification_link . '" target=_blank style="  display: block;
        width: 115px;
        height: 25px;
        background: #004E92 0% 0% no-repeat padding-box;
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        line-height: 25px;">Verify</a><br>
        Your login credentials<br>
        Email: ' . $request->email . '<br>
        Password: ' . $request->password . '<br><br>
        Thank you!<br>
        The ' . env('APP_NAME') . ' Team!
    </html>';

        if ($request->masteruser) {
            Mail::send('emails.general', ["html" => $html2], function ($message) use ($request, $User) {
                $message->to($request->email, $User->name);
                $message->bcc('piyush.mishra@oodles.io')
                    ->subject(env('APP_NAME') . ': Account Verification and Signup Details');
            });
        } else {
            Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $User) {
                $message->to($request->email, $User->name)
                    ->subject(env('APP_NAME') . ': Account Verification');
            });
        }


        $response = array('success' => true, 'response_code' => 200, 'message' => 'You are registered successfully, check email and click on verification link to activate your account.', 'data' => $User);
        return response()->json($response, 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {


        // already login device id



        SessionTable::where("device_id", $request->deviceid)
            ->where('status', 1)
            ->update([
                'status' => 0
            ]);

        $deviceDetails = getDeviceIdforAPI($request);
        $credentials = request(['email', 'password']);

        if (empty(request('email')) || empty(request('password'))) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'both email and password required'], 401);
        }
        if (empty(request('deviceid'))) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'deviceid required'], 401);
        }
        $expirationInMinutes = 259200; //one month in minutes
        JWTAuth::factory()->setTTL($expirationInMinutes);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials', 'response_code' => 401], 401);
        }

        $user = JWTAuth::user();




        if ($user->email_verified_at == null) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'Email not verfied, verify your email first.'], 401);
        }

        if ($user->is_active == 0) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'You are deactivated, kindly contact admin.', 'deleted_at' => $user->deleted_at], 401);
        }
        $userRole = $user->roles->pluck('name');

        if ($user->is_custom_user == 1 && $userRole[0] == 'tieruser') {

            $deviceLimit = GetLimitForUser($user->id);

            $userActiveDeviceCount = SessionTable::where("users_id", $user->id)->where("status", 1)->count();
            if ($userRole[0] == 'tieruser')
                $deviceLimit = HotelRoomTable::where('user_id', $user->id)->count();
            if ($userActiveDeviceCount >= $deviceLimit) {
                return response()->json([
                    'success' => false,
                    'response_code' => 402,
                    'message' => 'Your login limit has been reached. Please contact your service provider to enable further login',

                ], 402);
            }
        }

        $this->activity($user->id)->event("User")
            ->eventType(ActivityEventType::LOGIN)
            ->isUser()
            ->description("User login")
            ->properties(['data' => $user])
            ->save();

        $userRole = $user->roles->pluck('name');
        if ($userRole[0] == 'user' || 'tieruser') {
            $uid = $user['id'];
            checkLoginLimit($uid, $token, $deviceDetails);
            exit;
        }


        $zipcodeRule = DB::table('user_zipcode_rules')
            ->where('email', $user->email)
            ->first();

        $validateFlag = $zipcodeRule ? $zipcodeRule->validate : null;

        $this->authenticated($request, $user, $token, $validateFlag);

        return $this->respondWithToken($token, $validateFlag);
    }


    public function hoteladminlogin(Request $request)
    {
        $deviceDetails = getDeviceIdforAPI($request);
        $credentials = request(['email', 'password']);

        if (empty(request('email')) || empty(request('password'))) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'both email and password required'], 401);
        }
        if (empty(request('deviceid'))) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'deviceid required'], 401);
        }
        $expirationInMinutes = 43800; //one month in minutes
        JWTAuth::factory()->setTTL($expirationInMinutes);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials', 'response_code' => 401], 401);
        }

        $user = JWTAuth::user();

        if ($user->email_verified_at == null) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'Email not verfied, verify your email first.'], 401);
        }

        if ($user->is_active == 0) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'You are deactivated, kindly contact admin.', 'deleted_at' => $user->deleted_at], 401);
        }
        $userRole = $user->roles->pluck('name');
        if ($userRole[0] == 'user' || 'tieruser') {
            $uid = $user['id'];
            checkHoteluserLoginLimit($uid, $token, $deviceDetails);
            exit;
        }
        $this->authenticated($request, $user, $token);
        return $this->respondWithToken($token);
    }

    public function logincheck(Request $request)
    {
        $deviceDetails = getDeviceIdforAPI($request);
        $credentials = request(['email', 'password']);

        if (empty(request('email')) || empty(request('password'))) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'both email and password required'], 401);
        }
        if (empty(request('deviceid'))) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'deviceid required'], 401);
        }
        $expirationInMinutes = 43800; //one month in minutes
        JWTAuth::factory()->setTTL($expirationInMinutes);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials', 'response_code' => 401], 401);
        }

        $user = JWTAuth::user();

        if ($user->email_verified_at == null) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'Email not verfied, verify your email first.'], 401);
        }

        if ($user->is_active == 0) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'You are deactivated, kindly contact admin.', 'deleted_at' => $user->deleted_at], 401);
        }
        $userRole = $user->roles->pluck('name');
        if ($userRole[0] == 'user' || 'tieruser') {
            $uid = $user['id'];
            checkLoginLimitX($uid, $token, $deviceDetails);
            exit;
        }
        $this->authenticated($request, $user, $token);
        return $this->respondWithToken($token);
    }

    public function loginWithPasscode(Request $request)
    {


        // already login device id
        SessionTable::where("device_id", $request->deviceid)
            ->where('status', 1)
            ->update([
                'status' => 0
            ]);

        $isMainUser = true;
        $deviceDetails = getDeviceIdforAPI($request);
        $passcode = $request->only('passcode');
        $uid = UnHashUserID($passcode['passcode']);

        $hoaUser = HoaUser::where(
            'passcode',
            $request->passcode
        )->first();

        if ($hoaUser != null) {
            $isMainUser = false;
            $user = User::find($hoaUser->user_id);

            // hoa main checking
            if ($user == null) {
                return response()->json(['success' => false, 'response_code' => 404, 'message' => 'User not found !'], 404);
            }
            // hoa main user status checking
            if ($user->is_active == 0) {
                return response()->json(['success' => false, 'response_code' => 401, 'message' => 'You are deactivated, kindly contact admin'], 401);

            }
            // hoa sub user checking
            if ($hoaUser->status == 0) {
                return response()->json(['success' => false, 'response_code' => 401, 'message' => 'You are deactivated, kindly contact admin.'], 401);

            }

            //hoa sub user device limit check
            $hoaUserActiveDevices = SessionTable::where('users_id', $hoaUser->user_id)
                ->whereNotNull('passcode')
                ->where("passcode", $hoaUser->passcode)
                ->where("status", 1)->count();

            if ($hoaUserActiveDevices >= $hoaUser->device_limit) {
                $user = User::find($hoaUser->user_id);
                hoaSubUserDeviceLimitCheck($user, $hoaUser->device_limit);
                exit;
            }

            $uid = $hoaUser->user_id;

        }


        $user = User::find($uid);
        if ($user == null) {

            $response = array('success' => false, 'response_code' => 404, 'message' => 'User not found');
            return response()->json($response, 404);
        }
        // $user = User::where('email', '=', $email)->first();

        $expirationInMinutes = 43800; //one month in minutes
        JWTAuth::factory()->setTTL($expirationInMinutes);
        $token = JWTAuth::fromUser($user);
        // $credentials = c;
        // $user=JWTAuth::user();

        if ($user->email_verified_at == null) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'Email not verfied, verify your email first.'], 401);
        }

        if ($user->is_active == 0) {
            return response()->json(['success' => false, 'response_code' => 401, 'message' => 'You are deactivated, kindly contact admin.', 'deleted_at' => $user->deleted_at], 401);
        }


        $userRole = $user->roles->pluck('name');
        //todo hotel check


        $this->activity($user->id)
            ->event("User")
            ->eventType(ActivityEventType::LOGIN);

        if ($isMainUser && $userRole[0] == "hoa") {
            $this->description("Hoa main use login with passcode")
                ->isUser()
                ->properties([
                    "user" => $user
                ])
                ->save();
        } elseif (!$isMainUser && $userRole[0] == "hoa") {
            $houseNo = $hoaUser->house_no ?? "House" . $hoaUser->ordering;
            $this->descriptionSimple("Hoa sub user login with passcode | " . $hoaUser->passcode . " | " . $hoaUser->email . " | " . $houseNo)
                ->isUser()
                ->passcode($hoaUser->passcode)
                ->properties([
                    "user" => $hoaUser
                ])
                ->save();
        } else {
            $this->description("Login with passcode")
                ->isUser()
                ->properties([
                    "user" => $hoaUser
                ])
                ->save();
        }


        if (in_array($userRole[0], ['user', 'tieruser', 'hoa'])) {

            checkLoginLimit($uid, $token, $deviceDetails);
            exit;

        }
        //  hoa main login limit

        // $user_data=array('name'=>$user->name,'email'=>$user->email,'token'=>$token);

        // return response()->json($user_data,200);
    }
    public function verify(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validate->fails()) {
            $response = array('success' => false, 'response_code' => 400, 'message' => 'Validation error', 'data' => $validate->messages());
            return response()->json($response, 400);
        }

        $token = $request->token;

        $verification_token = Crypt::decrypt($request->token);

        $user = User::where('email', $verification_token)->first();
        $email_verified_at = Carbon::now();

        if ($user) {

            if ($user->is_active)
                $alreadyVerify = 1;
            else {
                $alreadyVerify = 0;
                $user->email_verified_at = $email_verified_at;
                $user->is_active = 1;
                $user->save();
            }
            if ($request->wantsJson()) {
                $response = array('success' => true, 'response_code' => 200, 'message' => 'Account successfully verified');
                return response()->json($response, 200);
            } else {
                if (Auth::check()) {
                    $login = true;
                } else {
                    $login = false;
                }
                return view('frontend.verified', compact('login', 'alreadyVerify'));
            }
        } else {
            $response = array('success' => false, 'response_code' => 401, 'message' => 'Invalid verification token');
            return response()->json($response, 401);
        }
    }

    public function changePassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            $response = array('success' => false, 'response_code' => 400, 'message' => 'Validation error', 'data' => $validate->messages());
            return response()->json($response, 400);
        }

        $User = JWTAuth::user();

        if ($request->has('oldpassword')) {
            $credentials = ['email' => $User->email, 'password' => $request->oldpassword];
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                $response = array('success' => false, 'response_code' => 401, 'message' => 'Wrong old password');
                return response()->json($response, 401);
            }
        }

        if ($User) {
            $User->password = Hash::make($request->password);
            $User->save();

            $this->activity()
                ->event("User")
                ->eventType(ActivityEventType::Change)
                ->description("password changed")
                ->isUser()
                ->save();

            $response = array('success' => true, 'response_code' => 200, 'message' => 'Password changed successfully.');
            return response()->json($response, 200);
        } else {
            $response = array('success' => false, 'response_code' => 404, 'message' => 'User not found');
            return response()->json($response, 404);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = JWTAuth::user();

        // use Tymon\JWTAuth\Facades\JWTAuth;
        //     $token = $request->bearerToken();
        // $apy = JWTAuth::getPayload($token)->toArray();

        // 'payload'=>$apy,
        $user_data = array('name' => $user->name, 'email' => $user->email, 'roles' => $user->roles->pluck('name'));

        return response()->json($user_data);
    }

    /**
     * Get the profile data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = JWTAuth::user();
        $response = [
            'success' => true,

            'data' => $user,
            'message' => 'Users Profile',
            'response_code' => 200,
        ];
        return response()->json($response, 200);
        // $user_data=array('name'=>$user->name,'email'=>$user->email,'roles'=>$user->roles->pluck('name'));

        // return response()->json($user_data);
    }


    public function checkSession(Request $request)
    {

        $token = $request->bearerToken();
        $acctoken = SessionTable::where([
            ['status', '=', '1'],
            ['access_token', '=', $token],
        ]);
        if ($acctoken->exists()) {

            $response = [
                'success' => true,
                'status' => true,
                'message' => 'Ok',
                'response_code' => 200,
            ];
            return response()->json($response, 200);
        } else {

            $response = [
                'success' => false,
                'status' => false,
                'message' => 'Token is Invalid',
                'response_code' => 401,
            ];
            return response()->json($response, 401);
        }
    }

    public function tvprofile()
    {
        $user = JWTAuth::user();
        $data = [
            "name" => $user->name,
            "email" => $user->email,
            "created" => $user->created_at,
            "version" => "1.0.0"
        ];
        $response = [
            'success' => true,

            'data' => $data,
            'message' => 'Users Profile',
            'response_code' => 200,
        ];
        return response()->json($response, 200);
        // $user_data=array('name'=>$user->name,'email'=>$user->email,'roles'=>$user->roles->pluck('name'));

        // return response()->json($user_data);
    }


    /**
     * Update the profile data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateprofile(Request $request)
    {
        $loggedInuserinfo = JWTAuth::user();
        $userid = $loggedInuserinfo->id;
        if ($request == '') {
            return response()->json(['success' => false, 'response_code' => 400, 'message' => 'No data sent'], 400);
        }
        $exist = User::whereNotIn('id', [$userid])->where('email', [$request->email])->first();

        if ($exist) {
            $response = array('success' => false, 'response_code' => 400, 'message' => 'Email already exists.');
            return response()->json($response, 400);
        }
        //if email is passed
        if ($request->has('email')) {

            if ($input['lastdigit'] == 2222) {
                $input['email'] = $request->email;
            } else {
                $response = array('success' => false, 'response_code' => 400, 'message' => 'Wrong code entered.');
                return response()->json($response, 400);
            }
        }

        // $validate=$this->validate($request, [
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users,email,'.$userid,


        // ]);
        // if($validate->fails()){
        //     $response = array('status' => false,'message'=>'Validation error','data'=>$validate->messages());
        //     return response()->json($response, 400);
        // }
        $input = $request->only('name', 'email', 'password', 'dob', 'gender', 'country', 'address', 'zipcode', 'contact');
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']); //update the password
        } else {
            $input = Arr::except($input, array('password')); //remove password from the input array
        }
        $user = User::find($userid);

        if ($user) {
            $user->update($input);
            $response = array('success' => true, 'response_code' => 200, 'message' => 'User updated successfully.', 'data' => $user);
            return response()->json($response, 200);
        } else {
            $response = array('success' => false, 'response_code' => 404, 'message' => 'User not found.');
            return response()->json($response, 404);
        }
    }

    /**
     * Log the Adminuser out (Invalidate the token).
     *Log the user out and decrement the device(Invalidate the token).
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {



            $request = request();
            $token = $request->bearerToken(); //get token from header

            $user = JWTAuth::parseToken()->authenticate();



            $userRole = $user->roles->pluck('name');
            //check users role
            $session = SessionTable::with('pdInfo')
                ->where('status', 1)
                ->where('access_token', $request->bearerToken())->first();



            if ($session->is_main_account == 0) {
                $hoauser = HoaUser::where('passcode', $session->passcode)->first();
                $houseNo = $hoauser->house_no ?? "House" . $hoauser->ordering;
                $personalDevice = PersonalDevice::where('device_id', $session->device_id)->latest()->first();


                $this->activity()->event("User")
                    ->eventType(ActivityEventType::LOGOUT)
                    ->descriptionSimple(
                        "Hoa sub user logout<br>" .
                        "Device: " . ($session->device_title ?? explode('#~', $session->mac_address)[0]) . "<br>" .
                        ($session->device_title != null ? ("Device Name: " . $personalDevice->personal_devicename . "<br>") : '') .
                        "Passcode: " . $hoauser->passcode . "<br>" .
                        "Email: " . $hoauser->email . "<br>" .
                        "House No: " . $houseNo
                    )->properties([
                            "data" => $session
                        ])
                    ->passcode($hoauser->passcode)
                    ->isUser()
                    ->save();
            } else {
                $personalDevice = PersonalDevice::where('device_id', $session->device_id)->latest()->first();
                $this->activity()->event("User")
                    ->eventType(ActivityEventType::LOGOUT)
                    ->descriptionSimple(
                        "user logout <br>" .
                        "Device: " . ($session->device_title ?? explode('#~', $session->mac_address)[0]) . "<br>" .
                        ($session->device_title != null ? ("Device Name: " . $personalDevice->personal_devicename . "<br>") : '') .
                        "Email: " . $user->email . "<br>" .
                        "Role: " . $userRole[0]

                    )->properties([
                            "data" => $session
                        ])
                    ->isUser()
                    ->save();
            }

            if ($userRole[0] == 'user' || 'tieruser') {
                //check if token exist in table
                $acctoken = SessionTable::where([
                    ['status', '=', 1],
                    ['access_token', '=', $token],
                ])->get()->toArray();
                $uid = $user['id'];

                $acctokenExists = SessionTable::where([
                    ['status', '=', 0],
                    ['access_token', '=', $token],
                ]);

                if ($acctokenExists->exists()) {
                    $response = [
                        'success' => true,
                        'data' => 'Empty',
                        'message' => 'logged out successful',
                        'response_code' => 200,
                    ];

                    return response()->json($response, 200);
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
                    exit;
                }
            } else {
                config([
                    'jwt.blacklist_enabled' => true
                ]);
                JWTAuth::parseToken()->invalidate();
                return response()->json(['success' => true, 'response_code' => 200, 'message' => 'Successfully logged out'], 200);
            }



        } catch (Exception $ex) { // Anything that went wrong
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong'

            ], 500);
        }
    }

    public function samedevicelogout(Request $request)
    {



        $validate = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'response_code' => 400,
                'message' => 'Validation error',
                'data' => $validate->messages()
            ], 400);
        }

        // Extract and clean Authorization token
        $authToken = $request->header('Authorization');
        if ($authToken) {
            $str = preg_replace('/\s+/', '', str_replace("Bearer", "", $authToken));
            $str = preg_replace('/^null$/i', '', $str);

            if ($str && !SessionTable::where('status', 1)->where('access_token', $str)->exists()) {
                return response()->json([
                    'success' => false,
                    'status' => false,
                    'response_code' => 401,
                    'message' => 'Token is Invalid'
                ], 401);
            }
        }


        //  HoaUser::getCurrentSubUserByToken();
        // Unhash the user ID
        $id = UnHashUserID($request->id);

        // Retrieve the session record
        $session = SessionTable::with('pdInfo')->where('status', 1)
            ->where('id', $id)
            ->first();
        $sessionLogout = SessionTable::where('status', 0)
            ->where('id', $id)
            ->first();






        if ($request->passcode != null) {
            $hoauser = HoaUser::where('passcode', $request->passcode)->first();
            if ($hoauser != null) {

                $this->activity($session->users_id)->event("Device")
                    ->eventType(ActivityEventType::REMOVED);
                $device = ($session->device_title ?? explode('#~', $session->mac_address)[0]);
                if ($session->device_title != null) {
                    $this->descriptionSimple(
                        "Device limit exceed. Device removed <br>"
                        . "Device: {$device} <br>"
                        . "Device name: {$session->pdInfo->personal_devicename}"

                    );
                } else {
                    $this->descriptionSimple(
                        "Device limit exceed. Device removed <br>"
                        . "Device: {$device} <br>"


                    );
                }
                $this->properties([
                    "data" => $session


                ])
                    ->passcode($hoauser->passcode)
                    ->isUser()
                    ->save();
            } else {


                $this->activity($session->users_id)->event("Device")
                    ->eventType(ActivityEventType::REMOVED);
                $device = ($session->device_title ?? explode('#~', $session->mac_address)[0]);
                if ($session->device_title != null) {
                    $this->descriptionSimple(
                        "Device limit exceed. Device removed <br>"
                        . "Device: {$device} <br>"
                        . "Device name: {$session->pdInfo->personal_devicename}"

                    );
                } else {
                    $this->descriptionSimple(
                        "Device limit exceed. Device removed <br>"
                        . "Device: {$device} <br>"


                    );
                }
                $this->properties([
                    "data" => $session


                ])

                    ->isUser()
                    ->save();
            }


        } elseif ($request->email != null) {



            $this->activity($session->users_id)->event("Device")
                ->eventType(ActivityEventType::REMOVED);
            $device = ($session->device_title ?? explode('#~', $session->mac_address)[0]);
            if ($session->device_title != null) {
                $this->descriptionSimple(
                    "Device limit exceed. Device removed <br>"
                    . "Device: {$device} <br>"
                    . "Device name: {$session->pdInfo->personal_devicename}"

                );
            } else {
                $this->descriptionSimple(
                    "Device limit exceed. Device removed <br>"
                    . "Device: {$device} <br>"


                );
            }
            $this->properties([
                "data" => $session


            ])

                ->isUser()
                ->save();
        }




        // If the device is logged in
        if ($session) {

            $this->activity($session->users_id)->event("Device")
                ->eventType(ActivityEventType::REMOVED);
            $device = ($session->device_title ?? explode('#~', $session->mac_address)[0]);
            if ($session->device_title != null) {
                $this->descriptionSimple(
                    "Device limit exceed. Device removed"
                    . "Device: {$device} <br>"
                    . "Device name: {$session->pdInfo->personal_devicename}"

                );
            } else {
                $this->descriptionSimple(
                    "Device limit exceed. Device removed <br>"
                    . "Device: {$device} <br>"


                );
            }
            $this->properties([
                "data" => $session


            ]);

            if ($session->passcode != null) {
                $this->passcode($session->passcode);
            }

            $this->isUser()
                ->save();

            // Log out the user and update connected devices
            $session->update(['status' => 0, 'modified_on' => Carbon::now()]);
            SessionTable::where('status', 1)->where('connected_devices', '!=', 1)
                ->update(['connected_devices' => DB::raw('`connected_devices` - 1')]);

            $accesstoken = $session->get('access_token')[0]->access_token;
            // dd($accesstoken);
            $request->headers->set('Content-TypeX-Header-One', 'X-Header-Oneapplication/json');
            $request->headers->set('Authorization', 'Bearer ' . $accesstoken);
            $request->server->set('HTTP_AUTHORIZATION', 'Bearer ' . $accesstoken);
            // Invalidate token (this could be its own method)



            invalidateToken();

            $this->updateHotelRoomSession($id);



            return response()->json([
                "response_code" => 200,
                "success" => true,
                "message" => "logged out successful",
                "error" => false,
            ], 200);
        }

        // If the device is already logged out
        if ($sessionLogout) {
            $this->updateHotelRoomSession($id);

            return response()->json([
                'success' => true,
                'data' => 'Empty',
                'message' => 'logged out successful',
                'response_code' => 200,
            ], 200);
        }

        // If the session does not exist
        return response()->json([
            'success' => false,
            'data' => 'Empty',
            'message' => 'id not found.',
            'response_code' => 404,
        ], 404);
    }

    // Helper function to update hotel room sessions
    private function updateHotelRoomSession($sessionId)
    {
        $hotelRoomCount = HotelRoomTable::where('session_id', $sessionId)->count();

        if ($hotelRoomCount > 0) {
            HotelRoomTable::where('session_id', $sessionId)->update(['session_id' => null]);
        }
    }


    public function logoutDevice(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validate->fails()) {
            $response = array('success' => false, 'response_code' => 400, 'message' => 'Validation error', 'data' => $validate->messages());
            return response()->json($response, 400);
        }
        // dump($request->id);
        $id = UnHashUserID($request->id);
        $checkDeviceDetails = SessionTable::where([
            ['status', '=', '1'],
            ['id', '=', $id],
        ]);
        if ($checkDeviceDetails->exists()) {
            //   dump($checkDeviceDetails->get());
            $accesstoken = $checkDeviceDetails->get('access_token')[0]->access_token;
            // dd($accesstoken);
            $request->headers->set('Content-TypeX-Header-One', 'X-Header-Oneapplication/json');
            $request->headers->set('Authorization', 'Bearer ' . $accesstoken);
            $request->server->set('HTTP_AUTHORIZATION', 'Bearer ' . $accesstoken);
            // header("Authorization: Bearer" . $accesstoken);
            //    $request = Request::create(route('api.v1.b.show', ['booking' => 4]), 'GET');
            //    $request->headers->set('Authorization', 'Bearer ' . $accesstoken);
            // dd($request->headers->set('Authorization', 'Bearer ' . $accesstoken));
            // $this->logout()->middleware(['jwt.verify']);
            $user = SessionTable::where('id', $id)->update(['status' => 0, 'modified_on' => Carbon::now()]);
            if ($user) {
                $response = [
                    "response_code" => 200,
                    "success" => true,
                    "message" => "logged out successful",
                    "error" => false,
                ];
                return response()->json($response, 200);
            } else {
                $response = [

                    'success' => false,
                    'data' => 'Empty',
                    'message' => 'id not found.',
                    'response_code' => 404,
                ];
                return response()->json($response, 404);
            }
            //$this->logout()->middleware(['jwt.verify']);
        } else {
            $response = [

                'success' => false,
                'data' => 'Empty',
                'message' => 'id not found.',
                'response_code' => 404,
            ];
            return response()->json($response, 404);
        }
    }

    //function incomplete this let user delete sessions
    public function multilogout()
    {
        try {
            $request = request();
            $token = $request->bearerToken(); //get token from header
            $user = JWTAuth::user();
            $userRole = $user->roles->pluck('name'); //check users role
            if ($userRole[0] == 'user' || 'tieruser') {
                //check if token exist in tablelogoutDevice
                $acctoken = SessionTable::where([
                    ['status', '=', '1'],
                    ['access_token', '=', $token],
                ])->get()->toArray();
                $uid = $user['id'];
                if ((count($acctoken) == 0)) {
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
                    exit;
                }
            } else {
                config([
                    'jwt.blacklist_enabled' => true
                ]);
                JWTAuth::parseToken()->invalidate();
                return response()->json(['success' => true, 'response_code' => 200, 'message' => 'Successfully logged out'], 200);
            }
        } catch (Exception $ex) { // Anything that went wrong
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong'

            ], 500);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $validateFlag = null)
    {
        $user = JWTAuth::user();
        $uRole = $user->roles->pluck('name');
        $user_data = array('name' => $user->name, 'email' => $user->email, 'roles' => $user->roles->pluck('name'), 'rolename' => $uRole[0]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user_data,
            'validate' => $validateFlag
        ]);
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

    public function loginEntry(Request $request)
    {

        $key = $request['key'];
        if (empty($key)) {

            $return = array('status' => 2, 'msg' => 'key not provided');
            return response()->json($return, 200);
        }
        $mem = new \Memcached();
        $mem->addServer('127.0.0.1', 11211);
        $data = json_decode($mem->get($key), true);
        //     $passcode=$data['login'];
        if (empty($data)) {
            $return = array('status' => 2, 'msg' => 'expired');
            return response()->json($return, 200);
        } else {
            if (isset($data['jwt'])) {
                // $userid=UnHashUserID($data['jwt']);s
                $request->merge(["passcode" => $data['jwt']]);

                $this->loginWithPasscode($request);
                exit;
                // $user = Auth::loginUsingId($userid, true);


                //    $return = array ('status'=>1,'msg'=>'success','jwt'=>$data['jwt'],'user'=>$user );
                //    return response()->json($return, 200);
            } else {
                $return = array('status' => 0, 'msg' => '', 'data' => $data);
                return response()->json($return, 400);
            }
        }
        // Use passcode to login and return the object in reposne or jWT
        // extract memcache and the login var jwt/passcode
        //use passcode to login and return the jwt
    }

    /*** Check all available zipcode ****/

    public function getZipcodes(Request $request)
    {
        $search = $request->search;

        $query = Zipcode::orderBy('zipcode', 'asc')
            ->select('zipcode', 'city', 'state');

        if ($search) {
            $query->where('zipcode', 'like', '%' . $search . '%');
        }

        $zipcodes = $query->limit(5)->get();

        $response = $zipcodes->map(function ($zipcode) {
            return [
                "value" => $zipcode->zipcode,
                "label" => $zipcode->zipcode,
                "state" => $zipcode->state,
                "city" => $zipcode->city,
            ];
        });

        return response()->json($response);
    }

    /*** Report zipcodes inquery***/

    public function failedzipinquery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|max:255',
            'zip' => 'required|max:255',
            'city' => 'required|max:255',
            'state' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        DeclineZipInquiry::updateOrCreate(
            [
                'email' => $request->input('email'),
                'zip' => $request->input('zip'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
            ],
            ['frequency' => \DB::raw('frequency + 1')]
        );

        return response()->json(['success' => 'Data is successfully added']);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->email;

        $userExists = User::where('email', $email)->exists();

        return response()->json([
            'message' => $userExists ? 'User already exists' : "User doesn't exist",
            'exists' => $userExists,
        ]);
    }

    public function deviceDetail(Request $request)
    {

        $deviceId = "web" . session()->getId(); // Use Laravel session ID as a unique identifier
        return response()->json([
            'deviceid' => $deviceId
        ]);
    }

    public function deleteAccountOtpSend()
    {
        DB::beginTransaction();
        try {
            $otp = random_int(100000, 999999);
            EmailOtp::where('email', auth()->user()->email)
                ->whereNull('sent_count')
                ->whereDate('created_at', '<', now())
                ->delete();
            $emailOtp = EmailOtp::where('email', auth()->user()->email)
                ->whereNotNull('sent_count')
                ->whereDate('created_at', now()) // current date
                ->first();

            if ($emailOtp != null && $emailOtp->sent_count >= 3) {
                return response()->json([
                    "status" => false,
                    "response_code" => 429,
                    "message" => "Delete account OTP limit reached for today. Try again later ."
                ], 429);


            }

            if ($emailOtp == null) {
                EmailOtp::create([
                    'email' => auth()->user()->email,
                    'otp' => $otp,
                    'otp_expire' => now()->addMinutes(15),
                    "sent_count" => 1
                ]);
            } else {
                $emailOtp->update([
                    'otp' => $otp,
                    'otp_expire' => now()->addMinutes(15),
                    "sent_count" => $emailOtp->sent_count += 1
                ]);
            }

            DB::commit();
            Mail::to(auth()->user()->email)->send(new DeleteAccountOtp(auth()->user(), $otp));

            return response()->json([
                "status" => true,
                "response_code" => 200,
                "message" => "OTP sent successfully !"
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                "status" => false,
                "response_code" => 500,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function deleteAccountOtpVerification(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'response_code' => 422,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {


            $emailOtp = EmailOtp::where('email', auth()->user()->email)
                ->where('otp', $request->otp)
                ->whereNotNull('sent_count')
                ->first();


            if ($emailOtp == null) {
                return response()->json([
                    'status' => false,
                    'response_code' => 422,
                    'message' => "Invalid OTP ."

                ], 422);

            }

            if (Carbon::parse($emailOtp->otp_expire)->isPast()) {
                return response()->json([
                    'status' => false,
                    'response_code' => 422,
                    'message' => "OTP has expired. Please request a new one."
                ], 422);
            }

            $emailOtp->delete();
            DB::commit();

            return response()->json([
                "status" => true,
                "response_code" => 200,
                "message" => "OTP verified successfully !"
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                "status" => false,
                "response_code" => 500,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            // Generate OTP
            $otp = random_int(100000, 999999);

            // Save OTP to the database
            $user = EmailOtp::where('email', $request->email)->first();
            if (!$user) {
                EmailOtp::create([
                    'email' => request('email'),
                    'otp' => $otp,
                    'otp_expire' => now()->addMinutes(1),
                ]);
            } else {
                EmailOtp::where('email', $request->email)->update(
                    [
                        'otp' => $otp,
                        'otp_expire' => Carbon::now()->addMinutes(1),
                    ]
                );
            }
            // Send OTP to the email
            $this->sendOtpEmail(request('email'), $otp);

            return response()->json(['message' => 'OTP sent successfully']);
        }
    }

    private function sendOtpEmail($email, $otp)
    {
        // Implement your email sending logic here
        // You can use Laravel's built-in Mail facade or any email service provider
        // Example using Laravel Mail facade:
        Mail::to($email)->send(new OtpEmail($otp));
    }

    public function verifyOtp()
    {
        // Validate the request
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Check if OTP is valid and not expired
        $otpRecord = EmailOtp::where('email', request('email'))
            ->where('otp', request('otp'))
            ->where('otp_expire', '>', now())
            ->first();

        if ($otpRecord) {
            // OTP is valid
            // Implement your further verification logic here
            return response()->json(['message' => 'OTP verification successful']);
        }

        // Invalid OTP or expired
        return response()->json(['error' => 'Invalid OTP or expired'], 401);
    }
}
