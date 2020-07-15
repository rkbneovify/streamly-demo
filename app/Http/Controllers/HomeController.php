<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\User;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Crypt;
use Mail;
use Exception;
use App\Models\Zipcode;
use App\Helpers\LogActivity;
// use GuzzleHttp\Client;

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

        return view('frontend.home', compact('login','plans'));
    }
    
    public function location(Request $request)
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
        /**
         * Here we actually catch the instance of GuzzleHttp\Psr7\Response
         * (find it in ./vendor/guzzlehttp/psr7/src/Response.php) with all
         * its own and its 'Message' trait's methods. See more explanations below.
         *
         * So you can have: HTTP status code, message, headers and body.
         * Just check the exception object has the response before.
         */
        
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
        
    }
    public function verified()
    {
        

        return view('frontend.verified');
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
            'zip_code'=>'required|exists:zipcodes,zipcode,status,1'
        ], ['zip_code.exists'=>'Service not available at this zipcode or your zipcode is not valid'], $attributeNames);

        /*if ( $validatedData->fails() ) {
            return redirect()->back()->withInput()->with( 'errors', $validatedData->errors() );
        }*/

        $User = User::create([
            'name' => $request['name'],
            'email' => $request['user-email'],
            'password' => Hash::make($request['user-password']),
            'zipcode' => $request['zip_code'],
            'email_verified_at'=>null
        ]);

        $role = RoleUser::create([
            'user_id' => $User->id,
            'role_id' => $this->DEFAULT_ROLE_ID,
        ]);


        $verification_code=Crypt::encrypt($User->email);

        $FRONTEND_URL=env('FRONTEND_URL');

        $account_verification_link=$FRONTEND_URL.'/api/auth/verify?token='.$verification_code;
        $html='<html>
            Hi, '.$User->name.'<br><br>

            Thank you for registering on '.env('APP_NAME').'.

            Here is your account verification link. Click on below link to verify you account. <br><br><a href="'.$account_verification_link.'" target=_blank >'.$account_verification_link.'</a>
        </html>';
      try{
        Mail::send('emails.general',["html"=>$html] , function($message) use ($request,$User){
            $message->to($request['user-email'], $User->name)
            ->subject(env('APP_NAME').': Account Verification');
            // return redirect()->route('home')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
        });} catch (Exception $ex) {
             //$ex->getMessage();
             return redirect()->back()->with('message', "We've got errors!".json_encode($ex->getMessage(), true));
            // return "We've got errors!".json_encode($ex->getMessage(), true);
        }

        return redirect('/')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
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

        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser                    = new User;
            $newUser->provider_name     = 'google';
            $newUser->provider_id       = $user->getId();
            $newUser->name              = $user->getName();
            $newUser->email             = $user->getEmail();
            $newUser->email_verified_at = now();
            $newUser->avatar            = $user->getAvatar();
            $newUser->save();

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
    

}
