<?php

namespace App\Http\Controllers\Pages\Account;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\User;
use App\SessionTable;
use JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;
use App\Models\BugReporting;
use App\Models\PersonalDevice;
use Illuminate\Support\Arr;
use Mail;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\HotelRoomTable;
use Jenssegers\Agent\Agent;
class Setting extends Controller
{   public function __construct()
    {
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
    }
    public function invoice(){
        $user= Auth::user();
    //   $invoices=  \Stripe\Invoice::all(['customer' => $user->stripe_id]);
      $invoices = $user->invoices();
     $firstinvoice= $user->invoices->first()->download();

        return [ 'invoices' => $invoices ];
    }
    public function invoicePDF (Request $request, $invoiceId) {
        $user= Auth::user();
        
        // return dd(\Stripe\Invoice::all(['customer' => $user->stripe_id]));
        return Auth::user()->downloadInvoice($invoiceId, [
            'vendor' => 'Sahil',
            'product' => 'Your Product',
        ]);
    }
    // Account Settings page
    public function showSettingpage(){
        $invoices='1';
    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }
       $id= Auth::user()->id;
        $userinfo = User::find($id);
        // $roles = Role::get(); //get all roles
        // $userRoles = $user->roles->pluck('id')->toArray();
        //dd($roles);
        $allSession=SessionTable::with('user')->selectRaw("id,users_id , connected_devices,access_token,created_on, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name")
        ->where([
        ['status', '=', '1'],
        ['users_id', '=', $id],
         ])->orderBy('connected_devices', 'asc')->get();
         $currentMonth = date('m');
        //  $subscription = Subscription::where([
        //     // ['stripe_status', '=', 'canceled'],
        //     ['user_id', '=', $id],
        //      ])
        //  ->whereRaw('MONTH(created_at) = ?',[$currentMonth])
        //  ->first();
        $subscription=Auth::user()->subscriptions()->first();
         
         if(!$userinfo->subscribed('main')){
            $plan = (object) ['amount'=>'0'];
         } else {
            
         
            $stripe_plan=$subscription->stripe_plan;
         
            $plan = \Stripe\Plan::retrieve($stripe_plan);
           // dd($plan);
           $invoices=  \Stripe\Invoice::all(['customer' => Auth::user()->stripe_id,'expand'=>['data.charge']]);
        //    $invoices = Auth::user()->invoicesIncludingPending();
        //    https://api.stripe.com/v1/invoices/in_1HSfl2LqTwOy7LECEZruM6OB?expand[]=charge
            // ch_1HSfl3LqTwOy7LECVmOdkimy
        //  dd($invoices);
         }
         
    	return view('frontend.pages.account.setting', compact('login','allSession','subscription','plan','userinfo','invoices'));
    }
    // Account Settings page
    public function showSettingpage2(){
        $invoices='1';
    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }
       $id= Auth::user()->id;
        $userinfo = User::find($id);
        // $roles = Role::get(); //get all roles
        // $userRoles = $user->roles->pluck('id')->toArray();
        //dd($roles);
        $allSession=SessionTable::with('user')->selectRaw("id,users_id , connected_devices,access_token,created_on, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name")
        ->where([
        ['status', '=', '1'],
        ['users_id', '=', $id],
         ])->orderBy('connected_devices', 'asc')->get();
         $currentMonth = date('m');
        return view('frontend.pages.account.setting4', compact('login','allSession'));
    }
    public function showSettingpage3(){
        $invoices='1';
    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }
       $id= Auth::user()->id;
      
       $hasid = HashUserID($id);
    //  dump($hasid);
    //  dd(UnHashUserID($hasid));
        $userinfo = User::find($id);

        $resultoffset = $userinfo->zipcode;
      
          if($resultoffset == "86336" || $resultoffset == "85023" || $resultoffset == "85022"){
            $timezone = "Mountain Standard Time";
          }else if($resultoffset == "97352" || $resultoffset == "92832" || $resultoffset == "92831" || $resultoffset == "97305" || $resultoffset == "97305"){
            $timezone = "Pacific Standard Time";
          }else if($resultoffset == "75662"){
            $timezone = "Central Standard Time";
          }else{
            $timezone = "Indian Standard Time";
          }
        
         $allSession=SessionTable::where([
        ['status', '=', '1'],
        ['users_id', '=', $id],
         ])->orderBy('connected_devices', 'asc')->get();
         $currentMonth = date('m');
         $subscription=Auth::user()->subscriptions()->first();
        //  dd($subscription);
        //  $status= \Stripe\Subscription::retrieve($subscription->stripe_id);
        //  dd($status);
         if(!$userinfo->subscribed('main')){
            $plan = (object) ['amount'=>'0'];
         } else {
            
         
            $stripe_plan=$subscription->stripe_plan;
         
            // $plan = \Stripe\Plan::retrieve($stripe_plan);
            $plan = \Stripe\Plan::retrieve(['id'=>$stripe_plan, 'expand'=>["tiers"]]);
        //    dd($plan);
           $invoices=  \Stripe\Invoice::all(['customer' => Auth::user()->stripe_id,'expand'=>['data.charge']]);
        //    $invoices = Auth::user()->invoicesIncludingPending();
        //    https://api.stripe.com/v1/invoices/in_1HSfl2LqTwOy7LECEZruM6OB?expand[]=charge
            // ch_1HSfl3LqTwOy7LECVmOdkimy
        //  dd($invoices);
         }
        if($subscription->ends_at){
        $renewaldatetime = 'Plan Expired';
        }else{

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.stripe.com//v1/invoices/upcoming?customer='.$userinfo->stripe_id.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Bearer '.env('STRIPE_SECRET').''
  ),
));

$response = curl_exec($curl);
$renewalDatestripe = json_decode($response);
curl_close($curl);

dd($userinfo->card_last_four);
die();

        // $time = ($subscription->created_at)->format('H:i:s');
        // $renewsDate = gmdate("Y-m-d", $plan->created);
        //$renewsDate = date("Y-m-d", strtotime("+".$plan->interval_count.$plan->interval));
        //$renewaldatetime = $renewsDate . ' at ' . $time;
        $renewaldatetime = date("Y-m-d H:i:s", $renewalDatestripe->next_payment_attempt);
        }
         
    	// return view('frontend.pages.account.setting', compact('login','allSession'));
    	return view('frontend.pages.account.setting3', compact('login','allSession','subscription','plan','userinfo','invoices','hasid', 'timezone', 'renewaldatetime'));
    }
    public function showSettingpage4(){
        $invoices='1';
    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }
       $id= Auth::user()->id;
      
       $hasid = HashUserID($id);
    //  dump($hasid);
    //  dd(UnHashUserID($hasid));
        $userinfo = User::find($id);
        $allSession=SessionTable::with(['pdname'=> function($q) use($id) {
            // Query the name field in status table
                $q->select('id','personal_devicename','device_id','user_id')
                ->where('user_id', '=', $id); 
        }])
        ->where([
            ['status', '=', '1'],
            ['users_id', '=', $id],
        ])
        ->orderBy('connected_devices', 'asc')
        ->get();
        /* $allSession = DB::table('session_table')
            ->leftJoin('personal_device', 'session_table.device_id', '=', 'personal_device.device_id')
            ->where('session_table.users_id', '=', $id)
            ->where('session_table.status', '=', '1')
            ->selectRaw("session_table.*, personal_device.personal_devicename as device_name, personal_device.id as pd_id")
            ->orderBy('session_table.connected_devices', 'asc')
            ->distinct()
            ->get();
        */
       
        $userRole=  $userinfo->roles->first()->name;
         if($userRole=='tieruser'){
            /*$allSession=SessionTable::with('hotel')->where([
                ['status', '=', '1'],
                ['users_id', '=', $id],
            ])->orderBy('connected_devices', 'asc')->get();  
            */
            $allSession=SessionTable::with(['hotel','pdname'=> function($q) use($id) {
                // Query the name field in status table
                $q->select('id','personal_devicename','device_id','user_id')->where('user_id', '=', $id);
            }])->where([
                ['status', '=', '1'],
                ['users_id', '=', $id],
            ])->orderBy('connected_devices', 'asc')->get();
        }
        // dd($allSession);
        
    	return view('frontend.pages.account.devicemanagement', compact('login','allSession','userinfo','hasid'));
    }

    public function sessiondestroy($id,Request $request)
     {   $input=$request->only('access_token');
        $tokenString=$input['access_token'];

    $request->headers->set('Authorization', 'Bearer '.$tokenString);
        $dataresponse_code=LogoutandDecrementDevice($id);
        if($dataresponse_code){
            return redirect()->back()->with('message','Device Session Deleted Successfully');
        }
        return redirect()->back()->with('message','Device Session Not found');
        
    }

    public function update(Request $request, $id)
    { 
        $userinfo = User::find($id);
        $attributeNames = array(
            'old_password'=>'Old Password',
           'password' => 'Password',
        );
        if($userinfo->password != NULL){
            $validator = Validator::make($request->all(), [
                'old_password' => [
                    'required', function ($attribute, $value, $fail) {
                        if (!Hash::check($value, Auth::user()->password)) {
                            $fail('Old Password didn\'t match');
                        }
                    },
                ],
                'password' => 'confirmed|string|min:8',
            ]);
            // $validatedData = $request->validate([
            //     'old_password' => 'required',
            //     'password' => 'confirmed|string|min:8',
            // ], [], $attributeNames);
        }else{
            $validator = Validator::make($request->all(), [
                'password' => 'confirmed|string|min:8',
            ]);
        }
        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        
        $input = $request->only('name', 'email', 'old_password', 'password','dob','gender','country','address','zipcode','contact');
        // $input = $request->only('password','zipcode');
       
    
        
     // dd($archived);
        
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']); //update the password
        }else{
            $input = Arr::except($input,array('password')); //remove password from the input array
        }
       //$allpost= $request->post();
       // dd($input); exit; get all the post data
       if($request['password'] == $request['old_password']){
        return redirect()->back()->with('message2','Your Old Password Cannot Be New Password');
       }else{
        $user = User::find($id);
        $user->update($input); //update the user info
        return redirect()->back()->with('message','Password Updated Successfully');
       }
             
    }
    public function updatePersonalDeviceName(Request $request, $id)
    {  $validator = Validator::make($request->all(), [
        'devicename' => 'required|string|max:20',
        'device_type' => 'required|string|max:20'
    ]);
    if($validator->fails()) {
        return redirect()->back()->with(['deviceidseesion' => $id])->withErrors($validator);
    }
    $unhashid=UnHashUserID($id);
    if($request->device_type == 'non-web'){
        $user=PersonalDevice::where('id', $unhashid)->update( [
            'personal_devicename' => $request->devicename
        ]);
    }else{
        $user=SessionTable::where('id', $unhashid)->update( [
            'device->personaldevicename' => $request->devicename
        ]);
    }
        if($request->wantsJson()){

        $response = [
            'success' => true,
            'data' => ['devicename'=>$request->devicename,'personaldeviceid'=>$id],
            'message' => 'Device name Updated Successfully.',
            'response_code'=>200,
        ];

        return response()->json($response, 200);
        }
     return redirect()->back()->with('message','Device name Updated Successfully');
        
        
    }

    public function destroy($id)
    {  
        $uid= Auth::user()->id;
        $user = User::find($uid);
    
        try{
        $user->subscription('main')->skipTrial()->cancelNow();
        return redirect('account/settings')->with('message','Subscription Cancel Successfully');
    }catch( Exception $e ){
        return redirect('account/settings')->with('error','Error'.json_encode($e->getMessage(), true)); }
        

        
    }

    public function BugReportView(){
        if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }

        return view('frontend.pages.account.bugreport', compact('login'));
    }
    public function sendBugReport(Request $request)
    {    
        $FRONTEND_URL=env('FRONTEND_URL');
        $html='<html>
        <br>

        Error Report of '.env('APP_NAME').'.
        <br><br>
        User Email:'.$request['email_input'].'.<br><br>
        Bug Severity:'.$request['bug_severity'].'.<br>
        Occurrence:'.$request['occurrence'].'.<br>
        Platform info:'.$request['platform_info'].'.<br>
        Details:'.$request['details'].'.<br>
    </html>';

    // check if reCaptcha has been validated by Google      
    $secret = env('GOOGLE_RECAPTCHA_SECRET');
    $captchaId = $request->input('g-recaptcha-response');
    
    //sends post request to the URL and tranforms response to JSON
    $responseCaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captchaId));
    
    if($responseCaptcha->success == true){
    try{
        Mail::send('emails.general',["html"=>$html] , function($message) use ($request){
            $message->to(env('MAIL_TO_ADDRESS'), env('APP_NAME'))
        
            ->subject(env('APP_NAME').': Feedback/Bug Report');
            // return redirect()->route('home')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
        });

            $outsideLocation = BugReporting::create([
                'email' => $request['email_input'],
                'occurences'=>$request['occurrence'],
                'bug_severity'=>$request['bug_severity'],
                'platform'=>$request['platform_info'],
                'message'=>$request['details'],
            ]);
    
    } catch (Exception $ex) {
             //$ex->getMessage();
             return redirect()->back()->with('mailerror', "We've got errors!".json_encode($ex->getMessage(), true));
            // return "We've got errors!".json_encode($ex->getMessage(), true);
        }
        return redirect()->back()->with('mailmessage', "Bug Report Sent!");}else{
            // send back error message
            
            
            return redirect()->back()->with('mailerror', 'Looks like you are a replicant. Sorry but We do not receive emails from non human entities');
            }
    }

    public function userAskedtoRefreshToken(){
        $user= Auth::user();
        $user_id=  $user['id'];
        $token = JWTAuth::fromUser($user);

        $deviceDetails=getDeviceIdforWeb();
        $login_limit = GetLimitForUser($user_id);
        //find all by user_id and and status=active
        $active_session = SessionTable::where([
        ['status', '=', '1'],
        ['users_id', '=', $user_id],
         ])->get()->toArray();
            //check if there's no entry by this used id
            if ((count($active_session)==0)) {
             //new entry of sessionTable
             if(sessionTableForWeb($user_id,$token,$deviceDetails,$connected_devices=1)){return redirect()->back();};
         }  else if (count($active_session) > 0 && count($active_session) < $login_limit) {
            //check if device id is same
            
           //new entry of sessionTable
           //and get the number of last connected device
           $prevConnectDevicecount = SessionTable::select('connected_devices')
            ->where([
            ['status', '=', '1'],
            ['users_id', '=', $user_id],
             ])->orderBy('connected_devices', 'desc')
             ->first();
             
          $device=number_format($prevConnectDevicecount['connected_devices']); 
          //the number of device previously connected + 1
           if(sessionTableForWeb($user_id,$token,$deviceDetails,$connected_devices=$device+1)){return redirect()->back();
      }else{return redirect()->back();}
       
       } 
       if (count($active_session) >= $login_limit) {
        //then return a login error that maximum logins reached
        //echo 'you are not allowed to login as you have breeched the maximum session limit.';
        //exit;
        return redirect()->back();
        


    }
      
        
    }

}
