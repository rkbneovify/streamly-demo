<?php

namespace App\Http\Controllers\Auth;

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
use Illuminate\Support\Arr;
use App\SessionTable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Memcached;
use DB;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login','register','verify','samedevicelogout','loginWithPasscode','loginEntry', 'loginCheck', 'hoteladminlogin']]);
    }

    public function register(Request $request){
        $validate=User::validator($request);
        
        if($validate->fails()){
            $response = array('success' => false,'response_code'=>400,'message'=>'Validation error','data'=>$validate->messages());
            return response()->json($response, 400);
        }

        $role=Role::where('name','user')->first();

        $User= User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'zipcode'=>$request->zipcode,
            'contact'=>$request->contact,
            'gender'=>$request->gender,
            'dob'=>$request->dob,
            'email_verified_at'=>null
        ]);

        $User->roles()->sync($role->id);

        $verification_code=Crypt::encrypt($User->email);

        $FRONTEND_URL=env('FRONTEND_URL');

        $account_verification_link=$FRONTEND_URL.'/api/auth/verify?token='.$verification_code;

        $html='<html>
        Hi, '.$request['name'].'<br><br>

        Thank you for registering for '.env('APP_NAME').'.<br><br>
        Please click the following link to verify your account.
        <br><br><a href="'.$account_verification_link.'" target=_blank style="  display: block;
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
        The '.env('APP_NAME').' Team!
    </html>';

    $html2='<html>
        Hi, '.$request['name'].'<br><br>

        Thank you for registering for '.env('APP_NAME').'.<br>
        Please click the following link to verify your account.
        <br><br><a href="'.$account_verification_link.'" target=_blank style="  display: block;
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
        Email: '.$request->email.'<br>
        Password: '.$request->password.'<br><br>
        Thank you!<br>
        The '.env('APP_NAME').' Team!
    </html>';

        if($request->masteruser){
        Mail::send('emails.general',["html"=>$html2] , function($message) use ($request,$User){
                $message->to($request->email, $User->name);
                $message->bcc('piyush.mishra@oodles.io')
                ->subject(env('APP_NAME').': Account Verification and Signup Details');
        });
        }else{
        Mail::send('emails.general',["html"=>$html] , function($message) use ($request,$User){
            $message->to($request->email, $User->name)
            ->subject(env('APP_NAME').': Account Verification');
        });
        }


        $response = array('success' => true,'response_code'=>200,'message'=>'You are registered successfully, check email and click on verification link to activate your account.', 'data'=>$User);
        return response()->json($response, 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {	$deviceDetails	=getDeviceIdforAPI($request);
        $credentials = request(['email', 'password']);

        if(empty(request('email')) || empty(request('password'))){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'both email and password required'], 401);
        }
        if(empty(request('deviceid'))){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'deviceid required'], 401);
        }
        $expirationInMinutes = 43800;//one month in minutes
        JWTAuth::factory()->setTTL($expirationInMinutes);

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['success'=>false,'message' => 'Invalid credentials','response_code'=>401], 401);
        }

        $user=JWTAuth::user();

        if($user->email_verified_at==null){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'Email not verfied, verify your email first.'], 401);   
        }

        if($user->is_active==0){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'You are deactivated, kindly contact admin.','deleted_at' => $user->deleted_at], 401);   
        }
       $userRole= $user->roles->pluck('name');
       if($userRole[0]=='user'||'tieruser'){
        $uid=  $user['id'];
        checkLoginLimit($uid,$token,$deviceDetails);
        exit;
        
    }
        $this->authenticated($request,$user,$token);
        return $this->respondWithToken($token);
    }

    public function hoteladminlogin(Request $request)
    {	$deviceDetails	=getDeviceIdforAPI($request);
        $credentials = request(['email', 'password']);

        if(empty(request('email')) || empty(request('password'))){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'both email and password required'], 401);
        }
        if(empty(request('deviceid'))){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'deviceid required'], 401);
        }
        $expirationInMinutes = 43800;//one month in minutes
        JWTAuth::factory()->setTTL($expirationInMinutes);

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['success'=>false,'message' => 'Invalid credentials','response_code'=>401], 401);
        }

        $user=JWTAuth::user();

        if($user->email_verified_at==null){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'Email not verfied, verify your email first.'], 401);   
        }

        if($user->is_active==0){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'You are deactivated, kindly contact admin.','deleted_at' => $user->deleted_at], 401);   
        }
       $userRole= $user->roles->pluck('name');
       if($userRole[0]=='user'||'tieruser'){
        $uid=  $user['id'];
        checkHoteluserLoginLimit($uid,$token,$deviceDetails);
        exit;
        
    }
        $this->authenticated($request,$user,$token);
        return $this->respondWithToken($token);
    }

    public function logincheck(Request $request)
    {	$deviceDetails	=getDeviceIdforAPI($request);
        $credentials = request(['email', 'password']);

        if(empty(request('email')) || empty(request('password'))){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'both email and password required'], 401);
        }
        if(empty(request('deviceid'))){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'deviceid required'], 401);
        }
        $expirationInMinutes = 43800;//one month in minutes
        JWTAuth::factory()->setTTL($expirationInMinutes);

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['success'=>false,'message' => 'Invalid credentials','response_code'=>401], 401);
        }

        $user=JWTAuth::user();

        if($user->email_verified_at==null){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'Email not verfied, verify your email first.'], 401);   
        }

        if($user->is_active==0){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'You are deactivated, kindly contact admin.','deleted_at' => $user->deleted_at], 401);   
        }
       $userRole= $user->roles->pluck('name');
       if($userRole[0]=='user'||'tieruser'){
        $uid=  $user['id'];
        checkLoginLimit2($uid,$token,$deviceDetails);
        exit;
        
    }
        $this->authenticated($request,$user,$token);
        return $this->respondWithToken($token);
    }

    public function loginWithPasscode(Request $request){
        $deviceDetails	=getDeviceIdforAPI($request);
       $passcode= $request->only('passcode');
       $uid=UnHashUserID($passcode['passcode']);
       if(!$uid){
          
        $response = array('success' =>false ,'response_code'=>404,'message'=>'User not found');
        return response()->json($response, 404);    
       }
       $user = User::find($uid);
        // $user = User::where('email', '=', $email)->first();

        $expirationInMinutes = 43800;//one month in minutes
        JWTAuth::factory()->setTTL($expirationInMinutes);
        $token = JWTAuth::fromUser($user);
        // $credentials = c;
        // $user=JWTAuth::user();

        if($user->email_verified_at==null){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'Email not verfied, verify your email first.'], 401);   
        }

        if($user->is_active==0){
            return response()->json(['success'=>false,'response_code'=>401,'message' => 'You are deactivated, kindly contact admin.','deleted_at' => $user->deleted_at], 401);   
        }
       $userRole= $user->roles->pluck('name');
       //todo hotel check
       if($userRole[0]=='user'||'tieruser'){
        
        checkLoginLimit($uid,$token,$deviceDetails);
        exit;
        
    }
        // $user_data=array('name'=>$user->name,'email'=>$user->email,'token'=>$token);
        
        // return response()->json($user_data,200);
    }
    public function verify(Request $request)
    {
       
        $validate = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        
        if ($validate->fails()) {
            $response = array('success' => false,'response_code'=>400,'message'=>'Validation error','data'=>$validate->messages());
            return response()->json($response, 400);
        }

        $token = $request->token;

        $verification_token = Crypt::decrypt($request->token);
        
        $user = User::where('email',$verification_token)->first();
        $email_verified_at=Carbon::now();

        if($user) {
            $user->email_verified_at = $email_verified_at;
            $user->is_active = 1;
            $user->save();
            if($request->wantsJson()){
            $response = array('success' =>true ,'response_code'=>200,'message'=>'Account successfully verified');
            return response()->json($response, 200);}
            else { if( Auth::check()){
                $login = true;}else{ $login = false; } return view('frontend.verified',compact('login')); }

        }else{
            $response = array('success' =>false ,'response_code'=>401,'message'=>'Invalid verification token');
            return response()->json($response, 401);
        }
    }

    public function changePassword(Request $request)
    {   
        $validate = Validator::make($request->all(), [
            'password' => 'required'
        ]);
        
        if ($validate->fails()) {
            $response = array('success' => false,'response_code'=>400,'message'=>'Validation error','data'=>$validate->messages());
            return response()->json($response, 400);
        }

        $User=JWTAuth::user();
        if($User){
             $User->password=Hash::make($request->password);
             $User->save();
             $response = array('success' =>true ,'response_code'=>200,'message'=>'Password changed successfully.');
            return response()->json($response, 200);         
        }else{
            $response = array('success' =>false ,'response_code'=>404,'message'=>'User not found');
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
        $user=JWTAuth::user();

// use Tymon\JWTAuth\Facades\JWTAuth;
    //     $token = $request->bearerToken();
    // $apy = JWTAuth::getPayload($token)->toArray();

    // 'payload'=>$apy,
        $user_data=array('name'=>$user->name,'email'=>$user->email,'roles'=>$user->roles->pluck('name'));
        
        return response()->json($user_data);
    }
   
    /**
     * Get the profile data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {        
        $user=JWTAuth::user();
        $response = [
            'success' => true,
            
            'data' => $user,
            'message' => 'Users Profile',
            'response_code'=>200,
        ];
        return response()->json($response, 200);
       // $user_data=array('name'=>$user->name,'email'=>$user->email,'roles'=>$user->roles->pluck('name'));
        
       // return response()->json($user_data);
    }

    public function tvprofile()
    {        
        $user=JWTAuth::user();
       $data=[ "name"=>$user->name,
       "email"=>$user->email, 
       "created"=>$user->created_at,"version"=>"1.0.0"];
        $response = [
            'success' => true,
            
            'data' => $data,
            'message' => 'Users Profile',
            'response_code'=>200,
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
    public function updateprofile(Request $request){        
        $loggedInuserinfo=JWTAuth::user();
       $userid = $loggedInuserinfo->id;
       if($request==''){
        return response()->json(['success'=>false,'response_code'=>400,'message' => 'No data sent'],400);
       }
        $exist=User::whereNotIn('id',[$userid])->where('email',[$request->email])->first();

        if($exist){
            $response = array('success' => false,'response_code'=>400,'message'=>'Email already exists.');
            return response()->json($response, 400);
        }
 //if email is passed
        if ($request->has('email')) {
            
            if ($input['lastdigit']==2222){
                $input['email'] =$request->email;
            } else {
                $response = array('success' => false,'response_code'=>400,'message'=>'Wrong code entered.');
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
        $input = $request->only('name', 'email', 'password','dob','gender','country','address','zipcode','contact');
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']); //update the password
        }else{
            $input = Arr::except($input,array('password')); //remove password from the input array
        }
        $user= User::find($userid);
        
        if($user){
            $user->update($input);
            $response = array('success' => true,'response_code'=>200,'message'=>'User updated successfully.','data'=>$user);             
            return response()->json($response, 200);
        }else{
            $response = array('success' => false,'response_code'=>404,'message'=>'User not found.');
            return response()->json($response, 404);
            }            
    }
   
    /**
     * Log the Adminuser out (Invalidate the token).
     *Log the user out and decrement the device(Invalidate the token).
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {    try {
        $request = request();
        $token = $request->bearerToken();//get token from header
        
        $user=JWTAuth::parseToken()->authenticate();
        
        $userRole= $user->roles->pluck('name');//check users role
           if($userRole[0]=='user'||'tieruser'){
               //check if token exist in table
            $acctoken = SessionTable::where([
                ['status', '=', '1'],
                ['access_token', '=', $token],
                 ])->get()->toArray();
        $uid=  $user['id'];
        if ((count($acctoken)==0)){ 
            $response = [
                
                'success' => false,
                'data' => 'Empty',
                'message' => 'access token not found.',
                'response_code'=>404,
            ];
            return response()->json($response, 404);

        }  else {
    
            $id=  $acctoken[0]['id'];
              LogoutandDecrementDevice($id);
              exit;
          }
        
        
        
    } else { 
        config([
        'jwt.blacklist_enabled' => true
    ]);
         JWTAuth::parseToken()->invalidate();
        return response()->json(['success'=>true,'response_code'=>200,'message' => 'Successfully logged out'],200); }
    } catch (Exception $ex) { // Anything that went wrong
        return response()->json( [
            'error'   => true,
            'message' => 'Something went wrong'

        ], 500 );
    }
}   

public function logoutDevice(Request $request)
{   
    $validate = Validator::make($request->all(), [
        'id' => 'required'
    ]);
    
    if ($validate->fails()) {
        $response = array('success' => false,'response_code'=>400,'message'=>'Validation error','data'=>$validate->messages());
        return response()->json($response, 400);
    }
    // dump($request->id);
    $id=UnHashUserID($request->id);
        $checkDeviceDetails=SessionTable::where([
            ['status', '=', '1'],
            ['id', '=', $id],
          ]);
          if ($checkDeviceDetails->exists()) {
            //   dump($checkDeviceDetails->get());
            $accesstoken=  $checkDeviceDetails->get('access_token')[0]->access_token;
            // dd($accesstoken);
            $request->headers->set('Content-TypeX-Header-One', 'X-Header-Oneapplication/json');
            $request->headers->set('Authorization', 'Bearer '.$accesstoken);
            $request->server->set('HTTP_AUTHORIZATION', 'Bearer '.$accesstoken);
            // header("Authorization: Bearer" . $accesstoken);
    //    $request = Request::create(route('api.v1.b.show', ['booking' => 4]), 'GET');
    //    $request->headers->set('Authorization', 'Bearer ' . $accesstoken);
// dd($request->headers->set('Authorization', 'Bearer ' . $accesstoken));
            // $this->logout()->middleware(['jwt.verify']);
                    $user= SessionTable::where('id', $id)->update(['status' => 0, 'modified_on' => Carbon::now()]);
                    if($user){
                        $response = [
                            "response_code" => 200,
                            "success" => true,
                            "message" => "logged out successful",
                            "error" => false,
                        ];
                        return response()->json($response, 200);
                   }else{
                    $response = [
            
                        'success' => false,
                        'data' => 'Empty',
                        'message' => 'id not found.',
                        'response_code'=>404,
                    ];
                    return response()->json($response, 404);
                   }
                //$this->logout()->middleware(['jwt.verify']);
        } else{
            $response = [
            
                'success' => false,
                'data' => 'Empty',
                'message' => 'id not found.',
                'response_code'=>404,
            ];
            return response()->json($response, 404);
        }
     
}

public function samedevicelogout(Request $request)
{   
    $validate = Validator::make($request->all(), [
        'id' => 'required'
    ]);

    $authToken = $request->header('Authorization');

    if ($authToken) {

        $tokenExist = SessionTable::where([
            ['status', '=', '1'],
            ['access_token', '=', str_replace("Bearer ", "", $authToken)]
        ]);

        if(!$tokenExist->exists()) {
            $response = array('success' => false,'response_code'=>400,'message'=>'Invalid auth token','data' => 'Empty');
            return $response;
        }

    }
    
    if ($validate->fails()) {
        $response = array('success' => false,'response_code'=>400,'message'=>'Validation error','data'=>$validate->messages());
        return response()->json($response, 400);
    }
    // dump($request->id);
    $id=UnHashUserID($request->id);
        $checkDeviceDetails=SessionTable::where([
            ['status', '=', '1'],
            ['id', '=', $id],
          ]);
          if ($checkDeviceDetails->exists()) {
            //   dump($checkDeviceDetails->get());
            $accesstoken=  $checkDeviceDetails->get('access_token')[0]->access_token;
            // dd($accesstoken);
            $request->headers->set('Content-TypeX-Header-One', 'X-Header-Oneapplication/json');
            $request->headers->set('Authorization', 'Bearer '.$accesstoken);
            $request->server->set('HTTP_AUTHORIZATION', 'Bearer '.$accesstoken);
            // header("Authorization: Bearer" . $accesstoken);
    //    $request = Request::create(route('api.v1.b.show', ['booking' => 4]), 'GET');
    //    $request->headers->set('Authorization', 'Bearer ' . $accesstoken);
// dd($request->headers->set('Authorization', 'Bearer ' . $accesstoken));
            // $this->logout()->middleware(['jwt.verify']);
                    $user= SessionTable::where('id', $id)->update(['status' => 0, 'modified_on' => Carbon::now()]);
                    SessionTable::where([
                        ['status', '=', '1'],
                        ['connected_devices', '!=', '1'],
                         ])
                ->update([
                    'connected_devices' => DB::raw('`connected_devices` - 1 ')
                ]);
                $error=invalidateToken();
                    if($user){
                        $response = [
                            "response_code" => 200,
                            "success" => true,
                            "message" => "logged out successful",
                            "error" => false,
                        ];
                        return response()->json($response,200 );
                   }else{
                    $response = [
            
                        'success' => false,
                        'data' => 'Empty',
                        'message' => 'id not found.',
                        'response_code'=>404,
                    ];
                    return $response;
                   }
                //$this->logout()->middleware(['jwt.verify']);
        } else{
            $response = [
            
                'success' => false,
                'data' => 'Empty',
                'message' => 'id not found.',
                'response_code'=>404,
            ];
            return response()->json($response, 404);
        }
     
}
//function incomplete this let user delete sessions
public function multilogout()
{    try {
    $request = request();
    $token = $request->bearerToken();//get token from header
    $user=JWTAuth::user();
    $userRole= $user->roles->pluck('name');//check users role
       if($userRole[0]=='user'||'tieruser'){
           //check if token exist in table
        $acctoken = SessionTable::where([
            ['status', '=', '1'],
            ['access_token', '=', $token],
             ])->get()->toArray();
    $uid=  $user['id'];
    if ((count($acctoken)==0)){ 
        $response = [
            
            'success' => false,
            'data' => 'Empty',
            'message' => 'access token not found.',
            'response_code'=>404,
        ];
        return response()->json($response, 404);

    }  else {

        $id=  $acctoken[0]['id'];
          LogoutandDecrementDevice($id);
          exit;
      }
    
    
    
} else { 
    config([
    'jwt.blacklist_enabled' => true
]);
     JWTAuth::parseToken()->invalidate();
    return response()->json(['success'=>true,'response_code'=>200,'message' => 'Successfully logged out'],200); }
} catch (Exception $ex) { // Anything that went wrong
    return response()->json( [
        'error'   => true,
        'message' => 'Something went wrong'

    ], 500 );
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
    protected function respondWithToken($token)
    {
        $user=JWTAuth::user();
        $uRole=$user->roles->pluck('name');
        $user_data=array('name'=>$user->name,'email'=>$user->email,'roles'=>$user->roles->pluck('name'),'rolename'=>$uRole[0]);

        return response()->json([            
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user'=>$user_data,
            
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
        $jwtToken->browser = $agent->browser();;
        $jwtToken->platform = $agent->platform();
        $jwtToken->device = $agent->device();
        $mobileHeader = $request->header('x_platform');
        if (isset($mobileHeader) && $mobileHeader == 'mobile') {
            JwtToken::where('user_id',$user->id)->where('phone',1)->delete();
            
            $jwtToken->phone = 1;
            $jwtToken->save();

        } else {
            JwtToken::where('user_id',$user->id)->where('desktop',1)->delete();
            
            $jwtToken->desktop = 1;
            $jwtToken->save();
        } 
    }

    public function loginEntry(Request $request)
    {
      
       $key=$request['key'];
       if (empty($key)){
  
       $return = array ('status'=>2,'msg'=>'key not provided' );
       return response()->json($return, 200);
       }
      $mem = new \Memcached();
      $mem->addServer('127.0.0.1',11211);
      $data = json_decode($mem->get($key),true); 
  //     $passcode=$data['login'];
      if (empty($data)){
       $return = array ('status'=>2,'msg'=>'expired' );
       return response()->json($return, 200);
   } else {
       if (isset($data['jwt'])){
        // $userid=UnHashUserID($data['jwt']);s
        $request->merge(["passcode"=>$data['jwt']]);

        $this->loginWithPasscode($request);
        exit;
        // $user = Auth::loginUsingId($userid, true);
  
  
        //    $return = array ('status'=>1,'msg'=>'success','jwt'=>$data['jwt'],'user'=>$user );
        //    return response()->json($return, 200);
      } else {
           $return = array ('status'=>0,'msg'=>'','data'=>$data );
           return response()->json($return, 400);
      }
  }
      // Use passcode to login and return the object in reposne or jWT
      // extract memcache and the login var jwt/passcode 
      //use passcode to login and return the jwt
    }
}