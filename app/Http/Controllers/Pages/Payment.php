<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FrontendText;
use Carbon\Carbon;
use App\Models\Role;
use Laravel\Cashier\Exceptions\IncompletePayment;
use App\Models\Channel;
use Illuminate\Support\Facades\DB;
class Payment extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }
    public function showTierPage($stripeplanID){
        if( Auth::check()){
            $login = true;
        } else {
            $login = false;

            return redirect()->route('home');
        }
        $plandetails = \Stripe\Plan::retrieve($stripeplanID,['expand' => ['data.tiers']]);

      $interval = $plandetails->interval;
      $interval_count = $plandetails->interval_count;
        $ApproxEndDateOfSubscription = Carbon::now()->add($interval_count, $interval);
        //  dd(Auth::user()->invoices());
        //  $catIds = array_column($plandetails->tiers, 'up_to');
        //  dump($plandetails->tiers);
        //  dd($catIds);
        $frontendjson = FrontendText::find(1)->frontendjson;
        if (Auth::user()->subscription('main') && Auth::user()->subscription('main')->cancelled() ){
        return view('frontend.pages.paymentcancelledtier', compact('login','stripeplanID','plandetails','frontendjson','ApproxEndDateOfSubscription'));
    }else{
        return view('frontend.pages.paymenttier', compact('login','stripeplanID','plandetails','frontendjson','ApproxEndDateOfSubscription'));
    }
       


    }
    public function showPage($stripeplanID){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;

            return redirect()->route('home');
        }
        $plandetails = \Stripe\Plan::retrieve($stripeplanID);
        
        $frontendjson = FrontendText::find(1)->frontendjson;
        if (Auth::user()->subscription('main') && Auth::user()->subscription('main')->cancelled() ){
        return view('frontend.pages.paymentcancelled', compact('login','stripeplanID','plandetails','frontendjson'));
    }else{
        return view('frontend.pages.payment', compact('login','stripeplanID','plandetails','frontendjson'));
    }
        
        
    }
    public function showPlan(){
    
        if (Auth::user()->subscribed('main')) {
            
            return redirect()->route('payment',[Auth::user()->subscription('main')->stripe_plan]);
        } 
        
        $plans = \Stripe\Plan::all(['active'=>true,'expand' => ['data.tiers']]);
        $imagePath=asset("/uploads/channellogo/notfound.png");
   
$second=Channel::toBase()->select('channels.id',DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),
DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'), 'channels.image',DB::raw('IFNULL(genre.name,"Others") genrenae'))
       
       ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
       ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
       ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')

->where('channels.status','=','1')
->orderBy('channels.StationName', 'asc');



$pagechannel=$second->get()
->reject(function($element) use($imagePath) {
    
    return $element->image === null;
})->groupBy('genrenae');

$Genrekey=$pagechannel->keys();

    //   dump($plans->data);
    $d=collect($plans->data)
    ->groupBy('billing_scheme');
  //   ->where('billing_scheme', 'per_unit')
    
    $plansdetails=$d->all();
    $plankey=$d->keys();
    	return view('frontend.pages.plans', compact('plans','pagechannel','Genrekey','plansdetails','plankey'));
        
    }

    public function pay(Request $request,$StripeplanId) {
        // dd($request);
        $attributeNames = array(
            'zip' => 'Zip Code',
         );

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'zip' => 'required|regex:/^[0-9]{3,7}$/',
            'zip'=>'required|exists:zipcodes,zipcode,status,1'
        ], ['zip.exists'=>'Service not available at this zipcode or your zipcode is not valid'], $attributeNames);
        $user = auth()->user();
        $trial_days=$request->trial_days;
        // dump($request->tier);
        // dump($request->quantity);
        // dump($request->tiers_modes);volumegraduated
        // dump($request->billing_scheme);per_unit tiered
        // dump($request->quantity);
        $quantity=$request->quantity??1;
        // dd($StripeplanId);
        $previousURL=$request->input('previousurl');
        try {
            // check already subscribed and if already subscribed with picked plan
        if( $user->subscribed('main') && ! $user->subscribedToPlan($StripeplanId, 'main') ) {

            // swap if different plan attempt
            $user->subscription('main')->quantity($quantity)->swap($StripeplanId);

        }else {
            
           // if user has a coupon, create new subscription with coupon applied
           if( $coupon = $request->get('coupon') ) {
               $this->withCouponCheckTrialDays($user,$request,$coupon,$StripeplanId,$quantity,$trial_days);
           

        } else {
            $this->withoutCouponCheckTrialDays($user,$request,$StripeplanId,$quantity,$trial_days);
        }}
        $this->updateUserRoleBasedOnSubscription($user,$request);
        CheckPreviousHotelRooms($quantity,$user->id);
        } catch(\Stripe\Exception\CardException $e) {
            $this->theCatchError($e,$previousURL);
          } catch (\Stripe\Exception\RateLimitException $e) {
            $this->theCatchError($e,$previousURL);
          } catch (\Stripe\Exception\InvalidRequestException $e) {
            $this->theCatchError($e,$previousURL);
          } catch (\Stripe\Exception\AuthenticationException $e) {
            $this->theCatchError($e,$previousURL);
          } catch (\Stripe\Exception\ApiConnectionException $e) {
            $this->theCatchError($e,$previousURL);
          } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->theCatchError($e,$previousURL);
          } catch (Exception $e) {
            $this->theCatchError($e,$previousURL);
          }

        return redirect()->back();
    }
    public function updateUserRoleBasedOnSubscription($user,$request){
        if($request->tier=='tiered'){
            $tieruserrole=Role::where('name','tieruser')->first();
            
        $userRole=  $user->roles->first()->display_name;
        
        if( $userRole!== 'admin'||'superadmin') {
        
            $user->roles()->sync([$tieruserrole->id]);
        }
             
            
        }else{
            $tieruserrole=Role::where('name','user')->first();
            
            $userRole=  $user->roles->first()->display_name;
            
            if( $userRole!== 'admin'||'superadmin') {
            
                $user->roles()->sync([$tieruserrole->id]);
            }

        }
    }
    public function withCouponCheckTrialDays($user,$request,$coupon,$StripeplanId,$quantity,$trial_days){

        if(!empty($trial_days)){
            $user->newSubscription( 'main', $StripeplanId)->quantity($quantity)->trialDays($trial_days)
            ->withCoupon($coupon)
            ->create($request->payment_method, ['email' => $user->email]); 
        }else{ $user->newSubscription( 'main', $StripeplanId)
            ->quantity($quantity)
                ->withCoupon($coupon)
                ->create($request->payment_method, ['email' => $user->email]);}

    }
     public function withoutCouponCheckTrialDays($user,$request,$StripeplanId,$quantity,$trial_days){

        //check for trial days
            // Create subscription
            if(!empty($trial_days)){
                $newSubscription = $user->newSubscription('main', $StripeplanId)
                ->quantity($quantity)
                ->trialDays($trial_days)->create($request->payment_method, ['email' => $user->email]);
            }else{
                    $newSubscription = $user->newSubscription('main', $StripeplanId)
                    ->quantity($quantity)
                    ->create($request->payment_method, ['email' => $user->email]);}

    }
    public function persist($user)
    {

    //   $subscription = $user->newSubscription('default', $this->input('plan'))->create($this->input('token'));
    
    //   return $subscription->owner()->subscribed(); //returns true
    }
    public function theCatchError($e,$previousURL) {

            // Since it's a decline, \Stripe\Exception\CardException will be caught
            echo 'Status is:' . $e->getHttpStatus() . '\n';
            echo 'Type is:' . $e->getError()->type . '\n';
            echo 'Code is:' . $e->getError()->code . '\n';
            // param is '' in this case
            echo 'Param is:' . $e->getError()->param . '\n';
            echo 'Message is:' . $e->getError()->message . '\n';

            return redirect($previousURL)->with(['error_message' => $e->getMessage()]);
    }
    
}
