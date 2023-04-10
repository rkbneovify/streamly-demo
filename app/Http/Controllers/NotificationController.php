<?php 
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use JWTAuth;
//use App\Models\User;
  
class NotificationController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('pushNotification');
    } 
  
     /**
     * Write code on Method
     *
     * @return response()
     */

     public function updateDeviceToken(Request $request)
    {
        Auth::user()->device_token =  $request->token;
        $user = JWTAuth::user();
         $tokenAUTHNEW = session()->get('jwttoken');
        
        if($request->token){
            $userfcmtoken = DB::table('session_table')
                  ->where('access_token', $tokenAUTHNEW)
                  ->update(array(
                  'fcm_token' => $request->token,
                  )); 
        }
    }

    public function sendNotification(Request $request)
    {
        $firebaseToken = array('fhjhe8476584ufjhsdjk');
            
        $SERVER_API_KEY = env('FCM_SERVER_KEY');
    
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body. date("Y/m/d H:i:s"),  
            ]
        ];
        $dataString = json_encode($data);
      
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
      
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                 
        $response = curl_exec($ch);
    
        return back()->with('success', $response);
    }

    public function sendNotificationApp(Request $request)
    {
        $firebaseToken = $request->registration_ids;
            
        $SERVER_API_KEY = env('FCM_SERVER_KEY');
    
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body. date("Y/m/d H:i:s"),  
            ]
        ];
        $dataString = json_encode($data);
      
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
      
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                 
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch);

        return response()->json(['success'=>true,'response_code'=>501,'message' => $result], 501);
    
        // if($result->success ==  0){
        //     return response()->json(['success'=>false,'response_code'=>501,'message' => $result->results], 501);
        // }else{
        //     return response()->json(['success'=>true,'response_code'=>200,'message' => 'Notification send successfully'], 200);
        // }   
    }
}