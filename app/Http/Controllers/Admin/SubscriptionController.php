<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPack;
use App\User;
use Carbon\Carbon;


/**
 * Description of SubscriptionController
 *
 * 
 */

class SubscriptionController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Subscription::with('user')->paginate(20);//Customers::with('country')->paginate(20);
        return view('admin.subscriptions.index', compact('users'));
        
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   //$subscription = Subscription::find($id); 
       // $users = User::all()->pluck('name', 'id');
        $subscription = Subscription::find($id);
       // dd($subscription);
       // $pack = SubscriptionPack::all()->pluck('name', 'id');

        return view('admin.subscriptions.show', compact('subscription','users','pack'));


    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    
         $users = User::all()->pluck('name', 'id');
         $pack = SubscriptionPack::all()->pluck('name', 'id');

         return view('admin.subscriptions.create', compact('users','pack'));
        
        
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   $this->validate($request, [
        'subscriptionpack_id' => 'required',
        'start_date' => 'required',
        'users_id' => 'required',
    ]);

    // $list=  (new Subscription())
    // ->where([
    //     ['status', '=', '1'],
    //     ['users_id', '=', $request->input('users_id')],
    //      ])
    // ->whereDate('start_date','<=', $request->input('start_date'))
    // ->whereDate('expiry_date','>=', $request->input('start_date'))
    // ->get()->toArray();
  $userid=$request->input('users_id');//user id
//   echo $userid;
  $startdate=Carbon::parse($request->input('start_date'))->startOfDay();;//start date
  $expiry_date=Carbon::parse($request->input('expiry_date'))->endOfDay();;//end date
//   echo $startdate;
//   echo "   0000000000   "   ;
//   echo $expiry_date;

    $subscription_exists = Subscription::where([
        ['status', '=', '1'],
        ['users_id', '=', $userid],
         ])
    ->whereBetween('start_date',[$startdate, $expiry_date])
    ->orWhereBetween('expiry_date',[$startdate, $expiry_date])->get()->toArray();
   // print_r($subscription_exists); exit;   
    
      if ((count($subscription_exists)==0)){
         //  dd($list[0]); 
        $model=new Subscription();
        
        $packid=$request->input('subscriptionpack_id');

        // if($request->has('start_date') && !empty($request->input('start_date'))) {
        //     $startdate=Carbon::createFromFormat('Y-m-d H:i:s', $request->start_date)->format('Y/m/d');
        // } else {
        //     $startdate=Carbon::now();
        // } 

         // a helper function addInterval(Carbon $date)
         
        $model->subscriptionpack_id = $packid;
        $model->users_id = $request->input('users_id');
        $model->start_date = $request->input('start_date');
        $model->expiry_date = $request->input('expiry_date');
        
       // $model->expiry_date = Carbon::now()->addMonths(1);;
    //    if($packid==1){
         
    //         $model->expiry_date = $startdate->addMonth(1);
    //    }else if($packid==2){
    //         $model->expiry_date = $startdate->addMonths(6);
    //    }else{
    //     $model->expiry_date = Carbon::now();
    //    }
            
      //echo $model->expiry_date;
        
     
        $model->created_on = Carbon::now();
        $model->modified_on = Carbon::now();
        $model->status = 1;
        $model->save();
        return redirect('admin/subscriptions')->with('info','Subscriber Added Successfully');
  
         
    } else{
        $user = User::pluck('name', 'id'); 
        $u=$user[$userid];
        $start=Carbon::parse($request->input('start_date'))->format('d M Y');
        $end=Carbon::parse($request->input('expiry_date'))->format('d M Y');

        return  back()->withErrors(["dateeror"=>"overlaping date found! ".$start." between ".$end,
        "users"=>"For ".$u]);
        
    }
    
        //return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  
        $subscription = Subscription::find($id);
        //$packs = SubscriptionPack::get(); //get all packs
        $packs = SubscriptionPack::pluck('name', 'id');
        $subscription=$subscription->load('subscriptionpack');
        $user = User::pluck('name', 'id'); 
        
        
        
        //dd($packs);
        

        return view('admin.subscriptions.edit', compact('subscription','user','packs','SubscriptionPackArr','UserArr'));
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'subscriptionpack_id'=>'required',
            'start_date'=>'required',
            'users_id'=>'required'
        ]);
        $input = $request->only('subscriptionpack_id', 'start_date', 'users_id');
        if ($request->has('toggleswitch')) {
            // $archived="helllo block user on";
             $input['status'] = 0;
            // $user->is_active=0;
         } else{
            // $archived="bye block off";
             //$user->is_active=1;
             $input['status'] = 1;
         }
    //            $allpost= $request->post();
    //    dd($input);  //get all the post data
        
        $model = Subscription::find($id);
        
       // dd($input);exit;
        if(!$model){
            return redirect('admin/subscriptions')->with('info','subscription with id ' . $id . ' cannot be found.'); 
        }
        else{$model->update($input); //update the subscription info

            return redirect('admin/subscriptions')->with('info','Subscription updated Successfully');
       }
        }
    
    
    
    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $subscription= Subscription::whereId($id)->delete(); 
         if($subscription){
            return redirect('admin/subscriptions')->with('info','Subscription Deleted Successfully');
                      
        
        }else{
            return redirect('admin/subscriptions')->with('info','Subscription Not found');
            
        }

        
    }

    public function changeStatus(Request $request)
    {
        $user = Subscription::find($request->user_id);
        $user->status = $request->status;
        $user->save();
  
        return response()->json(['success'=>'Status change successfully.']);
    }
}
