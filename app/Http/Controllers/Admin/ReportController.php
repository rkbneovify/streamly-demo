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
       
      //  $allSession=SessionTable::with('user')->selectRaw("users_id , count(id) activesession")->where("status",'1')->groupBy('users_id')->get();
       //return view('admin.reports.channelanalysis', compact('allSession'));

         return 'Under Maintenance';
   }

   public function TransactionReports(){

    $transaction = DB::table('subscriptions')
    ->select('id','user_id','stripe_status', 'renews_at', 'ends_at', 'created_at')
    ->get();

    $array =[];
    $arrayRes1 = [];
    $arrayRes2 = [];
    $arrayRes3 = [];
    $arrayRes4 = [];
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
        
      $arry = [
        'id'=> $data->id ,
        'email'=> $userEmail ,
        'zipcode' => $userZipcode ,
        'stripe_status'=> $data->stripe_status ,
        'start_at'=> $data->created_at ,
        'ends_at'=> $data->ends_at ,
      ];
      array_push($array, $arry);
    };

    $resultCount = [
      'newuser' => $arrayRes1,
      'reneweduser' => $arrayRes2,
      'terminateduser' => $arrayRes3,
      'overdueuser' => $arrayRes4,
      'data' => $array
    ];

    //$transaction->toArray();
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

   
}
