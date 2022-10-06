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
    ->select('id','user_id','stripe_status','ends_at', 'created_at')
    ->get();

    $array =[];
    foreach ($transaction as $data){

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

    //$transaction->toArray();
    return view('admin.reports.transactionreport', compact('array'));
         
    //return $array;
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
    
     $zipCode=DB::table('zipcodes')->where('status', '1')->get();

     $array =[];
    foreach ($zipCode as $data){

       // $userEmail=DB::table('users')->where('id', $data->user_id)->value('email');
        $userZipcode=DB::table('users')->where('zipcode', $data->zipcode)->get();

        $count = count($userZipcode);
        
      $arry = [
        'zipcode' => $data->zipcode,
        'userCount'=> $count
      ];
      array_push($array, $arry);
    };
    
   // return response()->json($array, 200);
    return view('admin.reports.zipcodreport', compact('array'));

      // return '1';
   }

   
}
