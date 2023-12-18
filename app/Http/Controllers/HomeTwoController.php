<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\User;
use App\Models\RoleUser;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Crypt;
use Mail;
use Exception;
use App\Models\Zipcode;
use App\Models\Map;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\DB;
use App\Models\Channel;
use App\Models\Gracenote;
use ctwillie\Usps\Address;
use App\Models\FrontendText;
use App\Models\AddressesCheckAvailability;
// use GuzzleHttp\Client;
use App\SessionTable;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use App\Http\Requests\ContactFormRequest;

use App\Models\Genre;
use Illuminate\Support\Facades\Cache;
class HomeTwoController extends Controller
{

    private $user;
    private $DEFAULT_ROLE_ID = 3;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createUser(Request $request){

        $attributeNames = array(
           'user-email' => 'Email',
           'user-password' => 'Password',
           'name' => 'Name',
           'zip_code' => 'Zip Code',
        );

        $checkEmail = $request['user-email'];
        $User = User::where('email', $checkEmail)->first();

        if($User){
            $Emailstatus = User::where('email', $checkEmail)->value('is_active');
            if($Emailstatus == '1'){
                return redirect()->back()->withInput()->withErrors(['SMTP-Error' => 'Email already exists']);
            }else{
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'user-password' => 'required|string|min:6',
                    'zip_code' => 'required|regex:/^[0-9]{3,7}$/',
                    'zip_code'=>'required|exists:zipcodes,zipcode,status,1',
                    'g-recaptcha-response'=>'required'
                // ], ['zip_code.exists'=>'Service not available at this zipcode or your zipcode is not valid'], $attributeNames);
            ], ['zip_code.exists'=>'Unfortunately service is not yet available for the zip code you submitted.'], $attributeNames);
    
            $secret = env('GOOGLE_RECAPTCHA_SECRET');
            $captchaId = $request->input('g-recaptcha-response');
            
            //sends post request to the URL and tranforms response to JSON
            $responseCaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captchaId));
            if($responseCaptcha->success == true){
            
                $verification_code=Crypt::encrypt($request['user-email']);
            
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
            
                try{
                    Mail::send('emails.general',["html"=>$html] , function($message) use ($request){
                        $message->to($request['user-email'], $request['name'])
                        ->subject(env('APP_NAME').': Account Verification');
                    
                    });
    
                    $User->name = $request['name'];
                    $User->password=Hash::make($request['user-password']);
                    $User->zipcode = $request['zip_code'];
                    $User->save();
                
                } catch (Exception $ex) {
                         //$ex->getMessage();
                         return redirect()->back()->withInput()->withErrors(['SMTP-Error'=> "We've got errors!".json_encode($ex->getMessage(), true)]);
                    }
                    return redirect('/')->with('message', 'Thanks! We have sent you an email to verify your email address. Click  on the verification link in the email to activate your account.');
                }
            }
        }else{


        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'user-email' => 'required|string|email|max:255|unique:users,email',
            'user-password' => 'required|string|min:6',
            'zip_code' => 'required|regex:/^[0-9]{3,7}$/',
            'zip_code'=>'required|exists:zipcodes,zipcode,status,1',
            'g-recaptcha-response'=>'required'
        // ], ['zip_code.exists'=>'Service not available at this zipcode or your zipcode is not valid'], $attributeNames);
    ], ['zip_code.exists'=>'Unfortunately service is not yet available for the zip code you submitted.'], $attributeNames);

        /*if ( $validatedData->fails() ) {
            return redirect()->back()->withInput()->with( 'errors', $validatedData->errors() );
        }*/
// check if reCaptcha has been validated by Google      
$secret = env('GOOGLE_RECAPTCHA_SECRET');
$captchaId = $request->input('g-recaptcha-response');

//sends post request to the URL and tranforms response to JSON
$responseCaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captchaId));
if($responseCaptcha->success == true){

    $verification_code=Crypt::encrypt($request['user-email']);

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

    try{
        Mail::send('emails.general',["html"=>$html] , function($message) use ($request){
            $message->to($request['user-email'], $request['name'])
            ->subject(env('APP_NAME').': Account Verification');
            // return redirect()->route('home')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
       
       
    
        });

        $Usermode=DB::table('Stripe_keys')->where('status', 'active')->value('type');

        $User = User::create([
            'name' => $request['name'],
            'email' => $request['user-email'],
            'password' => Hash::make($request['user-password']),
            'zipcode' => $request['zip_code'],
            'email_verified_at'=>null,
            'is_active'=>0,
            'mode'=> $Usermode
        ]);

        $role = RoleUser::create([
            'user_id' => $User->id,
            'role_id' => $this->DEFAULT_ROLE_ID,
        ]);
    
    
    } catch (Exception $ex) {
             //$ex->getMessage();
             return redirect()->back()->withInput()->withErrors(['SMTP-Error'=> "We've got errors!".json_encode($ex->getMessage(), true)]);
            // return "We've got errors!".json_encode($ex->getMessage(), true);
        }

        return redirect('/')->with('message', 'Thanks! We have sent you an email to verify your email address. Click  on the verification link in the email to activate your account. ');
}else{
    // send back error message
    
    
    return redirect()->back()->with('message', 'Looks like you are a replicant. Sorry but We do not receive emails from non human entities');
    }

    }
}
    public function contactformsend(ContactFormRequest $request){

    // check if reCaptcha has been validated by Google      
    $secret = env('GOOGLE_RECAPTCHA_SECRET');
    $captchaId = $request->input('g-recaptcha-response');
    
    //sends post request to the URL and tranforms response to JSON
    $responseCaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captchaId));
    
    if($responseCaptcha->success == true){
       
        $html='<html>
            Hi,My name is '.$request['contactname'].'<br><br>
            Email :'.$request['user-email'].'<br><br>

            Message :'.$request['formmessage'].'.

            . <br><br>
        </html>';
        // echo $html;exit;
      try{
        Mail::send('emails.general',["html"=>$html] , function($message) use ($request){
            $message->to(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
            ->subject(env('APP_NAME').': Contact Form');
            // return redirect()->route('home')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
        });} catch (Exception $ex) {
             //$ex->getMessage();
             return redirect()->back()->with('contactmessage', "We've got errors!".json_encode($ex->getMessage(), true));
            // return "We've got errors!".json_encode($ex->getMessage(), true);
        }
    
        return redirect()->back()->with('contactmessage', 'Thanks for contacting us!.');
    }else{
            // send back error message
            
            
            return redirect()->back()->with('contactmessage', 'Looks like you are a replicant. Sorry but We do not receive emails from non human entities');
            }

    }
   
    public function logout()
    {
        if(session()->has('jwttoken') && session()->get('jwttoken')!=='no jwt'){
            $tokenString=session()->get('jwttoken');
            $acctoken = SessionTable::where([
                ['status', '=', '1'],
                ['access_token', '=', session()->get('jwttoken')],
                 ])->get()->toArray();
                 if ((count($acctoken)!==0)){ 
                   
                    $id=  $acctoken[0]['id'];
                      //logout
  $myarr=[];
  array_push($myarr,$id ); 
     
     $decremen=count($myarr);// number of id we will use this number to decrement the last value
     $updatedExpireConcrenteUser=SessionTable::whereIn('id', $myarr)->update(['status' => 0,'modified_on' => Carbon::now()]);
     SessionTable::where([
        ['status', '=', '1'],
        ['connected_devices', '!=', '1'],
         ])
->update([
    'connected_devices' => DB::raw('`connected_devices` - 1 ')
]);
config([
  'jwt.blacklist_enabled' => true
]);

try {
    JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($tokenString), $forceForever = false);
    }catch (Exception $e) {
        return redirect()->back()->with('message','Exception occured '.$e->getMessage().'');
    }catch (JWTException $e){
        return redirect()->back()->with('message',''.$e->getMessage().'');
    }
     
                }

        Auth::logout();
        return redirect()->route('home');
        }else{
            Auth::logout();
            return redirect()->route('home');}
    }

    public function rediretToGoogleLogin(){
        return Socialite::driver('google')->redirect();
    }

    public function checkzipAfterGoogleLogin(Request $request){
        $user = $request->only('user');
        $userData = json_decode($user['user'], true);
//          dump($userData);
//  dd($userData['id']);
        $attributeNames = array(
            'zip_code' => 'Zip Code',
         );
         $validator = Validator::make($request->only('zip_code'), [
            'zip_code' => 'required|regex:/^[0-9]{3,7}$/',
            'zip_code'=>'required|exists:zipcodes,zipcode,status,1'
        ]);
         if ( $validator->fails() ) {
            return redirect()->route('home')->withErrors($validator)->withInput();
        } else{
            $role=Role::where('name','user')->first();
             $newUser                    = new User;
            $newUser->provider_name     = 'google';
            $newUser->provider_id       = $userData['id'];
            $newUser->name              = $userData['name'];
            $newUser->email             = $userData['email'];
            $newUser->email_verified_at = now();
            $newUser->is_active = 1;
            $newUser->avatar            = $userData['avatar'];
            $newUser->mode            = 'Live';
            // dd($newUser);
            $newUser->save();

            $newUser->roles()->sync($role->id);
            auth()->login($newUser, true);
            
        return redirect('/');
        }
    }

    public function checkzipView()
    { 
        if( Auth::check()){
            $login = true;}else{ $login = false; }
        $user = session()->get( 'user' );

        return view('frontend.checkzip', compact('login','user')); }
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login');
        }

        $existingUser = User::where('email', $user->getEmail())->first();
        $role=Role::where('name','user')->first();
        if ($existingUser) {
            $userRole= $existingUser->roles->pluck('name');
            
                if($userRole->isEmpty()){
             RoleUser::create([
                'user_id' => $existingUser->id,
                'role_id' => $role->id,
            ]);
            auth()->login($existingUser, true);
        }else{
            auth()->login($existingUser, true);}
        } else {

return redirect('/checkzip')->with( [ 'user' => $user ] );
           
        }

        return redirect('/');
    }
   
    public function myTestAddToLog()

    {

        LogActivity::addToLog('My Testing Add To Log.');

        return 'log insert successfully.';

    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Http\Response

     */
    
    public function logActivity()

    {

        $logs = LogActivity::logActivityLists();

        return view('logActivity',compact('logs'));

    }

    public function search(Request $request)
    {   $posts = Channel::paginate(8);
        $data = '';
        if ($request->ajax()) {
            foreach ($posts as $post) {
                $data.='<li>'.$post->id.' <strong>'.$post->name.'</strong> : '.$post->stationName??'unknown'.'</li>';
            }
            return $data;
        }

        return view('frontend.search');
    }
    public function mydata(Request $request)
    {   $posts = Channel::orderBy('name', 'asc')->paginate(8);
        // dd($posts);
        // $data = '';
        if ($request->ajax()) {
            $view = view('data',compact('posts'))->render();

            return response()->json(['html'=>$view]);
        }

        return view('frontend.mydata',compact('posts'));
    }

    public function getdataforselect22(Request $request){
        
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts = DB::table('channels')->select('id','name as text')
                ->where('name', 'LIKE',  '%' . $term. '%')
                ->orderBy('name', 'asc')->simplePaginate(10);
           
            $morePages=true;
           $pagination_obj= json_encode($posts);
        //    return $pagination_obj;
           if (empty($posts->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $posts->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        
            return \Response::json($results);
        }
    }
    public function getepg(Request $request){
        
        $starttime = strip_tags($request->input('starttime'));
        $callsign = strip_tags($request->input('callsign'));
        $timeoffset = $request->input('hoursoffset');
        $datenew = date('Y-m-d H:i:s', strtotime('-325 minutes'));
        $datetz = substr($datenew,0,10);

        $timetz = substr($datenew,11,8);
        
        $tz=$timeoffset??'-07:00';
        $rt = '+00:00';

// $callSign='COMEDYP';
        // if ($request->ajax()) {

            $term = trim($request->term);
         //   $startdatetime = DB::table('gracenote')->select('StartDateTime');
            //$starttz = DB::table('gracenote')->select('StartDateTime');
            $posts = DB::table('gracenote')->select('id','EventTitle as text','StartDateTime')

            ->select(
                DB::raw('gracenote.id as id'),
                DB::raw('gracenote.EventTitle as text'),
                DB::raw('gracenote.StartDateTime as StartDateTime'),
                DB::raw("CONVERT_TZ(gracenote.StartDateTime,'$rt','$tz') as StartDateTimewithTimezon"))
            
            

            //$startdatetime = DB::table('gracenote')->select('StartDateTime');
           

            // DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
            // $record  = Gracenote::select("sub.callSign as CallSign","sub.genre_name as genre_name",
            // DB::raw('concat(id,"") ChanId') ,DB::raw('concat(id,"") ChanNum'),"StationName","$type as url",
            // DB::raw("CONCAT('$imagePath/',`image`) IconURL"))
            // ->selectRaw(" concat( '[', group_concat(json_object( 'Title', EventTitle,'Description', EventDescription,'CatType', CatType,'Category', Category ,'StartTime',DATE_FORMAT(StartDateTimewithTimezon, '%Y-%m-%dT%TZ'),'EndTime',DATE_FORMAT(EndDateTimewithTimezon, '%Y-%m-%dT%TZ'),'TimeZoneName',TimeZoneName ) Order By StartDateTime ), ']') Programs")
            // ->selectRaw(" concat( '[', group_concat(json_object( 'Title' ),']') Programs")
            // // ->fromSub(function ($query) use ($type,$tz) {
            // //     DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle')
            // // )}
            // DB::raw('IFNULL(gracenote.EventTitle,channels.name) Title')
            
                ->where('EventTitle', 'LIKE',  '%' . $term. '%')
                ->whereRaw("CallSign= '$callsign'")
->whereRaw('gracenote.`EndDateTime` BETWEEN DATE_FORMAT(CONCAT("'.$datetz.'", " '.$timetz.'"), "%Y-%m-%d %H:%i:%s")
AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")')

// DB::raw(" CASE gracenote.TimeZoneName
//             WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
//             WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
//             WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
//             ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s'),'+00:00','$tz') end StartDateTimewithTimezon"),

 ->orderBy('gracenote.StartDateTime', 'asc')
 ->simplePaginate(10);
        //    echo $posts;
        //    die;
            $morePages=true;
           $pagination_obj= json_encode($posts);
        //    return $pagination_obj;
           if (empty($posts->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $posts->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        
            return \Response::json($results);
        }
    // }
    public function getdataforselectUser(Request $request){
        
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts = DB::table('users')->select('id','name as text')
                ->where('name', 'LIKE',  '%' . $term. '%')->where('is_active','=','1')
                ->orderBy('name', 'asc')->simplePaginate(10);
           
            $morePages=true;
           $pagination_obj= json_encode($posts);
        //    return $pagination_obj;
           if (empty($posts->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $posts->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        
            return \Response::json($results);
        }
    }
    public function find(Request $request)
    {
        $term = trim($request->q);

        if (empty($term)) {
            return \Response::json([]);
        }

        $tags = Channel::where('name', 'LIKE', '%'.$term.'%')->limit(5)->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
        }

        return \Response::json($formatted_tags);
    }

    public function search2(Request $request)
    {
      $genre = Genre::get();
      $channels = Channel::select('url','name',DB::raw('IFNULL(stationName,name) stationName'))
      ->where('status','=','1')
      ->orderBy('name', 'asc')
      ->get();

      $sortBy = 'id';
    $orderBy = 'desc';
    $perPage = 20;
    $q = null;

    if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
    if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
    if ($request->has('perPage')) $perPage = $request->query('perPage');
    if ($request->has('q')) $q = $request->query('q');

    $users = Gracenote::search($q)->orderBy($sortBy, $orderBy)->paginate($perPage);
        return view('frontend.search2',compact('genre','channels','users', 'orderBy', 'sortBy', 'q', 'perPage'));
    }
    public function autocomplete(Request $request)
    { $user=Auth::user();
//          $search = request()->get('search');
//          $data = Channel::select("callSign")
//         ->where('callSign', 'LIKE', "%{$search}%")        
//                 ->get();
//                 $result = json_encode($data ); 
//    return $result;
        // return response()->json($data);
        $q=$request->search;
        if($request->ajax()) {
            $record = Channel::select('channels.id', 'channels.callSign','channels.image', 'channels.url','channels.name')
        
     ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
     
    //  ->join('gracenotechannels', 'channels.callSign', '=', 'gracenotechannels.CallSign')
     ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
     ->where(function($query) use ($q) {
        $query->where('channels.callSign', 'LIKE', '%'.$q.'%')
            
            ->orWhere('channels.name', 'LIKE', '%'.$q.'%');
    })
    //  ->where('channels.callSign', 'LIKE',  '%'.$request->search.'%')
     
 ->groupBy('channels.id','channels.name') ->orderBy('channels.callSign', 'asc');
       
      $data=$record->get();
            // $data = Channel::where('callSign', 'LIKE', $request->search.'%')
            //     ->get();
           
            $output = '';  $FRONTEND_URL=env('FRONTEND_URL');
            
            if (count($data)>0) {
              
                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';
              
                foreach ($data as $row){
                   
                if (!empty($row->callSign)){
                    $url=$FRONTEND_URL.'/livetv/'.$row->callSign.'/'.$row->genrename['0'];}else{
                        $url=$FRONTEND_URL.'/livetv/'.$row->name.'/'.$row->genrename['0']; 
                    }

                //     $output .= '<li class="list-group-item">
                //     '.$row->callSign.'<div class="live-news-item">
                //     <a href='.$url.'>
                //     <img src='.$row->logo.'>
                //     <p>'.$row->callSign.'</p></a>
            
                // </div>
                //     </li>';
                    
                    $output .= '<li class="list-group-item">
                    <div class="dddd">
                    <a href='.$url.'>
                    <img style="	display: block;
                    width: auto;
                    margin: 0 auto;
                    height: 1.5rem;" src='.$row->logo.'>
                    '.$row->callSign.'
                   
                    </a>
                    '.$row->name.'
                </div>
                    </li>';


                    // <a href="{{url('livetv/'.$channeldet->callSign.'/'.$channeldet->genrename['0'])}}">
					// 	<img src="{{ $channeldet->logo }}">
                    // 	<p>{{  $channeldet->callSign}}</p></a>
                    // <div class="live-news-item">
					// 	<a href="https://b9a0-110-235-237-173.ngrok.io/livetv/AETVP/Others">
					// 	<img src="https://b9a0-110-235-237-173.ngrok.io/uploads/channellogo/AETV.png">
					// 	<p>AETVP</p></a>
				
					// </div>
                }
              
                $output .= '</ul>';
            }
            else {
             
                $output .= '<li class="list-group-item">'.'No results'.'</li>';
            }
            if (!$user->subscribed('main')) {
                return '<li class="list-group-item">you are not subscribed</li>';
            } 
            return $output;
        }
    }


    public function passcodeLogin(Request $request){
        
        $deviceDetails	= getDeviceIdforAPI($request);
        $passcode= $request->only('passcode');
        $uid=UnHashUserID($passcode['passcode']);
        if(!$uid) {
            return redirect()->back()->withInput()->withErrors(['passcode' => 'User not found']);
        }

        $user = User::find($uid);

        if($user->email_verified_at==null){
            return redirect()->back()->withInput()->withErrors(['passcode' => 'Email not verfied, verify your email first.']);
        }

        if($user->is_active==0){
            return redirect()->back()->withInput()->withErrors(['passcode' => 'You are deactivated, kindly contact admin.']);
        }

        Auth::login($user);
        $userRole= $user->roles->pluck('name');
        if($user->forced_change == 1) {
            return redirect('/update/password');
        } elseif( (@$userRole[0]== 'admin' || @$userRole[0]== 'superadmin')) {
            return redirect('/admin');
        } else {
            return redirect('/');
        }
    }
}


