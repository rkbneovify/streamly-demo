<?php

namespace App\Http\Controllers;
use JWTAuth;
use App\User;
use App\SessionTable;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;
use Jenssegers\Agent\Agent;
class APIController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
        $user = JWTAuth::user();//all the user info

        $date = date('Y-m-d H:i:s');
        function getUserIpAddr(){
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                //ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                //ip pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }
        //Store the login info in 
        $task = new SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = $user['id'];
        $task->access_token = 'somrthing';//
        $task->user_ip = getUserIpAddr();
        $task->mac_address = 'from login entry';
        $task->connected_devices = 1;
       $task->logintime = $date;;
        //$task->description = $request->description;
        $task->created_on = $date;
        $task->modified_on = $date;
        $task->save();
        
        



        // to detect browser
        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();
       $browser_name =$browser.''.$device.''.$platform;
       $t=$browser_name?$browser_name:'unknown';
return response()->json([
    'success' => true,
    'token' => $token,
    'browser_name'=>$t,
    'user'=>$user
]);
}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    /**
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationFormRequest $request)
    { $date = date('Y-m-d H:i:s');
        $user = new User();
        $user->first_name = $request->first_name;
        $user->role_id = 1;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->address = 'adress 1';
        $user->address2 = 'adress 2';
        $user->postal_code = '111111';
        $user->state = 'state';
        $user->country = 'country';
        $user->active = 'x';
        $user->created_on = $date;
        $user->modified_on = $date;
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success'   =>  true,
            'data'      =>  $user
        ], 200);
    }
/// check for 3 concurrent login
    public function checkLoginLimit($user_id){
        $login_limit = 3;
    //find all by user_id and and status=active
    $active_session = SessionTab::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
     ])->get();
        //check if there's no entry by this used id
        if (($active_session == '' || count($active_session)==0)) {
         //new entry of sessionTable
         $user = JWTAuth::user();

        $date = date('Y-m-d H:i:s');
        function getUserIpAddr(){
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                //ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                //ip pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }
        //Store the login info in 
        $task = new SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = $user['id'];
        $task->access_token = 'somrthing';//change this field to status
        $task->user_ip = getUserIpAddr();
        $task->mac_address = 'from login entry';
        $task->connected_devices = 1;
       $task->logintime = $date;;
        //$task->description = $request->description;
        $task->created_on = $date;
        $task->modified_on = $date;
        $task->save();
       

        }
        else if (count($active_session) > 0 && count($active_session) < $login_limit) {

         //new entry of sessionTable
         $user = JWTAuth::user();

        $date = date('Y-m-d H:i:s');
        function getUserIpAddr(){
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                //ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                //ip pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }
        //Store the login info in 
        $task = new SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = $user['id'];
        $task->access_token = 'somrthing';//change this field to status
        $task->user_ip = getUserIpAddr();
        $task->mac_address = 'from login entry';
        $task->connected_devices++;
       $task->logintime = $date;;
        //$task->description = $request->description;
        $task->created_on = $date;
        $task->modified_on = $date;
        $task->save();
       

    }else if (count(($active_session)) >= $login_limit) {
        $logins = json_decode($active_session->conc_login);

         //find all by user_id and and status=active
    $active_session = SessionTab::where([
        ['status', '=', '1'],
        ['users_id', '=', $user_id],
         ])->get();
        foreach ($logins as $key => $login) {
            if ($login->logintime < time() - 120) {
                //this checks if the iterated login is greater than the current time -120seconds and if found to be true then the user is inactive
                //then set this current login to null by using the below statement
                $logins[$key] = 0; // or unset($logins[$key]) either should work;
                //unset($logins[$key]);
            }
        }
    } //after iteration we check if the count of logins is still greater than the limit
    if (count($logins) >= $login_limit) {
        //then return a login error that maximum logins reached
        //echo 'you are not allowed to login as you have breeched the maximum session limit.';
        //exit;
        $login_json = json_encode($logins);
        $active_session->conc_login = $login_json;
        $active_session->save();

        $this->addError($attribute, 'you are not allowed to login as you have breeched the maximum session limit.');

    } else {
        //then login is successsfull
        $user = JWTAuth::user();

        $date = date('Y-m-d H:i:s');
        function getUserIpAddr(){
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                //ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                //ip pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }
        //Store the login info in 
        $task = new SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = $user['id'];
        $task->access_token = 'somrthing';//
        $task->user_ip = getUserIpAddr();
        $task->mac_address = 'from login entry';
        $task->connected_devices = 1;
       $task->logintime = $date;;
        //$task->description = $request->description;
        $task->created_on = $date;
        $task->modified_on = $date;
        $task->save();
       exit;
    }

    //update the logins column to equal to json_encode($logins);

    
}}