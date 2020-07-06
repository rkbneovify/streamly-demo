<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPack;
use App\User;
use Carbon\Carbon;
use Exception;

use Laravel\Cashier\Exceptions\IncompletePayment;


/**
 * Description of SubscriptionController
 *
 * 
 */

class SubscriptionController extends Controller
{    public function __construct()
    {
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $users = Subscription::with('user')->paginate(20);
        $plans = \Stripe\Plan::all();
         $plans->data;//Customers::with('country')->paginate(20);
        return view('admin.subscriptions.index', compact('users','plans'));
        
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   //$subscription = Subscription::find($id); 
       $users = User::all()->pluck('name', 'id');
        $subscription = Subscription::find($id);
        $plans = \Stripe\Plan::all();
         $plans->data;
       // dd($subscription);
       // $pack = SubscriptionPack::all()->pluck('name', 'id');

        return view('admin.subscriptions.show', compact('subscription','users','plans'));


    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    
         $users = User::where('is_active','=','1')->get();
         $plans = \Stripe\Plan::all();
         $plans->data;
         $pack = SubscriptionPack::where('is_active','=','1')->get();
         return view('admin.subscriptions.create', compact('users','pack','plans'));
        
        
    }  
    public function store(Request $request) {
        //dd($request);exit;
        $user = User::find($request['users_id']);
         //echo trim($user);exit;
        //echo $user;exit;
        $product=$request['product'];
        try {
            $newSubscription = $user->newSubscription('main', $product)->create($request->payment_method, ['email' => $user->email]);
            return redirect('admin/subscriptions')->with('info','Subscriber Added Successfully');
        } catch ( IncompletePayment $exception ){
            return redirect()->back()->with('info',json_encode($exception->getMessage(), true));
        }catch ( Exception $exception ){
            

            return redirect()->back()->with('info',json_encode($exception->getMessage(), true));
          //  return redirect()->back()->with(['info' => $exception->getMessage()]);
        }

    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
//     public function store(Request $request)
//     {   $this->validate($request, [
//         'subscriptionpack_id' => 'required',
//         'start_date' => 'required',
//         'users_id' => 'required',
//     ]);
//     $userid=$request->input('users_id');
//     $start = Carbon::parse($request['start_date'])->format('Y-m-d 00:00:00');
//  $end = Carbon::parse($request['expiry_date'])->format('Y-m-d 23:59:59');
//  $existsActive = Subscription::where(function ($query) use ($start,$userid) {
//     $query->where('users_id', '=', $userid);
//                                       $query->where('start_date', '<=', $start);
//                                       $query->where('expiry_date', '>=', $start);
//                                   })->orWhere(function ($query) use ($end) {
//                                       $query->where('start_date', '<=', $end);
//                                       $query->where('expiry_date', '>=', $end);
//                                   })->count();
//  if($existsActive > 0 ){
//     // "Already exists"; 
//     $user = User::pluck('name', 'id'); 
//     $u=$user[$userid];
//     $start=Carbon::parse($request->input('start_date'))->format('d M Y');
//     $end=Carbon::parse($request->input('expiry_date'))->format('d M Y');

//     return  back()->withErrors(["dateeror"=>"overlaping date found! ".$start." between ".$end,
//     "users"=>"For ".$u]);
//  }else{
//     //no overlapping date found
//     $model=new Subscription();
        
//     $packid=$request->input('subscriptionpack_id');


//      // a helper function addInterval(Carbon $date)
     
//     $model->subscriptionpack_id = $packid;
//     $model->users_id = $request->input('users_id');
//     $model->start_date = $request->input('start_date');
//     $model->expiry_date = $request->input('expiry_date');
    
//    // $model->expiry_date = Carbon::now()->addMonths(1);;
// //    if($packid==1){
     
// //         $model->expiry_date = $startdate->addMonth(1);
// //    }else if($packid==2){
// //         $model->expiry_date = $startdate->addMonths(6);
// //    }else{
// //     $model->expiry_date = Carbon::now();
// //    }
        
//   //echo $model->expiry_date;
    
 
//     $model->created_on = Carbon::now();
//     $model->modified_on = Carbon::now();
//     $model->status = 1;
//     $model->save();
//     return redirect('admin/subscriptions')->with('info','Subscriber Added Successfully');

    
// }
    
//         //return redirect()->back();
//     }
     /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  
        $subscription = Subscription::find($id);
        $userid=$subscription->user_id;
         
        $user = User::pluck('name', 'id'); 
        $plans = \Stripe\Plan::all();
         $plans->data;
        

        return view('admin.subscriptions.edit', compact('subscription','user','plans'));
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
       // dd($request);
        $product=$request['stripe_plan'];
        //dd($product);
        $planname=$request['name'];
        $userid=$request['user_id'];
        $user = User::find($userid);
        try{
        $user->subscription('main')->swap($product);
        return redirect('admin/subscriptions')->with('info','Subscription updated Successfully');
        } catch ( Exception $e ){
            return redirect('admin/subscriptions')->with('info','Subscription Error'.json_encode($e->getMessage(), true));
        }
        // $user->subscription('default')->swap('provider-plan-id');
    }
    
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'subscriptionpack_id'=>'required',
    //         'start_date'=>'required',
    //         'users_id'=>'required'
    //     ]);
    //     $userid=$request->input('users_id');
    //     $start = Carbon::parse($request['start_date'])->format('Y-m-d 00:00:00');
    //  $end = Carbon::parse($request['expiry_date'])->format('Y-m-d 23:59:59');
    //  $existsActive = Subscription::where(function ($query) use ($start,$userid) {
    //     $query->where('users_id', '=', $userid);
    //                                       $query->where('start_date', '<=', $start);
    //                                       $query->where('expiry_date', '>=', $start);
    //                                   })->orWhere(function ($query) use ($end,$userid) {
    //                                     $query->where('users_id', '=', $userid);
    //                                       $query->where('start_date', '<=', $end);
    //                                       $query->where('expiry_date', '>=', $end);
    //                                   })->count();
    //  if($existsActive > 0 ){
    //     // "Already exists"; 
    //     $user = User::pluck('name', 'id'); 
    //     $u=$user[$userid];
    //     $start=Carbon::parse($request->input('start_date'))->format('d M Y');
    //     $end=Carbon::parse($request->input('expiry_date'))->format('d M Y');
    
    //     return  back()->withErrors(["dateeror"=>"overlaping date found! ".$start." between ".$end,
    //     "users"=>"For ".$u]);
    //  } else {
    //     $input = $request->only('subscriptionpack_id', 'start_date', 'users_id');
    //     if ($request->has('toggleswitch')) {
    //         // $archived="helllo block user on";
    //          $input['status'] = 0;
            
    //      } else{
    //         // $archived="bye block off";
             
    //          $input['status'] = 1;
    //      }
    // //            $allpost= $request->post();
    // //    dd($input);  //get all the post data
        
    //     $model = Subscription::find($id);
        
       
    //     if(!$model){
    //         return redirect('admin/subscriptions')->with('info','subscription with id ' . $id . ' cannot be found.'); 
    //     }
    //     else{$model->update($input); //update the subscription info

    //         return redirect('admin/subscriptions')->with('info','Subscription updated Successfully');
    //    }
    //     }
    // }
    
    
    
    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {  $user = User::find($id);
    
        try{
        $user->subscription('main')->cancelNow();
        return redirect('admin/subscriptions')->with('info','Subscription Cancel Successfully'.$user);
    }catch( Exception $e ){
        return redirect('admin/subscriptions')->with('info','Error'.json_encode($e->getMessage(), true)); }
        

        
    }

    public function changeStatus(Request $request)
    {
        $user = Subscription::find($request->user_id);
        $user->status = $request->status;
        $user->save();
  
        return response()->json(['success'=>'Status change successfully.']);
    }
}
