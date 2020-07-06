<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPack;
use Illuminate\Support\Str;


/**
 * Description of SubscriptionPackController
 *
 * 
 */

class SubscriptionPackController extends Controller
{    
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
        // $subscriptionpack = SubscriptionPack::all();
        $plan = \Stripe\Plan::all();
        $product = \Stripe\Product::all();
       // return view('pages.subscription_pack.index', ['records' => SubscriptionPack::paginate(10)]);
       return view('admin.subscriptionpack.index', compact('plan','product'));
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  //$subscriptionpack = SubscriptionPack::find($id); 
        $plan = \Stripe\Plan::retrieve($id);
        
        return view('admin.subscriptionpack.show', compact('plan'));
        

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  $plan_id = \Stripe\Plan::all();
        $Product = \Stripe\Product::all();
 $Product->data;
        return view('admin.subscriptionpack.create', compact('plan_id','Product'));
    }  
    public function viewPlan(Request $request){
      //  \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
$plans = \Stripe\Plan::all(['active'=>false]);
// $plans = \Stripe\Product::retrieve('prod_HQU40biTPvAPfe');
// $plans = \Stripe\Price::retrieve('price_1Gr90hLqTwOy7LECCw9EzmLs');
// $plans = \Stripe\Product::all();
// $plans = \Stripe\Subscription::all(['expand' => ['data.customer']]);
// $plans = \Stripe\Subscription::all();
// $plans = \Stripe\Plan::all(['expand' => ['data.product']]);

return $plans->data;
    }
    public function createPlan(Request $request)
    {
        /*
         * Stripe Api Key Set
         * This ApiKey get from environment variable
         * Using Stripe lib for creating plan
         * */
        /*
         * Processing input data for stripe
         * */
        
        $amount = number_format($request->plan_amount, 2);
        $amount = (int) ($amount * 100);
        $interval = strtolower($request->plan_interval);
        $interval_count = 1;
        if ($interval == 'custom') {
            $interval_count = (int) $request->interval_count;
            $interval = $request->coustom_interval;
        } else {
            if ($interval == '3-month' || $interval == '6-month') {
                $array = explode('-', $interval);
                $interval_count = (int) $array[0];
                $interval = $array[1];
            }
        }
        $plan_id=Str::random(12);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
        $plan = \Stripe\Plan::create(array("id" => $plan_id, 
        "name" => $request->plan_name, 
        "currency" => "USD", 
        "amount" => $amount, 
        "interval" => $interval, 
        "interval_count" => $interval_count));
    } catch (Exception $e) {
        print_r($e);
    }
        /*
         * Stripe Plan insert in Local Database
         * Using Local Database for faster loading
        //  * */
        // $stripePlan = StripePlan::create(["plan_id" => $request->plan_id, "name" => $request->plan_name, "amount" => $request->plan_amount, "currency" => $request->plan_currency, "interval" => $interval, "interval_count" => $interval_count, "auto_renewal" => (int) $request->auto_renewal ? $request->auto_renewal : 0, "trial_period_days" => (int) $request->plan_trial, "statement_descriptor" => $request->plan_des, "coupon_id" => $request->discount == 1 ? $request->stripe_coupon : null, "created" => date('Y-m-d H:i:s')]);
        // $stripePlan->planFeature()->attach($request->feature_id);
        // if ($stripePlan) {
        //     flash('Your Plan (' . $stripePlan->name . ') is successfully created.');
        // } else {
        //     flash('Your Plan (' . $stripePlan->name . ') is failed to create. Please contact your administrator for assistance.');
        // }
        // return redirect()->back();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $amount = number_format($request->plan_amount, 2);
        $amount = (int) ($amount * 100);
        $interval = strtolower($request->plan_interval);
        $interval_count = 1;
        if ($interval == 'custom') {
            $interval_count = (int) $request->interval_count;
            $interval = $request->coustom_interval;
        } else {
            if ($interval == '3-month' || $interval == '6-month') {
                $array = explode('-', $interval);
                $interval_count = (int) $array[0];
                $interval = $array[1];
            }
        }
        $plan_id=Str::random(12);
        $product_id=$request->product;
        
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
        $plan = \Stripe\Plan::create(array("id" => $plan_id, 
        "nickname" => $request->plan_name, 
        "currency" => "USD", 
        "amount" => $amount, 
        "interval" => $interval, 
        "interval_count" => $interval_count,
        "product"=>$product_id));
        
        return redirect('admin/subscriptionpack')->with('info','SubscriptionPack Added Successfully');
    } catch (Exception $e) {
        
        return redirect('admin/subscriptionpack')->with('info',json_encode($e->getMessage(), true));
    }
        /*
         * Stripe Plan insert in Local Database
         * Using Local Database for faster loading
        //  * */
        // $stripePlan = StripePlan::create(["plan_id" => $request->plan_id, "name" => $request->plan_name, "amount" => $request->plan_amount, "currency" => $request->plan_currency, "interval" => $interval, "interval_count" => $interval_count, "auto_renewal" => (int) $request->auto_renewal ? $request->auto_renewal : 0, "trial_period_days" => (int) $request->plan_trial, "statement_descriptor" => $request->plan_des, "coupon_id" => $request->discount == 1 ? $request->stripe_coupon : null, "created" => date('Y-m-d H:i:s')]);
        // $stripePlan->planFeature()->attach($request->feature_id);
        // if ($stripePlan) {
        //     flash('Your Plan (' . $stripePlan->name . ') is successfully created.');
        // } else {
        //     flash('Your Plan (' . $stripePlan->name . ') is failed to create. Please contact your administrator for assistance.');
        // }
        // return redirect()->back();
        
    } 


    public function edit($id)
    {
        $plan = \Stripe\Plan::retrieve($id);
        return view('admin.subscriptionpack.edit', compact('plan'));
        
    }  
      /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $nickname=$request['nickname'];
        if ($request->has('toggleswitch')) {
            // $archived="helllo block user on";
             $status = false;
            // $user->is_active=0;
         } else{
            // $archived="bye block off";
             //$user->is_active=1;
             $status = true;
         }
        try {
        $plan = \Stripe\Plan::update($id,['nickname'=>$nickname,'active'=>$status]);
        return redirect('admin/subscriptionpack')->with('info','subscription plan with id ' . $id . ' Updated.');
        } catch (Exception $e) {
        
            return redirect('admin/subscriptionpack')->with('info',json_encode($e->getMessage(), true));
        }

    }
//     public function update(Request $request, $id)
//     {   $this->validate($request, [
//         'name' => 'required|max:255',
//         'duration' => 'required',
//         'rate' => 'required',
        

//     ]);
//     $input = $request->only('name', 'duration', 'rate');
//     $model = SubscriptionPack::find($id);
//     if(!$model){
//         return redirect('admin/subscriptionpack')->with('info','subscription with id ' . $id . ' cannot be found.'); 
//     }
//     else{$model->update($input); //update the subscription info

//         return redirect('admin/subscriptionpack')->with('info','Subscription updated Successfully');
//    }

        
        
//     }  
  /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */

    public function destroy($id)
    {

        try {
            $plan = \Stripe\Plan::retrieve($id);
            $plan->delete();
            return redirect('admin/subscriptionpack')->with('info','Subscription Plan Deleted Successfully');
            } catch (Exception $e) {
            
            return redirect('admin/subscriptionpack')->with('info','SubscriptionPack Not found'.json_encode($e->getMessage(), true));

            }
       

        
    }
}
