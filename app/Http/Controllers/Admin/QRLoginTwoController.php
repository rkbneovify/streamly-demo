<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Memcache;
class QRLoginTwoController extends Controller
{
    public  function CreateQrcodeAction(){
      //   ob_start ();
        // echo url()->current();
  
        $url ='http://'. $_SERVER ['HTTP_HOST']; // Get the current url 
        // dd($url);
       $http = $url .'/api/login/mobile/scan/qrcode'; // Verify the url method of scanning code 
       $key = Str::random(30);//$this->getRandom(30); // The key value stored in memcache, a random 32-bit string 
       $random = mt_rand(1000000000, 9999999999);//random integer
       $_SESSION['qrcode_name'] = $key ; // Save the key as the name of the picture in the session 
       $forhash=substr( $random,0,2);
       $sgin_data = HashUserID($forhash); // The basic algorithm for generating the sign string 
       
       $sgin =strrev(substr($key,0,2)).$sgin_data ; // Intercept the first two digits and reverse them 
       
       $value = $http .'?key='. $key .'&type=1'; // Two-dimensional Code content 
      $pngImage = QrCode::format('png')
      //  ->merge(public_path('frontend/img/streamly-logo.png'), 0.3, true)
       ->size(300)->errorCorrection('H')
       ->generate($value, public_path('assets/img/qrcodeimg/'. $key .'.png'));
     
        $return = array ('status'=>0,'msg'=>'' );
        
        $qr = public_path('assets/img/qrcodeimg/'. $key .'.png');
      //   $qr = asset('assets/img/qrcodeimg/'. $key .'.png');
      //   dump($qr);
     if (!file_exists($qr)) {
      $return = array ('status'=>0,'msg'=>'' );
      return response()->json($return, 404); 
      //   return "no found qr img";
     }
     $qr = asset('assets/img/qrcodeimg/'. $key .'.png');
     
      $mem = new \Memcache();
      $mem->connect('127.0.0.1',11211 );
      $res=json_encode(array('sign'=> $sgin ,'type'=>0 ));
      // store in memcache, expiration time is three minutes 
     $mem->set($key,$res ,0,180);// 180 
     $return = array('status'=>1,'msg'=> $qr,'key'=>$key);
       return response()->json($return, 200); 
      //  return  $this ->createJsonResponse( $return );
   }

   /* *
     * Check whether the code has been scanned, this checked by polling, 
     * it looks for the key, the filename, 
     */ 
    public  function isScanQrcodeAction(Request $request){

      $key = $request['key'];
       $mem = new \Memcache();
       $mem->connect('127.0.0.1',11211 );
       $data = json_decode($mem->get($key),true);
       if (empty($data)){
           $return = array ('status'=>2,'msg'=>'expired' );
      } else {
           if ($data['type']){
               $return = array ('status'=>1,'msg'=>'success' );

          } else {
               $return = array ('status'=>0,'msg'=>'' );
          }
      }
      return response()->json($return, 200); 
      // return  $this->createJsonResponse( $return );
  }

  // Mobile device scan code 
  public function mobileScanQrcodeAction(Request $request ){
   $key = $_GET['key'];
   $url ='http://'.$_SERVER ['HTTP_HOST'];
   $agent = $_SERVER ["HTTP_USER_AGENT"];
   $headerpasscode = $request->header('userpasscode');
  //  if (!(strpos($agent,'MicroMessenger') === false )) {
  //      // Get the version number
  //     //preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $agent, $matches); 
  //     $app_url =' http : //club.risecenter.com/ wap_app.html' ;
  //      // WeChat browser, jump to the download APP page (you can judge that non-designated app browsers will jump to this page) 
  //     return  $this ->redirect( $app_url );
  // }
  $http = $url .'/api/login/qrcodedoLogin'; // Return to confirm the login link 
  $mem = new \Memcache();
   $mem->connect('127.0.0.1',11211);
   $data = json_decode($mem->get($key), true);
   
   if (empty($data)){
    $return = array ('status'=>2,'msg'=>'expired' );
    return response()->json($return, 200);
} 
   $data['type']=1; // Increase the type value to determine whether the code has been scanned 
  $res = json_encode($data);
   $mem->set($key,$res,0,180);
   $http = $http .'?key='.$key.'&type=scan&login='.$headerpasscode.'&sign='.$data['sign'];
   $return = array ('status'=>1,'msg'=> $http );
  //  return  $this ->createJsonResponse( $return );
   return response()->json($return, 200);
}

/* *
     * Log in after the client scans the code
     * $sign passes the identification when the client scans the code, and compares it with the memcache
     * $key is the key passed in the QR code on the web page
     * $uid The user id sent by scanning the code after the client logs in
     * @return void
     */ 
    public  function qrcodeDoLoginAction(Request $request ){

      $login=$_GET['login'];//jwt or passcode
       $key = $_GET['key'];
       $sign = $_GET['sign'];
       $mem = new \Memcache();
       $mem->connect('127.0.0.1',11211);
       $data = json_decode($mem->get($key),true); // Remove the value of memcache 
       if (empty($data)){
        $return = array ('status'=>2,'msg'=>'expired' );
        return response()->json($return, 200);
    } else {
      if (!isset($data['sign'])){
        $return = array('status'=>0,'msg'=>'Sign notset' );
      }
      if ($data['sign']!= $sign ){ // Verify delivery Sign 
        $return = array('status'=>0,'msg'=>'Verification Error' );
        //  return  $this ->createJsonResponse( $return );
         return response()->json($return, 403);
    } else {
         if ($login){ // Mobile phone scan code webpage login, save the user name in memcache

            $data['jwt'] = $login ;
             $res = json_encode($data);
             $mem->set($key,$res,0,180);
             $return = array('status'=>1,'msg' =>'Login successful' );
            //  return  $this ->createJsonResponse( $return );
             return response()->json($return, 200);
        } else {
             $return = array ('status'=>0,'msg'=>'Please pass the correct user information' );
            //  return  $this ->createJsonResponse( $return );
             return response()->json($return, 401);
        }
    }
    }

  }
  public function loginEntry(Request $request)
  {
    
     $key=$request['key'];
     if (empty($key)){

     $return = array ('status'=>2,'msg'=>'key not provided' );
     return response()->json($return, 200);
     }
    $mem = new \Memcache();
    $mem->connect('127.0.0.1',11211);
    $data = json_decode($mem->get($key),true); 
//     $passcode=$data['login'];
    if (empty($data)){
     $return = array ('status'=>2,'msg'=>'expired' );
     return response()->json($return, 200);
 } else {
     if (isset($data['jwt'])){
         $return = array ('status'=>1,'msg'=>'success','jwt'=>$data['jwt'] );
         return response()->json($return, 200);
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
// Click button to geerate qr code for 3 minutes
// Waits for scan code,poll wether qr is scanned or not
// Scan the code via mobile,visit the scan link
// php function saves the scanned code identification
// returns scan code status  to ajax poll method,
// saying code is scanned succesfully, a ajax poll goes whether login is confirmed
// when triggering login send jwt and generate a new one and send it to frontend
