<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use KeyOSAuthXMLGenerator;

class BuyDRMController extends Controller
{   
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login','register','verify','support','privacypol','termofuse']]);
    }


    public function index(){
        $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
        
     
        try {
            // Create authentication XML generator instance.
            $auth_xml_generator = new KeyOSAuthXMLGenerator($certificate);
            $auth_xml_generator->AuthXMLExpirationTime('+2 minutes');
          
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
}
