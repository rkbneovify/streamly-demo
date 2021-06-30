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
        $plan = \Stripe\Plan::all(['expand' => ['data.tiers']]);
        $product = \Stripe\Product::all();
        // dd($plan->data);
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
            $plan_args=[];
            // if($billing_scheme=='per_unit'){
            // billing_scheme => 'per_unit'}else{[billing_scheme => 'tiered','up_to'=>'5','flat_amount'=>'']},
            // $plan_args = array('amount' => $amount, 'interval' => $interval, 'name' => $name, 'currency' => $currency, 'id' => $plan_id, 'interval_count' => $interval_count);
            $plan_args=array("id" => $plan_id, 
            "name" => $request->plan_name, 
            "currency" => "USD", 
            "amount" => $amount, 
            "interval" => $interval, 
            "interval_count" => $interval_count,
            'trial_period_days' => $request->trial_period_days);  
        $plan = \Stripe\Plan::create($plan_args);
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
        $billing_scheme=$request->billing_scheme;
        // dump($request->billing_scheme);
        // dd($request);
        $planParams= $this->GetPlanParams($request);
        switch($billing_scheme){
            case 'tiered':
                $billing_scheme = 'tiered';
                $tiers= $this->ProcessTiers($request->rows);
                $tierDetails= array("tiers" => $tiers, 
                "billing_scheme" => "tiered", 
                "tiers_mode" => $request->billing_scheme);
                $planParams = array_merge($planparam, $tierDetails);
                    
                break;
            case 'volume':
                $billing_scheme = 'volume';
                $tiers= $this->ProcessTiers($request->rows);
                $tierDetails= array("tiers" => $tiers, 
                "billing_scheme" => "tiered", 
                "tiers_mode" => $request->billing_scheme);
                $planParams = array_merge($planParams, $tierDetails);
                break;
            case 'per_unit':
                $billing_scheme = 'per_unit';
                $amount= $this->convertpricetonumber($request->plan_amount);
                $amountDetails= array(
                "amount" => $amount);
                $planParams = array_merge($planParams, $amountDetails);
                break;
            default:
                $billing_scheme = 'per_unit';
                $amount= $this->convertpricetonumber($request->plan_amount);
                $amountDetails= array(
                    "amount" => $amount);
                    $planParams = array_merge($planParams, $amountDetails);
                break;
        }
       
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
           
        $plan = \Stripe\Plan::create($planParams);
        
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


    private function GetPlanInterval($request){
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

        return [$interval_count,$interval];
    }
    private function GetPlanParams($request){
       
       $intervalvalues= $this->GetPlanInterval($request);
       $interval_count = (int) $intervalvalues[0];
                $interval = $intervalvalues[1];
        $plan_id=Str::random(12);
        $product_id=$request->product;
        $planParams = array(
            "id" => $plan_id,
            "nickname" => $request->plan_name,
            "currency" => "USD",
            "interval" => $interval,
            "interval_count" => $interval_count,
            "product" => $product_id,
            "trial_period_days" => $request->trial_period_days,
            "metadata" => $request->text
        );
        return $planParams;
    }
    public function ProcessTiers($data){
//   dd(array_key_last($data));
// $lastEl = array_values(array_slice($data, -1))[0];
// dump($data);
// dd($lastEl['up_to']);
$find=['unit_amount','flat_amount'];
 $lastKey = array_key_last($data);
 $lastValue= $data[$lastKey]['up_to'];

array_walk_recursive($data, function (&$v, $k)use($find,$lastValue) { 
    if(in_array($k,$find)){ 
        $v = $this->convertpricetonumber($v); 
    } 
    if($k == 'up_to'){
        $v=(int)$v;
     }
     if($k == 'up_to' && $v==$lastValue) {
        $v='inf';
      }
     
        
});

return $data;

}

public function convertpricetonumber($rawamountprice){
    $rawamount = number_format($rawamountprice, 2);
    $amount = (int) ($rawamount * 100);
    return $amount;
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
        // dd($request->text);
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
        $plan = \Stripe\Plan::update($id,['nickname'=>$nickname,'active'=>$status,'trial_period_days' => $request->trial_period_days,'metadata' => $request->text]);
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
