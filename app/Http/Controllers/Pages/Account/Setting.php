<?php

namespace App\Http\Controllers\Pages\Account;

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
use Illuminate\Support\Arr;
use Mail;

class Setting extends Controller
{   public function __construct()
    {
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
    }
    // Account Settings page
    public function showSettingpage(){

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
        $allSession=SessionTable::with('user')->selectRaw("id,users_id , connected_devices,access_token, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name")
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
            
         }
         
    	return view('frontend.pages.account.setting', compact('login','allSession','subscription','plan','userinfo'));
    }

    public function sessiondestroy($id,Request $request)
    {   $input=$request->only('access_token');
        $tokenString=$input['access_token'];
        $myarr=[];
        array_push($myarr,$id );  
       // print_r($myarr);
        $user= SessionTable::whereIn('id', $myarr)->update(['status' => 0,'modified_on' => Carbon::now()]);
        SessionTable::where([
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

JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($tokenString), $forceForever = false);
        
         if($user){
            return redirect('account/settings')->with('message','Device Session Deleted Successfully');
                      
        
        }else{
            return redirect('account/settings')->with('message','Device Session Not found');
            
        }

        
    }

    public function update(Request $request, $id)
    { 
        $attributeNames = array(
            'old_password'=>'Old Password',
           'password' => 'Password',
        );
        $validator = Validator::make($request->all(), [
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
        ]);
        $validatedData = $request->validate([
            'old_password' => 'required',
            'password' => 'confirmed|string|min:8',
        ], [], $attributeNames);

        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        
        // $input = $request->only('name', 'email', 'password','dob','gender','country','address','zipcode','contact');
        $input = $request->only('password');
       
    
        
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
     return redirect('account/settings')->with('message','Password Updated Successfully');
        
        
    }

    public function destroy($id)
    {  $user = User::find($id);
    
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
            return redirect()->route('home');
        }

        return view('frontend.pages.account.bugreport', compact('login'));
    }
    public function sendBugReport(Request $request)
    {     $User=Auth::user();
        $FRONTEND_URL=env('FRONTEND_URL');
        $html='<html>
        Hi, '.$User->name.'<br><br>

        Error Report of'.env('APP_NAME').'.
        <br><br>
        User Email:'.$request['email_input'].'.<br><br>
        Bug Severity:'.$request['bug_severity'].'.<br>
        Occurrence:'.$request['occurrence'].'.<br>
        Debug info:'.$request['debug_info'].'.<br>
        Details:'.$request['details'].'.<br>
    </html>';
    try{
        Mail::send('emails.general',["html"=>$html] , function($message) use ($request,$User){
            $message->to(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
            ->subject(env('APP_NAME').': Feedback/Bug Report');
            // return redirect()->route('home')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
        });} catch (Exception $ex) {
             //$ex->getMessage();
             return redirect()->back()->with('message', "We've got errors!".json_encode($ex->getMessage(), true));
            // return "We've got errors!".json_encode($ex->getMessage(), true);
        }
        return redirect()->back()->with('message', "Feedback Sent!");
    }
}
