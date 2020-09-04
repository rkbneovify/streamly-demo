<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use KeyOSAuthXMLGenerator;

class BuyDRMController extends Controller
{   
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['WidevinePlayer','WidevinePlayer2','WidevinePlayer3','fairPlayer','fairPlayer2','verify','support','privacypol','termofuse']]);
    }


    public function index(){
        $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
        
     
        try {
            // Create authentication XML generator instance.
            $auth_xml_generator = new KeyOSAuthXMLGenerator($certificate);
            $auth_xml_generator->AuthXMLExpirationTime('+15 minutes');
          
            // Crate Simple PR license
            $pr_lic = $auth_xml_generator->newSimpleLicense();
          
            // Create WV license
            $wv_lic = $auth_xml_generator->newWidevineLicense();
          
            // Create FP license
            $fp_lic = $auth_xml_generator->newFairPlayLicense();
         
             
            // Generate Authentication XML.
            $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($auth_xml_generator->signAuthenticationXML($auth_xml_generator->generateAuthenticationXML()));
            
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
        $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
        
     
        try {
            // Create authentication XML generator instance.
            $auth_xml_generator = new KeyOSAuthXMLGenerator($certificate);
            $auth_xml_generator->AuthXMLExpirationTime('+15 minutes');
          
            // Crate Simple PR license
            $pr_lic = $auth_xml_generator->newSimpleLicense();
          
            // Create WV license
            $wv_lic = $auth_xml_generator->newWidevineLicense();
          
            // Create FP license
            $fp_lic = $auth_xml_generator->newFairPlayLicense();
         
             
            // Generate Authentication XML.
            $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($auth_xml_generator->signAuthenticationXML($auth_xml_generator->generateAuthenticationXML()));
            
    
         
            return view('frontend.drm.widevineplayready2', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    } 

    public function WidevinePlayer3()
    {  
        $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
        
     
        try {
            // Create authentication XML generator instance.
            $auth_xml_generator = new KeyOSAuthXMLGenerator($certificate);
            $auth_xml_generator->AuthXMLExpirationTime('+15 minutes');
          
            // Crate Simple PR license
            $pr_lic = $auth_xml_generator->newSimpleLicense();
          
            // Create WV license
            $wv_lic = $auth_xml_generator->newWidevineLicense();
          
            // Create FP license
            $fp_lic = $auth_xml_generator->newFairPlayLicense();
         
             
            // Generate Authentication XML.
            $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($auth_xml_generator->signAuthenticationXML($auth_xml_generator->generateAuthenticationXML()));
            
    
         
            return view('frontend.drm.widevineplayready3', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    }   public function WidevinePlayer()
    {  
        $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
        
     
        try {
            // Create authentication XML generator instance.
            $auth_xml_generator = new KeyOSAuthXMLGenerator($certificate);
            $auth_xml_generator->AuthXMLExpirationTime('+15 minutes');
          
            // Crate Simple PR license
            $pr_lic = $auth_xml_generator->newSimpleLicense();
          
            // Create WV license
            $wv_lic = $auth_xml_generator->newWidevineLicense();
          
            // Create FP license
            $fp_lic = $auth_xml_generator->newFairPlayLicense();
         
             
            // Generate Authentication XML.
            $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($auth_xml_generator->signAuthenticationXML($auth_xml_generator->generateAuthenticationXML()));
            
    
         
            return view('frontend.drm.widevineplayready', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    }  

    public function fairPlayer()
    {  
        $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
        
     
        try {
            // Create authentication XML generator instance.
            $auth_xml_generator = new KeyOSAuthXMLGenerator($certificate);
            $auth_xml_generator->AuthXMLExpirationTime('+15 minutes');
          
            // Crate Simple PR license
            $pr_lic = $auth_xml_generator->newSimpleLicense();
          
            // Create WV license
            $wv_lic = $auth_xml_generator->newWidevineLicense();
          
            // Create FP license
            $fp_lic = $auth_xml_generator->newFairPlayLicense();
         
             
            // Generate Authentication XML.
            $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($auth_xml_generator->signAuthenticationXML($auth_xml_generator->generateAuthenticationXML()));
            
    
         
            return view('frontend.drm.fairplay', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    } 
    public function fairPlayer2()
    {  
        $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
        
     
        try {
            // Create authentication XML generator instance.
            $auth_xml_generator = new KeyOSAuthXMLGenerator($certificate);
            $auth_xml_generator->AuthXMLExpirationTime('+15 minutes');
          
            // Crate Simple PR license
            $pr_lic = $auth_xml_generator->newSimpleLicense();
          
            // Create WV license
            $wv_lic = $auth_xml_generator->newWidevineLicense();
          
            // Create FP license
            $fp_lic = $auth_xml_generator->newFairPlayLicense();
         
             
            // Generate Authentication XML.
            $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($auth_xml_generator->signAuthenticationXML($auth_xml_generator->generateAuthenticationXML()));
            
    
         
            return view('frontend.drm.fairplay2', compact('encoded_auth_xml'));
          }
          catch(Exception $e) {
            
            return $e->getMessage();
          }
        
        
    } 
}
