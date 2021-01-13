<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use GuzzleHttp\Client as GuzzleClient;
class GoogleAnalyticsController extends Controller
{
    public function index(Request $request){
        $screenResolution = '1920x1080';
        $cid = '1.2222222';
        // $cID=Ua+nowtime;
        $uId=1;
        // GA curl
        $callSign='test1';
        $boolean='false';
        print('v=2&t=event&tid=G-E50Q5Q9J8D&gtm=2oebu0&sr='.$screenResolution.'&ul=en-us&uid='.$uId.'&cid='.$cid.'&en=video_play&_et=20347&ep.call_sign='.$callSign.'&ep.inside_geofence='.$boolean.'');
        exit;
$req = curl_init('https://www.google-analytics.com/collect');

curl_setopt_array($req, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS =>
    'v=2&t=event&tid=G-E50Q5Q9J8D&gtm=2oebu0&sr='.$screenResolution.'&ul=en-us&uid='.$uId.'&cid='.$cid.'&en=video_play&_et=20347&ep.call_sign='.$callSign.'&ep.inside_geofence='.$boolean.''
));
// Send the request
$response = curl_exec($req);
    }

    public function index2(Request $request){
        // dd($request);
        $header = $request->server('HTTP_USER_AGENT');
        $screenResolution = $request->input('sr');
        $callSign=$request->input('callsign');
        $boolean=$request->input('inside_geofence');
        $now = now();
        $now->timestamp;
        $cid = JWTAuth::user()->id.$now->timestamp;
        
        $uId=JWTAuth::user()->id;
        // dd($request);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.google-analytics.com/g/collect?v=2&tid=G-E50Q5Q9J8D&gtm=2oebu0&sr='.$screenResolution.'&ul=en-us&en=video_play&ep.call_sign='.$callSign.'&ep.inside_geofence='.$boolean.'&cid='.$cid.'&uid='.$uId.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Accept: */*',
    'User-Agent: '.$header.'',
    'Content-Type: text/plain;charset=UTF-8' 
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

    }

    public function index3(Request $request){
        $header = $request->server('HTTP_USER_AGENT');
        $screenResolution = $request->input('sr');
        $callSign=$request->input('callsign');
        $boolean=$request->input('inside_geofence');
        $now = now();
        $now->timestamp;
        $cid = JWTAuth::user()->id.$now->timestamp;
        
        $uId=JWTAuth::user()->id;

$headers = [

    "Accept" => "*/*",
    "User-Agent" => ".$header."
];

$client = new GuzzleClient([
    'headers' => $headers
]);


$r = $client->request('POST', 'https://www.google-analytics.com/g/collect?v=2&tid=G-E50Q5Q9J8D&gtm=2oebu0&sr='.$screenResolution.'&ul=en-us&en=video_play&ep.call_sign='.$callSign.'&ep.inside_geofence='.$boolean.'&cid='.$cid.'&uid='.$uId.'');
// $response = $r->getBody()->getContents();
// echo $r->getStatusCode();
if($r->getStatusCode()=='204')
{
$response = array('success' => true,'response_code'=>200,'message'=>'GA event logged successfully.');
return response()->json($response, 200);
    } else{

$response = array('success' => false,'response_code'=>404,'message'=>'404');
return response()->json($response, 404);}
    }
}
/* 
 sr=1920x1080
 ul=en-us
 cid=1198452763.1603112050
 cid=1198452763.1603112050
 _s=1
 dl=https%3A%2F%2Fstreamlytv.com%2Flivetv%2FVICE%2FOthers
dr=https%3A%2F%2Fstreamlytv.com%2Flivetv
dt=play...
sid=1610357018
sct=98
seg=1
en=video_play
_et=20347
ep.call_sign=VICE
ep.inside_geofence=false
*/

//todo
// Clientid
// <p>‘cid’ is used to denote the <a href="https://www.optimizesmart.com/understanding-the-difference-between-client-id-and-user-id/">client ID</a> 
// (ID which anonymously identifies a particular device / browser and is unique to a particular user).</p>

// _s means sesion hit count
// I guess i'll do something like this
// convert in number(user-agent+userid)+timestamp.
// The logic i found on a website is random number+timestamp