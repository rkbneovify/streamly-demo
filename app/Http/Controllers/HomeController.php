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
use ctwillie\Usps\Address;
use App\Models\FrontendText;
use App\Models\AddressesCheckAvailability;
// use GuzzleHttp\Client;
use App\SessionTable;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use App\Http\Requests\ContactFormRequest;
class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    { $plans = \Stripe\Plan::all(['active'=>true]);
        $imagePath=asset("/uploads/channellogo/notfound.png");
        $record = Channel::select('channels.id', DB::raw('IFNULL(channels.callSign,channels.name) callSign'),'channels.image', 'channels.url')
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
        
        ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
  
    ->orderBy('callSign', 'asc')
    ->groupBy('channels.id');
       
       
       $pagechannel=$record->get()->reject(function($element) use($imagePath) {
        return $element->logo == $imagePath;
    }); 


    $record2 = Channel::select('channels.id', DB::raw('IFNULL(channels.callSign,channels.name) callSign'),'channels.image', 'channels.url')
        
    ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
    
    
    ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
//     ->whereIn('channels.callSign', function($query)
// {
//     $query->select('CallSign')
//           ->from('gracenotechannels');
          
// })
->groupBy('channels.id') ->orderBy('channels.callSign', 'asc');
   

   
  $pagechannel2=$record2->get()->reject(function($element) use($imagePath) {
    return $element->logo == $imagePath;
})->groupBy('genrename');
       
        //$plans=$plans->data;
        if( Auth::check()){
            $login = true;
            $user=Auth::user();
            if (!$user->subscribed('main')) {
                return redirect('/selectplan');
            } else {   return redirect('/livetv');}
        } else {
            $login = false;
        }

        return view('frontend.home', compact('login','plans','pagechannel2','pagechannel'));
    }

    public function botman(){
        return view('frontend.botman'); 
    }
    
   /* public function location(Request $request)
    {     
        $LAT= $request['latitude'];
        $LON = $request['longitude'];
        try {
        $client = new \GuzzleHttp\Client();
        // $url = "http://nominatim.openstreetmap.org/reverse?email=myemail@myserver.com&format=xml&lat=-23.56320001&lon=-46.66140002&zoom=27&addressdetails=1";
        $response = $client->get('http://nominatim.openstreetmap.org/reverse?email=contact@streamlytv.com&format=json&lat='.$LAT.'&lon='.$LON.'&zoom=27&addressdetails=1');
       
       $jsonfile=$response->getBody();
       if (!json_decode($jsonfile, TRUE)) {return false;}
       else{
        $resp = json_decode($jsonfile, true);
    }
       // $data = json_decode($jsonfile, true); 
        // return $resp; exit;
         if( isset($resp['address']['postcode'] ) ){
            $zipcode= $resp['address']['postcode'];
            $nameArr = ['zipcode' => $zipcode];
            $validator = Validator::make($nameArr, 
        [
            'zipcode' => 'required|exists:zipcodes,zipcode,status,1'
        ], 
        [
            'zipcode.exists'=>'You are away from the zipcode proximity'
        ]
    );
    if ($validator->fails()) {
        return $validator->errors();
    }
         
        return $resp;
        }
        return $resp;
        echo "NO zipcode found do radius search";
        $Alldata = Zipcode::select('zipcode', 'latitude', 'longitude')->where('status',1)->get();
        foreach($Alldata as $data){
           echo $data->latitude; echo "\n";echo "nnnj";
           computeDistance($data->latitude, $data->longitude, $resp['lat'], $resp['lon'], $radius = 6378137);
           exit;
        }

       // computeDistance($lat1, $lng1, $lat2, $lng2, $radius = 6378137);
        
        // return $resp;
    }
    catch (\GuzzleHttp\Exception\RequestException $e) {
       
        
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            // var_dump($response->getStatusCode()); // HTTP status code;
            // var_dump($response->getReasonPhrase()); // Response message;
            return $response->getBody(); // Body, normally it is JSON;
            // var_dump(json_decode((string) $response->getBody())); // Body as the decoded JSON;
            // var_dump($response->getHeaders()); // Headers array;
            // var_dump($response->hasHeader('Content-Type')); // Is the header presented?
            // var_dump($response->getHeader('Content-Type')[0]); // Concrete header value;
        }
        else {return   json_encode($e->getMessage(), true);}
    } catch (Exception $ex) {
        
        return json_encode($ex->getMessage(), true);
       
   }

        // $response = new GuzzleHttp\Client();('http://nominatim.openstreetmap.org/reverse?json_callback=cb&format=json&lat=28.7441&lon=77.0732&zoom=27&addressdetails=1');
        
    } */
    public function geofence(Request $request)
    {     
        $LAT= $request['latitude'];
        $LON = $request['longitude'];
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:6750/']);
        $response=$client->request('GET', 'geofenceturf', [
            'query' => ['lat' => $LAT,'lon'=>$LON]
        ]);
        
        
       $jsonfile=$response->getBody();
       $resp = json_decode($jsonfile, true);

if($resp['response_code']=='200'){
return response()->json($resp, 200); 

}
 else{
    return response()->json($resp, 403); 
 }
      
    
        
   }

   /* public function oldgeofence(Request $request)
    {     
        $LAT= $request['latitude'];
        $LON = $request['longitude'];
    
      $query= Zipcode::select('map.zcta5ce10 AS zipcode')
        ->whereRaw("ST_Contains( map.SHAPE, ST_GeomFromText( 'POINT(".$LAT.' '.$LON." )', 4683 ) )")
        ->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->where('zipcodes.status',1)->get();
      
        if((count($query)==0)){
            return response()->json([
                'success' => false,
                'message' => 'Not within zipcode boundary',
                
                'response_code'=>403,
                
            ], 403);
        } else{
            $zipcode=$query->pluck('zipcode');
     
    $response = [
        'success' => true,
        'data' => $zipcode[0],
        'message' => $zipcode[0].' Zipcode is allowed.','response_code'=>200,
    ];
    return response()->json($response, 200); 
        }
    
        
   } */

   public function simplequeryrun(Request $request){
    $LAT= $request['latitude'];
    $LON = $request['longitude'];
//    DB::raw("ST_AsGeoJSON(`SHAPE`) sh"))http://geojson.tools/
      $query= Map::select('zcta5ce10 AS zipcode',DB::raw("ST_AsGeoJSON(`SHAPE`) shape"))->whereRaw("ST_Contains( map.SHAPE, ST_GeomFromText( 'POINT(".$LAT.' '.$LON." )', 4683 ) )")
     ->get();
    //  $data = Zipcode::select('zipcodes.*')->where('zipcodes.status',1)->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->toSql();
     $response = [
        'success' => true,
        'data' => $query,
        // 'sds' => $data,
        'response_code'=>200,
    ];
    return response()->json($response, 200); 
 }

 public function allowedZipcode(Request $request){
    $data = Zipcode::select('zipcodes.*',DB::raw("ST_AsGeoJSON(map.`SHAPE`) shape"))->join('map', 'zipcodes.zipcode', '=', 'map.zcta5ce10')->where('zipcodes.status',1)->get();
    # Build GeoJSON feature collection array
$geojson = array(
    'type'      => 'FeatureCollection',
    
    // 'crs' => htmlspecialchars(json_encode($crs), ENT_QUOTES, 'UTF-8'),
    'features'  => array()
 );
    if($data->isEmpty()){
        $response = [
            'success' => false,
            'data' => $data,
            'message'=>'No zipcode is allowed',
            'response_code'=>200,
        ];
        return response()->json($response, 200); 
    }
    
      foreach($data as $fieldnam) {
        $properties =  array(
            'color'=> 'red',
            'title' => $fieldnam['zipcode']
            
        );
        
        $feature = array(
            'type' => 'Feature',
            'properties' => $properties,
            
            'geometry' => $fieldnam->shape,
             
       );
                        
  
    
         array_push($geojson['features'], $feature);
     }
     $response = [
        'success' => true,
        // 'data' => $data,
        'geojson' => $geojson,
        // 'myarr' => $myarr,
        'response_code'=>200,
    ];
    return response()->json($response, 200); 
    // return response()->json($response, 200, [], JSON_NUMERIC_CHECK|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
 }
     
    public function verified()
    {
        

        return view('frontend.verified');
    }
    
    public function checkAvailabilty()
    {
        
$address = new Address([
    'Address2' => '2932 Nutwood',
    'City' => 'Fullerton',
    'State' => 'CA',
    'Zip5' => 92831
]);
$message='';
$response = $address->validate();

if(isset($response['Address']['Error'])){$message=$response['Address']['Error']['Description'];}else{
    dd($response);
    $message=$response['Address'];
}
// if($response['Address']['Error']['Description']=='Address Not Found.  '){print_r($response);} else{print_r($response);}
 
        $login = false; 
        return view('frontend.checkavailbilty',compact('login','response'));
        // return view('frontend.verified');
    }
    public function checkAvailabilty2()
    {
        
$address = new Address([
    'Address2' => '6406 Ivy Lane',
    'City' => 'Greenbelt',
    'State' => 'MD',
    'Zip5' => 20770
]);
$message='';
$response = $address->validate();
if(isset($response['Address']['Error'])){$message=$response['Address']['Error']['Description'];}else{
    // print_r($response);
    $message=isset($response['Address']['ReturnText'])?$response['Address']['ReturnText']:'Seems like valid addres';
}
// if($response['Address']['Error']['Description']=='Address Not Found.  '){print_r($response);} else{print_r($response);}
// dd($response);
        $login = false; 
        return view('frontend.checkavailbilty2',compact('login','response'));
        // return view('frontend.verified');
    }

    public function addressCheck(Request $request)
    { //dd($request);
        
        if($request->has('Address2')){
            
            
            try {
             
$address = new Address([
    'Address2' => $request->input('Address2'),
    'City' => $request->input('City'),
    'State' => $request->input('State'),
    'Zip5' => $request->input('zip')
]);
$message='';
$response = $address->validate();
if(isset($response['Address']['Error'])){$message=$response['Address']['Error']['Description'];
    return response()->json(['message' => $message], 404);}else{
    // print_r($response);
    $message=isset($response['Address']['ReturnText'])?$response['Address']['ReturnText']:'Seems like valid address';
    //todo insert into address logic
    $AddressesCheckAvailability = AddressesCheckAvailability::updateOrCreate([
        'address2' => $response['Address']['Address2'],
        'city' => $response['Address']['City'],
        'state' => $request->input('State'),
        'zip5'=>$response['Address']['Zip5']
    ]);

    //$request->input('Address2').$request->input('City').$request->input('State').$request->input('zip')
    return response()->json(['message' => $message], 200);

}

            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
         
        }
        
    }
    public function contactform()
    {
        if( Auth::check()){
            $login = true;}else{ $login = false; }
       
        return view('frontend.contactform',compact('login'));
        // return view('frontend.verified');
    } 
    
    public function showSupportedDevices(){
       
        if( Auth::check()){
            $login = true;}else{ $login = false; }
       
        return view('frontend.supporteddevice',compact('login'));
        
    }
    
    public function helpandsupp()
    {
        
        $login = false; 
        $frontendjson = FrontendText::find(1)->frontendjson;
        $supportemail=$frontendjson['supportemail']??'contact@streamlytv.com';
       $supportphone= $frontendjson['supportphone']??'1234656477';
       $supportphone2= $frontendjson['supportphone2']??'773727828';
       $message= $frontendjson['supportapitext']??'For help and support contact on email below';
        // return view('frontend.contactform',compact('login'));
        return view('frontend.helpandsupport',compact('login','supportemail','supportphone','supportphone2','message'));
        // return view('frontend.verified');
    }

    public function createUser(Request $request){

        $attributeNames = array(
           'user-email' => 'Email',
           'user-password' => 'Password',
           'name' => 'Name',
           'zip_code' => 'Zip Code',
        );

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'user-email' => 'required|string|email|max:255|unique:users,email',
            'user-password' => 'required|string|min:8',
            'zip_code' => 'required|regex:/^[0-9]{3,7}$/',
            'zip_code'=>'required|exists:zipcodes,zipcode,status,1',
            'g-recaptcha-response'=>'required'
        ], ['zip_code.exists'=>'Service not available at this zipcode or your zipcode is not valid'], $attributeNames);

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

        Thank you for registering on '.env('APP_NAME').'.

        Here is your account verification link. Click on below link to verify you account. <br><br><a href="'.$account_verification_link.'" target=_blank >'.$account_verification_link.'</a>
    </html>';

    try{
        Mail::send('emails.general',["html"=>$html] , function($message) use ($request){
            $message->to($request['user-email'], $request['name'])
            ->subject(env('APP_NAME').': Account Verification');
            // return redirect()->route('home')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
       
       
    
        });

        $User = User::create([
            'name' => $request['name'],
            'email' => $request['user-email'],
            'password' => Hash::make($request['user-password']),
            'zipcode' => $request['zip_code'],
            'email_verified_at'=>null,
            'is_active'=>0
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

        return redirect('/')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
}else{
    // send back error message
    
    
    return redirect()->back()->with('message', 'Looks like you are a replicant. Sorry but We do not receive emails from non human entities');
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
             return redirect()->back()->with('message', "We've got errors!".json_encode($ex->getMessage(), true));
            // return "We've got errors!".json_encode($ex->getMessage(), true);
        }
    
        return redirect()->back()->with('message', 'Thanks for contacting us!.');
    }else{
            // send back error message
            
            
            return redirect()->back()->with('message', 'Looks like you are a replicant. Sorry but We do not receive emails from non human entities');
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
        return redirect('account/settings')->with('message','Exception occured '.$e->getMessage().'');
    }catch (JWTException $e){
        return redirect('account/settings')->with('message',''.$e->getMessage().'');
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

        
            $newUser                    = new User;
            $newUser->provider_name     = 'google';
            $newUser->provider_id       = $user->getId();
            $newUser->name              = $user->getName();
            $newUser->email             = $user->getEmail();
            $newUser->email_verified_at = now();
            $newUser->is_active = 1;
            $newUser->avatar            = $user->getAvatar();
            $newUser->save();

            $newUser->roles()->sync($role->id);
            auth()->login($newUser, true);
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

    public function search()
    {
        

        return view('frontend.search');
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
					// 	<a href="http://127.0.0.1:8000/livetv/AETVP/Others">
					// 	<img src="http://127.0.0.1:8000/uploads/channellogo/AETV.png">
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

   
    

}
