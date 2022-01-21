<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPack;
use App\Models\Zipcode;
use App\User;
use Carbon\Carbon;
use Exception;
use Stripe\Coupon;

use Laravel\Cashier\Exceptions\IncompletePayment;


/**
 * Description of SubscriptionController
 *
 * 
 */

class SubscriptionController extends Controller
{    
    // https://hotexamples.com/examples/stripe/Plan/-/php-plan-class-examples.html
    // https://hotexamples.com/examples/stripe/Customer/all/php-customer-all-method-examples.html
    public function __construct()
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
        $subscriptions = Subscription::with('user')->get();
         $availablePlanidinDB=$subscriptions->pluck('stripe_plan')->unique();
        $plans = \Stripe\Plan::all();
        // dd($plans);
         $plans->data;//Customers::with('country')->paginate(20);

      $d=collect($plans->data)->whereIn('id',$availablePlanidinDB)
      
      ->groupBy('billing_scheme');
    //   ->where('billing_scheme', 'per_unit')
      
      $plansdetails=$d->all();
      $plankey=$d->keys();
    //   dump($d);
    //   dump($availablePlanidinDB->values());
    //   dd($plankey);
        // return view('admin.subscriptions.index', compact('subscriptions','plans'));
        return view('admin.subscriptions.tierindex', compact('subscriptions','plans','plansdetails','plankey'));
        
    }   
    public function showAllcustomer()
    {    $arrayStripeEmail= array();
      $arrayStripeEmailCustArray= array();
        // $users = User::all()->pluck('name', 'id');
        // $subscriptions = Subscription::paginate(20);
        //  $customers = \Stripe\Customer::all(['expand' => ['data.subscriptions']]);
        // $plans = \Stripe\Plan::all(['active'=>true]);
        $subscriptions = \Stripe\Subscription::all(['status'=>'active','expand' => ['data.plan','data.customer','data.items']]);
        foreach ($subscriptions->data as $key => $subscription) {
            array_push($arrayStripeEmail, $subscription->customer->email);
            $arrayStripeEmailCust = new \stdClass; 
            //customerdetails
            $arrayStripeEmailCust->email = $subscription->customer->email;
            $arrayStripeEmailCust->customerid = $subscription->customer->id;
            $arrayStripeEmailCust->customercreated = date("m-d-y", $subscription->customer->created);
            //activesubscriptiondetails
            $arrayStripeEmailCust->subscriptionid = $subscription->id;
            $arrayStripeEmailCust->subscriptionstatus = $subscription->status;
            $arrayStripeEmailCust->subscriptionquantity = $subscription->quantity;
            $arrayStripeEmailCust->subscriptionplanid = $subscription->plan->id;
            //subscription item
            $arrayStripeEmailCust->subscriptionitem = $subscription->items->data[0]->id;
            $arrayStripeEmailCust->subscriptioncreated = date("m-d-y", $subscription->created);
            array_push($arrayStripeEmailCustArray, $arrayStripeEmailCust);
            
            echo ($key+1).").".$subscription->id . " created " . date("m-d-y", $subscription->created) ." stripe_status " .  $subscription->status . " quantity " .  $subscription->quantity ." plan " .  $subscription->plan->id." Email " .  $subscription->customer->email."items".$subscription->items->data[0]->id."<br>";
            dump($subscription->items->data[0]->id);
        }
        // echo "subscriptions".count($subscriptions->data);
        while ($subscriptions->has_more) {
            $startsubscriptioncount=count($subscriptions->data);
            // Add the `starting_after` parameter to reflect the last customer ID
            $subscriptions = \Stripe\Subscription::all(array("limit" => 100, "starting_after" => $subscription->id,'expand' => ['data.plan','data.customer','data.items']));
            foreach ($subscriptions->data  as $key => $subscription) {
                //push emails in seperate array
                array_push($arrayStripeEmail, $subscription->customer->email);
                $arrayStripeEmailCust = new \stdClass; 
                //customerdetails
                $arrayStripeEmailCust->email = $subscription->customer->email;
                $arrayStripeEmailCust->customerid = $subscription->customer->id;
                $arrayStripeEmailCust->customercreated = date("m-d-y", $subscription->customer->created);
                //activesubscriptiondetails
                $arrayStripeEmailCust->subscriptionid = $subscription->id;
                $arrayStripeEmailCust->subscriptionstatus = $subscription->status;
                $arrayStripeEmailCust->subscriptionquantity = $subscription->quantity;
                $arrayStripeEmailCust->subscriptionplanid = $subscription->plan->id;
                //subscription item
                $arrayStripeEmailCust->subscriptionitem = $subscription->items->data[0]->id;
                $arrayStripeEmailCust->subscriptioncreated = date("m-d-y", $subscription->created);
                array_push($arrayStripeEmailCustArray, $arrayStripeEmailCust);
                echo ($key+$startsubscriptioncount+1).").".$subscription->id . " created " . date("m-d-y", $subscription->created) ." stripe_status " .  $subscription->status . " quantity " .  $subscription->quantity ." plan " .  $subscription->plan->id." Email " .  $subscription->customer->email."items".$subscription->items->data[0]->id."<br>";
                dump($subscription->items->data[0]->id);
            }}
            echo "-----------end----------";
        // $item = new \Stripe\SubscriptionItem();
        dump($subscriptions->data);
// dump($item);
$DBuserEmails = User::select('email')->where([
    ['is_active', '=', '1'],
    ['email_verified_at', '<>',NULL ],
    ['stripe_id', '<>',NULL ],
     ])->pluck('email')->toArray();

     $differenceArray = array_diff($arrayStripeEmail, $DBuserEmails);
     dump($differenceArray);
foreach ($arrayStripeEmailCustArray as $key => $object) {
    
    // echo $object->email."<br>";
    if (in_array($object->email, $differenceArray)) {
        // unset($arrayStripeEmailCustArray[$key];
        echo "key".$key;
        echo "<br>Match found".$object->email."<br>";
    } else{
        unset($arrayStripeEmailCustArray[$key]);
    }
}
echo "after diff";
dump($arrayStripeEmailCustArray);
dd($arrayStripeEmailCustArray);
         $customers = \Stripe\Customer::all(['limit' => 100]);
        //  foreach ($customers->autoPagingIterator() as $customer) {
        //     dump($customer->subscriptions->data);
        //   }
        //  $subscriptions = $customers->subscriptions->all();
        //  $customers = \Stripe\Plan::all();
        //  dump($subscriptions);
         echo "<br>";
         dump($customers->data);
         foreach ($customers->data as $key2 => $customer) {
            echo ($key2+1).").".$customer->id . " created " . date("m-d-y", $customer->created) . " email " .  $customer->email. "<br>";
        }
        // While we have more results, iterate through them
        while ($customers->has_more) {
            $startcuscount=count($customers->data);
            // Add the `starting_after` parameter to reflect the last customer ID
            $customers = \Stripe\Customer::all(array("limit" => 100, "starting_after" => $customer->id));
            foreach ($customers->data as $key2 => $customer) {
                ($key2+$startcuscount+1).").".$customer->id . " created " . date("m-d-y", $customer->created) . " email " .  $customer->email. "<br>";
            }}
            
        //  $plans->data;//Customers::with('country')->paginate(20);
        // return view('admin.subscriptions.index', compact('subscriptions','plans','users'));
        
    }   

    public function syncStripeUser()
    {    $arrayStripeEmail = array();
      
      $arrayStripeEmailCustArray = array();
         $customers = \Stripe\Customer::all(['limit' => 100,'expand' => ['data.subscriptions']]);
        //  foreach ($customers->autoPagingIterator() as $customer) {
        //     dump($customer->subscriptions->data);
        //   }
        //  $subscriptions = $customers->subscriptions->all();
        //  $customers = \Stripe\Plan::all();
        //  dump($subscriptions);
         echo "<br>";
         dump($customers->data);
         foreach ($customers->data as $key2 => $customer) {
            echo ($key2+1).").".$customer->id . " created " . date("m-d-y", $customer->created) . " email " .  $customer->email;
            // echo "sub".$customer->subscriptions->data. "<br>";
            dump($customer->subscriptions->data);
            array_push($arrayStripeEmail, $customer->email);
            $arrayStripeEmailCust = new \stdClass; 
            $arrayStripeEmailCust->email = $customer->email;
            $arrayStripeEmailCust->customerid = $customer->id;
            $arrayStripeEmailCust->created = date("m-d-y", $customer->created);
            array_push($arrayStripeEmailCustArray, $arrayStripeEmailCust);
        }
        // While we have more results, iterate through them
        while ($customers->has_more) {
            $startcuscount=count($customers->data);
            // Add the `starting_after` parameter to reflect the last customer ID
            $customers = \Stripe\Customer::all(array("limit" => 100, "starting_after" => $customer->id,'expand' => ['data.subscriptions']));
            foreach ($customers->data as $key2 => $customer) {
                ($key2+$startcuscount+1).").".$customer->id . " created " . date("m-d-y", $customer->created) . " email " .  $customer->email;
                // echo "sub".$customer->subscriptions->data['id']. "<br>";
                dump($customer->subscriptions->data);
                array_push($arrayStripeEmail, $customer->email);
                $arrayStripeEmailCust = new \stdClass; 
                $arrayStripeEmailCust->email = $customer->email;
            $arrayStripeEmailCust->customerid = $customer->id;
            $arrayStripeEmailCust->created = date("m-d-y", $customer->created);
            array_push($arrayStripeEmailCustArray, $arrayStripeEmailCust);
            }}
            // whereIn('email', $emailAddresses)
            $DBuserEmails = User::select('email')->where([
                ['is_active', '=', '1'],
                ['email_verified_at', '<>',NULL ],
                ['stripe_id', '<>',NULL ],
                 ])->pluck('email')->toArray();
                 echo "<br>DB_Emails<br>";
                 dump($DBuserEmails);
                 echo "<br>Stripe_emails<br>";
                 
            dump($arrayStripeEmail);
            $differenceArray = array_diff($arrayStripeEmail, $DBuserEmails);
            dump($differenceArray);
            // echo "<br> Email and customer:<br>";
            // dump($arrayStripeEmailCust);
            dump($arrayStripeEmailCustArray);
        
foreach ($arrayStripeEmailCustArray as $key => $object) {
    
    // echo $object->email."<br>";
    if (in_array($object->email, $differenceArray)) {
        // unset($arrayStripeEmailCustArray[$key];
        echo "key".$key;
        echo "<br>Match found".$object->email."<br>";
    } else{
        unset($arrayStripeEmailCustArray[$key]);
    }
}
echo "after diff";
dump($arrayStripeEmailCustArray);
            // coustmer add
            // insert the emails as users(created at with a custmer id & updated at at now) and make them roles of user ''from next time when making coustemer set there userlrole in metadata'
            //we'll get the user id
            //now insert it in subscription, or with customer id check if they have subscription
            //check for subscription items
            if (!empty($differenceArray)){
             $this->insertInDB($differenceArray);
            }
  
            
        //  $plans->data;//Customers::with('country')->paginate(20);
        // return view('admin.subscriptions.index', compact('subscriptions','plans','users'));
        
    }

              
 private function insertInDB($arrEmails){
    echo "helloLets inertinDB";
return print_r($arrEmails);
/**
try {
DB::transaction(function () use($arrEmails, $workflow, $menu_ids) {
    foreach ($role_ids as $key => $role_id) {
        $workflow->menus()->attach($menu_ids[$key], ['role_id' => $role_id]);
        $menu = Menu::find($menu_ids[$key]);
        foreach ($menu->permissions as $permission) {
            $workflow->permissions()->attach($permission->id, ['role_id' => $role_id]);
        }
    }
    flash()->success('Pemetaan proses aliran kerja berhasil disimpan.');
});} catch (\Exception $ex) {
    $response->setMessages(['Exception: ' . $ex->getMessage()]);
} */
}
   
    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function paginatewithscroll(){
        return view('admin.subscriptions.page');
    }
    public function subscriptionstripe(Request $request)
    { //dd($request);
        
        $starting_after=$request->query('lastid')==1?NULL:$request->query('lastid');
        $customer=$request->query('customer')??NULL;
        // dd($starting_after); 
        $increment = 30;
        $customersJSON = \Stripe\Subscription::all(array(
            "customer"=>$customer,
            //"customer"=>"cus_JgeZ3afo13HWyx",
        "limit" => $increment, "starting_after" => $starting_after,'expand' => ['data.plan','data.customer','data.items']));
        return ['fulldata'=>$customersJSON->data,'has_more'=>$customersJSON->has_more, 'lastid' => end($customersJSON->data)];
    }
    public function show($id)
    {   //$subscription = Subscription::find($id); 
       $user = User::all()->pluck('name', 'id');
        $subscription = Subscription::find($id);
        $plans = \Stripe\Plan::all();
         $plans->data;
       // dd($subscription);
       // $pack = SubscriptionPack::all()->pluck('name', 'id');

        return view('admin.subscriptions.show', compact('subscription','user','plans'));


    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    
         $users = User::where('is_active','=','1')->get();
         $plans = \Stripe\Plan::all(['active'=>true]);
         $plans->data;
         $pack = SubscriptionPack::where('is_active','=','1')->get();
         return view('admin.subscriptions.create', compact('users','pack','plans'));
        
        
    }  
    public function store(Request $request) {
        // dd($request);exit;
        $user = User::find($request['users_id']);
         //echo trim($user);exit;
        //echo $user;exit;
        $productfullString=$request['product'];
        // $product=$request['product'];
       $explodedstring= explode("#~",$productfullString);
       $product=$explodedstring[0];
       $trial_days=$explodedstring[1];
        $coupon=$request['coupon'];
        // $trial_days=$request['trial_days'];
        
         
        try {
               // check already subscribed and if already subscribed with picked plan
        if( $user->subscribed('main') && ! $user->subscribedToPlan($product, 'main') ) {

            // swap if different plan attempt
            $user->subscription('main')->swap($product);
            return redirect('admin/subscriptions')->with('info','Subscriber existed ,so plan is swapped ');
        }else {
            
            // if user has a coupon, create new subscription with coupon applied
            if( $coupon = $request->get('coupon') ) {
                if(!empty($trial_days)){
             $user->newSubscription( 'main', $product)->trialDays($trial_days)
                 ->withCoupon($coupon)
                 ->create($request->payment_method, ['email' => $user->email]);}else{
                    $user->newSubscription( 'main', $product)
                        ->withCoupon($coupon)
                        ->create($request->payment_method, ['email' => $user->email]);}
                 return redirect('admin/subscriptions')->with('info','Subscriber Added Successfully with coupon');
 
         } else {
            if(!empty($trial_days)){
             // Create subscription
             $newSubscription = $user->newSubscription('main', $product)
             
             ->trialDays($trial_days)->create($request->payment_method, ['email' => $user->email]);
             return redirect('admin/subscriptions')->with('info','Subscriber Added Successfully');}
               $newSubscription = $user->newSubscription('main', $product)
            // ->withCoupon($coupon)
            ->create($request['payment_method'], ['email' => $user->email]);
            return redirect('admin/subscriptions')->with('info','Subscriber Added Successfully');
         }}
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
        $user->subscription('main')->skipTrial()->swap($product);
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
        $user->subscription('main')->skipTrial()->cancelNow();
        return redirect('admin/subscriptions')->with('info','Subscription Cancel Successfully');
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

    public function ajaxCheck(Request $request)
    { //dd($request);
        
        if($request->has('coupon')){
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            try {
               $status= Coupon::retrieve($request->input('coupon'));
               if($status->amount_off) {
                $price =  $status->currency . ' ' . $status->amount_off/100 . ' Off ' . $status->duration;
            } else {
                if( $status->duration == 'repeating' ) {
                    $duration = '(' . $status->duration_in_months . ' months)';
                } else {
                    $duration = '(forever)';
                }
                $price = $status->percent_off . '% for ' . $duration;
            }
            } catch (\Stripe\Error\InvalidRequest $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return response()->json(['message' => 'Coupon is valid! & '.$price,'amountoff'=>$status->amount_off/100,'percentoff'=>$status->percent_off], 200);

        }
        if($request->has('zip')){
            $data = Zipcode::select('zipcode','city','state')->where([
                ['status', '=', '1'],
                ['zipcode', '=',$request['zip'] ]
            ])->get();
            
            if(count($data)==0){
                $data2 = Zipcode::select('zipcode','city','state')->where([
                    
                    ['zipcode', '=',$request['zip'] ]
                ])->get();
                return response()->json(['message' => 'Zipcode not allowed or invalid','city'=>$data2->pluck('city')->first()??'Invalid City','state'=>$data2->pluck('state')->first()??'Invalid State'], 404);
            }
            return response()->json(['message' => 'Congratulations!  Your zipcode is in our service area.<br> Proceed to the next step to subscribe','city'=>$data->pluck('city')->first(),'state'=>$data->pluck('state')->first()], 200); 

        }
    }

    
}
