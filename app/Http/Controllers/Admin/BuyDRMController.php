<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyDRMController extends Controller
{   
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['fairPlayernotoken','WidevinePlayer','WidevinePlayer2','WidevinePlayer3','fairPlayer','fairPlayer2','verify','support','privacypol','termofuse','proxyServer','do_post','WidevinePlayer4']]);
    }


    public function index(){
        
        
     
        try {
          $encoded_auth_xml = getKeyOSAuthToken();
            $response = [
                'success' => true,
                'response_code'=>200,
                'data' => $encoded_auth_xml,
                'widevinelicense'=>'https://wv-keyos.licensekeyserver.com/',
                'playreadylicense'=>'https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx',

                'message' => 'DRM authentication key successfully retreived.'
            ];
    
            return response()->json($response, 200);
          }
          catch(Exception $e) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => $e->getMessage(),
                'response_code'=>404,
            ];
            return response()->json($response, 404);
          }
    }

    public function WidevinePlayer2()
    {  
            
     
        try {
          $encoded_auth_xml = getKeyOSAuthToken();
            return view('frontend.drm.widevineplayready2', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    } 

    public function WidevinePlayer3()
    {  
            
     
        try {
          $encoded_auth_xml = getKeyOSAuthToken();
         
            return view('frontend.drm.widevineplayready3', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    }  
     public function WidevinePlayer4()
    {  
            
     
        try {
          $encoded_auth_xml = getKeyOSAuthToken();
         
            return view('frontend.drm.widevineplayready4', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    }  
     public function WidevinePlayer()
    {  
          
     
        try {
          $encoded_auth_xml = getKeyOSAuthToken();
         
            return view('frontend.drm.widevineplayready', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    }  

    public function fairPlayer()
    {  
        
     
        try {
          $encoded_auth_xml = getKeyOSAuthToken();
         
            return view('frontend.drm.fairplay', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    } 
    public function fairPlayernotoken()
    {  
        
     
        try {
          $encoded_auth_xml = getKeyOSAuthToken();
         
            return view('frontend.drm.fairplayproxy', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    } 
    public function fairPlayer2()
    {  
            
     
        try {
          $encoded_auth_xml = getKeyOSAuthToken();
         
            return view('frontend.drm.fairplay2', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    } 

    public function proxyServer(){

  // License challenge/payload.
  $payload = '';

  // Get the challenge/payload string that player sent to this script.
  if (($payload = file_get_contents('php://input')) === false) {
    exit('Could not read POST data.');
  }

  if (empty($payload)) {
    exit('`Challenge/payload` is empty.');
  }

  // Get DRM type.
  $drm_type = '';
  if (array_key_exists('HTTP_X_KEYOS_DRM_TYPE', $_SERVER)) {
    $drm_type = $_SERVER['HTTP_X_KEYOS_DRM_TYPE'];
  }
  
  if (empty($drm_type) && array_key_exists('drm-type', $_GET)) {
    $drm_type = $_GET['drm-type'];
  }

  if (empty($drm_type)) {
    exit('Neither `x-keyos-drm-type` header, nor `drm-type` query param set.');
  }

  $drm_type = strtolower($drm_type);

  // Alter license acquisition URLs if needed
  if ($drm_type == 'widevine') {
    $la_url = 'https://wv-keyos.licensekeyserver.com/';
  }
  else if ($drm_type == 'playready') {
    $la_url = 'https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx';
  }
  else if ($drm_type == 'fairplay') {
    $la_url = 'https://fp-keyos.licensekeyserver.com/getkey';
  }
  else {
    exit('Neither `x-keyos-la-url` header, nor `la-url` query param set. Can\'t determine license acquisition URL.');
  }

  // The SERVICE_CERTIFICATE_REQUEST is a 2 byte payload in the license acquisition request: 0x0804 (CAQ=).
  if (strtolower($drm_type) === 'widevine' && strlen($payload) === 2 && unpack('H*', $payload)[1] === '0804') {
    $headers = array(
      'Content-Type: text/html; charset=utf-8'
    );

    $response = do_post($la_url, $payload);

    // Return what license service responsed back to the client.
    exit($response);
  }

  $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
  try {    
    // Create authentication XML object instance.
    $auth_xml_generator = new \KeyOSAuthXMLGenerator($certificate);

    // Set authentication XML expiration time in UTC. For production you would want to set this value to time enough to acquire
    // a license (1 minute or less). For test purposes especially if you don't regenerate the authentication XML and use
    // it as static value on your HTML page (which you should not do in production), you can set the time to a day or week etc...
    $auth_xml_generator->setAuthXMLExpirationTime('+1 day');
	
    // Settings for PlayReady license.
    $lic_simple = $auth_xml_generator->newSimpleLicense();

    // Settings for Widevine license.
    $wv_lic = $auth_xml_generator->newWidevineLicense();

    // Settings for FairPlay license.
    $fp_lic = $auth_xml_generator->newFairPlayLicense();

    // Generate, sign and Base64-encode authentication XML
    $generated_auth_xml = $auth_xml_generator->generateAuthenticationXML();
    $signed_auth_xml = $auth_xml_generator->signAuthenticationXML($generated_auth_xml);
    $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($signed_auth_xml);
  }
  catch(Exception $e) {
    exit ('Error: ' . $e->getMessage());
  }

  // POST Headers may be different depending on the DRM type.
  if (strtolower($drm_type) === 'playready') {
    // For PlayReady license acquisition.
    $headers = array(
      'customdata:' . $encoded_auth_xml,
      'msprdrm_server_redirect_compat: false',
      'msprdrm_server_exception_compat: false',
      'soapaction: http://schemas.microsoft.com/DRM/2007/03/protocols/AcquireLicense',
      'Content-Type: text/xml'
    );
  }
  else {
    $headers = array('customdata:' . $encoded_auth_xml);
  }
  
  // Make a request to the license service.
  $response = $this->do_post($la_url, $payload, $headers, $encoded_auth_xml);

  // Return license service response back to the client.
  exit($response);
    }

     // Local functions.
  public function do_post($la_url, $payload, $headers = array(), $encoded_auth_xml = '') {
    // Prepare a POST request to the license service.
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $la_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $payload,
      CURLOPT_HTTPHEADER => $headers,
    ));

    // Make the POST request and get the response from the license service.
    $response = curl_exec($curl);

    // Check for an error just in case.
    $curl_error = curl_error($curl);

    // Closing connection.
    curl_close($curl);

    // Working with results.
    if (empty($curl_error) == false)
      exit('CURL Error: ' . $curl_error);

    if ($response === false)
      exit('CURL request failed.');

    return $response;
  }
}
