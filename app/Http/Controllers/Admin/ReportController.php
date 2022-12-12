<?php

namespace App\Http\Controllers\Admin;
use App\SessionTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\Role;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Yajra\DataTables\DataTables;
use App\HotelRoomTable;

class ReportController extends Controller
{     
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userdetails(){
       
         $allSession=SessionTable::with('user')->selectRaw("users_id , count(id) activesession")->where("status",'1')->groupBy('users_id')->get();
        return view('admin.reports.userdetails', compact('allSession'));
       // return $allSession;
    }

    public function channelanalysis(){

         return 'Under Maintenance';
   }

   public function TransactionReports($sort){

    $transaction = DB::table('subscriptions')
    ->select('id','user_id', 'stripe_plan', 'stripe_status', 'trial_ends_at', 'renews_at', 'ends_at', 'created_at')
    ->orderBy('created_at', $sort)
    ->get();

    $array =[];
    $arrayRes1 = [];
    $arrayRes2 = [];
    $arrayRes3 = [];
    $arrayRes4 = [];

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.stripe.com/v1/plans',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Bearer '.env('STRIPE_SECRET').''
  ),
  ));

$response = curl_exec($curl);
$newdataplans = json_decode($response);
curl_close($curl);
$stripeplans = [];
for ($x = 0; $x < count($newdataplans->data); $x++) {
  $plans = [
    'id' => $newdataplans->data[$x]->id,
    'interval' => $newdataplans->data[$x]->interval,
    'interval_count' => $newdataplans->data[$x]->interval_count
  ];

  array_push($stripeplans, $plans);
}
//dd($stripeplans);
    foreach ($transaction as $data){

      if($data->renews_at == null && $data->stripe_status == 'active'){
        $email=DB::table('users')->where('id', $data->user_id)->value('email');
        $name=DB::table('users')->where('id', $data->user_id)->value('name');
        $newUser = ['name' => $name,
                    'email' => $email
                   ];
        array_push($arrayRes1, $newUser);
      }elseif(!empty($data->renews_at) && $data->stripe_status == 'active'){
        $email=DB::table('users')->where('id', $data->user_id)->value('email');
        $name=DB::table('users')->where('id', $data->user_id)->value('name');
        $renewedUser = ['name' => $name,
                    'email' => $email
                   ];
        array_push($arrayRes2, $renewedUser);
      }elseif($data->stripe_status == 'canceled'){
        $email=DB::table('users')->where('id', $data->user_id)->value('email');
        $name=DB::table('users')->where('id', $data->user_id)->value('name');
        $terminatedUser = ['name' => $name,
                    'email' => $email
                   ];
        array_push($arrayRes3, $terminatedUser);
      }else{
        $email=DB::table('users')->where('id', $data->user_id)->value('email');
        $name=DB::table('users')->where('id', $data->user_id)->value('name');
        $overdueUser = ['name' => $name,
                    'email' => $email
                   ];
        array_push($arrayRes4, $overdueUser);
      }

        $userEmail=DB::table('users')->where('id', $data->user_id)->value('email');
        $userZipcode=DB::table('users')->where('id', $data->user_id)->value('zipcode');

        if(!empty($data->renews_at)){
        $time = strtotime($data->renews_at);
        }else{
        $time = strtotime($data->created_at);
        }

        for ($k = 0; $k < count($stripeplans); $k++) {
             if($data->stripe_plan == $stripeplans[$k]['id']){
               $final = date("Y-m-d H:i:s", strtotime("+".$stripeplans[$k]['interval_count'].$stripeplans[$k]['interval']."", $time));
               break;
              }
              else{
                $final = date("Y-m-d H:i:s", strtotime("1 month"));
              }
        }
        
      $arry = [
        'id'=> $data->id ,
        'email'=> $userEmail ,
        'zipcode' => $userZipcode ,
        'stripe_status'=> $data->stripe_status ,
        'start_at'=> $data->created_at ,
        'ends_at'=> $data->renews_at ,
        'cancle_at' => $data->ends_at,
        'trial_ends_at' => $data->trial_ends_at,
        'null'=> $final ,
      ];

      // dd($arry);
      // die();
      array_push($array, $arry);
    };

    $resultCount = [
      'newuser' => $arrayRes1,
      'reneweduser' => $arrayRes2,
      'terminateduser' => $arrayRes3,
      'overdueuser' => $arrayRes4,
      'data' => $array
    ];

    return view('admin.reports.transactionreport', compact('resultCount'));
         
    //return $resultCount;
   }

   public function subscriptiondestroy()
   {  
   //     $uid= Auth::user()->id;
   //     $user = User::find($uid);
   
   //     try{
   //     $user->subscription('main')->skipTrial()->cancelNow();
   //     return redirect('account/settings')->with('message','Subscription Cancel Successfully');
   // }catch( Exception $e ){
   //     return redirect('account/settings')->with('error','Error'.json_encode($e->getMessage(), true)); }
       

       return '1';
   }

   public function zipcode()
   { 
    
     $zipCode=DB::table('zipcodes')
     ->select('id','zipcode','city', 'state', 'county_area', 'status')
     ->orderBy('status', 'DESC')
     ->simplePaginate(125);

     $activezipCode=DB::table('zipcodes')
     ->where('status', '1')
     ->select('id','zipcode','city', 'state', 'county_area', 'status')
     ->get();

     $arrayactive =[];
     foreach ($activezipCode as $activedata){
 
        // $userEmail=DB::table('users')->where('id', $data->user_id)->value('email');
         $useractiveZipcode=DB::table('users')->where('zipcode', $activedata->zipcode)->get();
 
         $Activecount = count($useractiveZipcode);
         if($activedata->status == 1){
           $activezipcodeStatus = 'Active';
         }else{
           $activezipcodeStatus = 'Inactive';
         }
         
       $arryactive = [
         'zipcode' => $activedata->zipcode,
         'city' => $activedata->city,
         'state' => $activedata->state,
         'country' => $activedata->county_area,
         'status' => $activezipcodeStatus,
         'userCount'=> $Activecount
       ];
       array_push($arrayactive, $arryactive);
     };

     $array =[];
    foreach ($zipCode as $data){

       // $userEmail=DB::table('users')->where('id', $data->user_id)->value('email');
        $userZipcode=DB::table('users')->where('zipcode', $data->zipcode)->get();

        $count = count($userZipcode);
        if($data->status == 1){
          $zipcodeStatus = 'Active';
        }else{
          $zipcodeStatus = 'Inactive';
        }
        
      $arry = [
        'zipcode' => $data->zipcode,
        'city' => $data->city,
        'state' => $data->state,
        'country' => $data->county_area,
        'status' => $zipcodeStatus,
        'userCount'=> $count
      ];
      array_push($array, $arry);
    };

    $key_values = array_column($array, 'userCount'); 
    array_multisort($key_values, SORT_DESC, $array);

    $key_activevalues = array_column($arrayactive, 'userCount'); 
    array_multisort($key_activevalues, SORT_DESC, $arrayactive);

    $resultsData = [
      'current_page' => $zipCode->currentPage(),
      'count' => $zipCode->count(),
      'next_page_url' => $zipCode->nextPageUrl(),
      'hasMorePages' => $zipCode->hasMorePages(),
      'lastItem' => $zipCode->lastItem(),
      'firstItem' => $zipCode->firstItem(),
      'per_page' => $zipCode->perPage(),
      'prev_page_url' => $zipCode->previousPageUrl(),
      'data' => $array,
      'activezipcode' => $arrayactive
    ];
    
   // return response()->json($array, 200);
    return view('admin.reports.zipcodreport', compact('resultsData'));

        // return $resultsData;
   }

   public function graph(){

    // $allSession = SessionTable::with('user')->selectRaw("users_id , count(id) activesession")->where("status",'1')->groupBy('users_id')->get();

    $allSession=SessionTable::with(['hotel','pdname'=> function($q) {
      // Query the name field in status table
      $q->select('id','personal_devicename','device_id','user_id');
  }])->where([
      ['status', '=', '1'],
  ])->orderBy('connected_devices', 'asc')->get();

  $device1 = [];
  $device2 = [];
  $device3 = [];
  $device4 = [];
  $device5 = [];
  $device6 = [];
  $device7 = [];
  $device8 = [];
  $device9 = [];
  $device10 = [];
  foreach ($allSession as $data){

     if($data->deviceSign == 'normaltv'){
         array_push($device1, $data->deviceSign);
     }elseif($data->deviceSign == 'roku'){
      array_push($device2, $data->deviceSign);
     }elseif($data->deviceSign == 'appletv'){
      array_push($device3, $data->deviceSign);
    }elseif($data->deviceSign == 'firetv'){
      array_push($device4, $data->deviceSign);
    }elseif($data->deviceSign == 'smarttv'){
      array_push($device5, $data->deviceSign);
    }elseif($data->deviceSign == 'mobile'){
      array_push($device6, $data->deviceSign);
    }elseif($data->deviceSign == 'tablet'){
      array_push($device7, $data->deviceSign);
    }elseif($data->deviceSign == 'ipad'){
      array_push($device8, $data->deviceSign);
    }elseif($data->deviceSign == 'iphone'){
      array_push($device9, $data->deviceSign);
    }
    else{
      array_push($device10, $data->deviceSign);
    }

  }

    $resultDevicetype = [
      'desktop' => count($device1),
      'roku_tv' => count($device2),
      'apple_tv' => count($device3),
      'fire_tv' => count($device4),
      'samsung_tv' => count($device5),
      'mobile' => count($device6),
      'tablet' => count($device7),
      'ipad' => count($device8),
      'iphone' => count($device9),
      'others' => count($device10)
    ];

    //return $resultDevicetype;

    return view('admin.reports.demographicalreport', compact('resultDevicetype'));

   }

   public function zipcodeUsers($zipcode){

     $UsersINZipcode=DB::table('users')
     ->where('zipcode', $zipcode)
     ->select('id','name','email')
     ->get();

     //return view('admin.reports.zipcodreport', compact('UsersINZipcode'));
     return $UsersINZipcode;

   }  
}
