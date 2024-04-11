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
/*add Naresh 15-11-2022*/
use Cookie;
/*End Naresh 15-11-2022*/
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
    public function index(Request $request)
    {
        /*add Naresh 15-11-2022*/
       $this->setCookie($request);
       /*End Naresh 15-11-2022*/
       try {
        $plans = \Stripe\Plan::all(['active'=>true,'product'=>'prod_NcBvdnH9t6G0GY','expand' => ['data.tiers']]);
       } catch(Exception $e) {
        $plans = \Stripe\Plan::all(['active'=>true,'expand' => ['data.tiers']]);
       }

       $imagePath=asset("/uploads/channellogo/notfound.png");

$second=Channel::toBase()->select('channels.id',DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),
DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'), 'channels.image',DB::raw('IFNULL(genre.name,"Others") genrenae'))

       ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
       ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
       ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')

->where('channels.status','=','1')
->orderBy('channels.StationName', 'asc');



$pagechannel=$second->get()
->reject(function($element) use($imagePath) {

    return $element->image === null;
})->groupBy('genrenae');

$Genrekey=$pagechannel->keys();
    //    dd($pagechannel);
        // $plansgg=$plans->data;
        // dd($plansgg);
        if( Auth::check()){
            $login = true;
            $user=Auth::user();
            if (!$user->subscribed('main')) {
                $checkSubscription = json_decode(count($user->subscriptions), true);
                if($checkSubscription > 0){
                    return redirect('/planexpire');
                }else{
                    return redirect('/selectplan');
                }
                // return redirect('/subscription');
            } else {   return redirect('/livetv');}
        } else {
            $login = false;
        }
    //   dump($plans->data);
      $d=collect($plans->data)
      ->groupBy('billing_scheme');
    //   ->where('billing_scheme', 'per_unit')

      $plansdetails=$d->all();
      $plankey=$d->keys();
        return view('frontend.home', compact('login','plans','pagechannel','Genrekey','plansdetails','plankey'));
    }

    /*add Naresh 15-11-2022*/
public function setCookie(Request $request) {
    $UTMSource = $request->UTMSource;
    $UTMCampaign = $request->UTMCampaign;
    $UTMMedium = $request->UTMMedium;
    if($request->has('UTMSource')) {
    $UTMdata_arr = ['UTMSource'=>$UTMSource,'UTMCampaign'=>$UTMCampaign,'UTMMedium'=>$UTMMedium];

    $UTMdata = json_encode($UTMdata_arr);
    $minutes = 60;
    Cookie::queue('UTMdata', $UTMdata, $minutes);
    // $response = new Response('Hello World');
    // $response->withCookie(cookie('UTMdata', $UTMdata, $minutes));
    } else {
      return '';
   }
    //return $response;
}

/*End Naresh 15-11-2022*/

    public function indexCache()
    {
        $stats = Cache::getMemcached()->getStats();
        $getAllKeys = Cache::getMemcached()->getAllKeys();
        dump($getAllKeys);
        $plans = \Stripe\Plan::all(['active'=>true]);
       //for a day cache
           $pagechannel  = Cache::remember('homepagechannel',60*60*24, function () {

            $imagePath=asset("/uploads/channellogo/notfound.png");

            $second=Channel::toBase()->select('channels.id',DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),
            DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'), 'channels.image',DB::raw('IFNULL(genre.name,"Others") genrenae'))

                   ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
                   ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                   ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')

            ->where('channels.status','=','1')
            ->orderBy('channels.StationName', 'asc');

return $second->get()
->reject(function($element) use($imagePath) {

    return $element->image === null;
})->groupBy('genrenae');
          });




          $Genrekey=$pagechannel->keys();
    //    dd($Genrekey);
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
        $stats1 = array_pop($stats);
        return view('frontend.homecache', compact('login','plans','pagechannel','stats1','Genrekey','getAllKeys'));
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


 public function updatePassword(){
    $user = Auth::user();
    $id = $user->id;
    return view('frontend.pages.updatepassword',compact('user','id'));

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

if(Auth::check()){
    $login = true;
} else {
    $login = false;
}
        return view('frontend.checkavailbilty',compact('login','response'));
        // return view('frontend.verified');
    }
    public function checkAvailabilty2()
    {

// $address = new Address([
//     'Address2' => '6406 Ivy Lane',
//     'City' => 'Greenbelt',
//     'State' => 'MD',
//     'Zip5' => 20770
// ]);
// $message='';
// $response = $address->validate();
// if(isset($response['Address']['Error'])){$message=$response['Address']['Error']['Description'];}else{
//     // print_r($response);
//     $message=isset($response['Address']['ReturnText'])?$response['Address']['ReturnText']:'Seems like valid addres';
// }
// if($response['Address']['Error']['Description']=='Address Not Found.  '){print_r($response);} else{print_r($response);}
// dd($response);
if(Auth::check()){
            $login = true;
        } else {
            $login = false;
        }
        return view('frontend.checkavailbilty2',compact('login'));
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
       /**
        * *normal coment
       *! This needs to be changed
       *? think twice before testing this method
       * TODO: Refactor the view
       */
        if( Auth::check()){
            $login = true;}else{ $login = false; }

        return view('frontend.supporteddevice',compact('login'));

    }

    public function helpandsupp()
    {

        if( Auth::check()){
            $login = true;}else{ $login = false; }
        $frontendjson = FrontendText::find(1)->frontendjson;
        $supportemail=$frontendjson['supportemail']??'contact@streamlytv.com';
       $supportphone= $frontendjson['supportphone']??'1234656477';
       $supportphone2= $frontendjson['supportphone2']??'773727828';
       $message= $frontendjson['supportapitext']??'For help and support contact on email below';
        // return view('frontend.contactform',compact('login'));
        return view('frontend.helpandsupport',compact('login','supportemail','supportphone','supportphone2','message'));
        // return view('frontend.verified');
    }

    public function SubscriptionPage(Request $request)
    {
        /*add Naresh 15-11-2022*/
    //    $this->setCookie($request);
       /*End Naresh 15-11-2022*/
        $plans = \Stripe\Plan::all(['active'=>true,'expand' => ['data.tiers'], 'product' => 'prod_NV3xdPjVaNDrwn']);
        // $imagePath=asset("/uploads/channellogo/notfound.png");

         if( Auth::check()){
            $login = true;
            $user=Auth::user();
            if (!$user->subscribed('main')) {
                $checkSubscription = json_decode(count($user->subscriptions), true);
                if($checkSubscription > 0){
                }else{
                    return redirect('/selectplan');
                }
            } else {   return redirect('/livetv');}
        } else {
            $login = false;
            return redirect(env('FRONTEND_URL'));
        }
    //   dump($plans->data);
      $d=collect($plans->data)
      ->groupBy('billing_scheme');
    //   ->where('billing_scheme', 'per_unit')

      $plansdetails=$d->all();
      $plankey=$d->keys();
        return view('frontend.pages.subscription', compact('login','plans','plansdetails','plankey'));
    }

public function PlanRenews(Request $request)
    {
        $plans = \Stripe\Plan::all(['active'=>true,'expand' => ['data.tiers']]);

        if( Auth::check()){
            $login = true;
            $user=Auth::user();
            if (!$user->subscribed('main')) {
                $checkSubscription = json_decode(count($user->subscriptions), true);
                if($checkSubscription > 0){
                }else{
                    return redirect('/selectplan');
                }
            } else {   return redirect('/livetv');}
        } else {
            $login = false;
            return redirect(env('FRONTEND_URL'));
        }

        return view('frontend.pages.subscriptionrenews', compact('login'));
    }

    public function getAllPlans()
    {
        try {
            try {
                $plans = \Stripe\Plan::all(['active'=>true,'product'=>'prod_NcBvdnH9t6G0GY','expand' => ['data.tiers']]);
            } catch(Exception $e) {
                $plans = \Stripe\Plan::all(['active'=>true,'expand' => ['data.tiers']]);
            }

            // Extract metadata for each plan
            $formattedPlans = collect($plans->data)->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'nickname' => $plan->nickname,
                    'amount' => $plan->amount,
                    'currency' => $plan->currency,
                    'interval' => $plan->interval,
                    'metadata' => $plan->metadata,
                    'type'=> $plan->billing_scheme,
                    'tiers' => $plan->billing_scheme == "tiered" ? $plan->tiers : [],
                    'trial_period_days' => $plan->trial_period_days
                ];
            });

            return response()->json([$formattedPlans], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
