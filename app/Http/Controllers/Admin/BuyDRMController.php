<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyDRMController extends Controller
{   
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['WidevinePlayer','WidevinePlayer2','WidevinePlayer3','fairPlayer','fairPlayer2','verify','support','privacypol','termofuse']]);
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
        
        
    }   public function WidevinePlayer()
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
}
