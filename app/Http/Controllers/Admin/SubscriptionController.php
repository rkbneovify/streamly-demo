<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SubscriptionNewReportExport;
use App\Exports\UsersSubscriptionExport;
use App\Jobs\CancelStripeSubscriptionJob;
use App\Jobs\SendUpgradeMailJob;
use App\Mail\SubscriptionNotificationMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SubscriptionUpgradeMail;
use App\Models\Subscription;
use App\Models\SubscriptionPack;
use App\Models\Zipcode;
use App\Services\StripeService;
use App\User;
use Carbon\Carbon;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;
use Stripe\Coupon;
use Stripe\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Maatwebsite\Excel\Facades\Excel;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription as StripeSubscription;

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

        Stripe::setApiKey(config('services.stripe.secret'));
    }
    /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $stripePlans = \Stripe\Plan::all();
        $stripePlanData = collect($stripePlans->data)
            ->groupBy('billing_scheme');
        $plankey = $stripePlanData->keys();

        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');
        // Extracting subscription status types
        $statusTypes = Subscription::whereHas('user', function ($query) use ($mode) {
            $query->where('mode', $mode);
        })->pluck('stripe_status')->unique()->values();


        $plans = collect($stripePlans->data)->map(function ($plan) {
            return [
                'id' => $plan->id,
                'billing_scheme' => $plan->billing_scheme,
            ];
        });
        $planIds = $plans->pluck('id');

        if (!empty($request->plan)) {
            //   Plans id according to billing scheme from stripe
            $plansFilteredByBillingScheme = collect($stripePlans->data)
                ->where('billing_scheme', $request->plan)->values();
            $plans = collect($plansFilteredByBillingScheme)->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'billing_scheme' => $plan->billing_scheme,
                ];
            });

            $planIds = $plans->pluck('id');
        }









        $query = Subscription::query()->with('user')
            ->whereHas('user', function ($query) use ($mode) {
                $query->where('mode', $mode);
            })
            ->whereIn('stripe_plan', $planIds);
        if ($request->sort_subscribe_at == null || $request->sort_subscribe_at == 'desc') {
            $query->orderByDesc('updated_at');
        } else {
            $query->orderBy('updated_at');
        }
        // Filter by status if provided
        if (!empty($request->status)) {

            $query->where('stripe_status', $request->status);
        }

        // Apply search filter if provided
        if (!empty($request->search)) {

            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $subscriptions = $query->paginate(10)->appends($request->query());

        // Add billing_scheme to each subscription in the paginated collection
        $subscriptions->getCollection()->transform(function ($subscription) use ($plans) {
            $plan = $plans->firstWhere('id', $subscription->stripe_plan);
            $subscription->billing_scheme = $plan ? $plan['billing_scheme'] : 'Unknown Plan';
            return $subscription;
        });

        if ($request->ajax()) {
            return view('admin.subscriptions.partials.table-new', compact('subscriptions', 'plankey', 'statusTypes', 'mode'));
        }

        return view('admin.subscriptions.tierindex', compact('subscriptions', 'plankey', 'statusTypes', 'mode'));
    }

    public function index2(Request $request, StripeService $stripeService)
    {


        $query = Subscription::query()
            ->whereHas('user')   // only subscriptions with a related user
            ->with('user');      // still eager load user data

        if ($request->sort_subscribe_at == null || $request->sort_subscribe_at == 'desc') {
            $query->orderByDesc('updated_at');
        } else {
            $query->orderBy('updated_at');
        }
        // Filter by status if provided
        if (!empty($request->status)) {

            $query->where('stripe_status', $request->status);
        }

        // Apply search filter if provided
        if (!empty($request->search)) {

            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $query->latest();

        $subscriptions = $query->paginate(10)
            ->appends($request->query());

        $subscriptions->getCollection()->transform(function ($item) use ($stripeService) {

            $item->mode = $stripeService->hasSubscription($item->stripe_id);
            return $item;
        });
        // return response()->json($subscriptions);





        $stripePlans = \Stripe\Plan::all();
        $stripePlanData = collect($stripePlans->data)
            ->groupBy('billing_scheme');
        $plankey = $stripePlanData->keys();

        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');
        // Extracting subscription status types
        $statusTypes = Subscription::whereHas('user', function ($query) use ($mode) {
            $query->where('mode', $mode);
        })->pluck('stripe_status')->unique()->values();













        if ($request->ajax()) {
            return view('admin.subscriptions.partials.table-new2', compact('subscriptions', 'plankey', 'statusTypes', 'mode'));
        }

        return view('admin.subscriptions.index-new', compact('subscriptions', 'plankey', 'statusTypes', 'mode'));
    }

    public function showAllcustomer()
    {
        $arrayStripeEmail = array();
        $arrayStripeEmailCustArray = array();
        // $users = User::all()->pluck('name', 'id');
        // $subscriptions = Subscription::paginate(20);
        //  $customers = \Stripe\Customer::all(['expand' => ['data.subscriptions']]);
        // $plans = \Stripe\Plan::all(['active'=>true]);
        $subscriptions = \Stripe\Subscription::all(['status' => 'active', 'expand' => ['data.plan', 'data.customer', 'data.items']]);
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

            echo ($key + 1) . ")." . $subscription->id . " created " . date("m-d-y", $subscription->created) . " stripe_status " . $subscription->status . " quantity " . $subscription->quantity . " plan " . $subscription->plan->id . " Email " . $subscription->customer->email . "items" . $subscription->items->data[0]->id . "<br>";
            dump($subscription->items->data[0]->id);
        }
        // echo "subscriptions".count($subscriptions->data);
        while ($subscriptions->has_more) {
            $startsubscriptioncount = count($subscriptions->data);
            // Add the `starting_after` parameter to reflect the last customer ID
            $subscriptions = \Stripe\Subscription::all(array("limit" => 100, "starting_after" => $subscription->id, 'expand' => ['data.plan', 'data.customer', 'data.items']));
            foreach ($subscriptions->data as $key => $subscription) {
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
                echo ($key + $startsubscriptioncount + 1) . ")." . $subscription->id . " created " . date("m-d-y", $subscription->created) . " stripe_status " . $subscription->status . " quantity " . $subscription->quantity . " plan " . $subscription->plan->id . " Email " . $subscription->customer->email . "items" . $subscription->items->data[0]->id . "<br>";
                dump($subscription->items->data[0]->id);
            }
        }
        echo "-----------end----------";
        // $item = new \Stripe\SubscriptionItem();
        dump($subscriptions->data);
        // dump($item);
        $DBuserEmails = User::select('email')->where([
            ['is_active', '=', '1'],
            ['email_verified_at', '<>', NULL],
            ['stripe_id', '<>', NULL],
        ])->pluck('email')->toArray();

        $differenceArray = array_diff($arrayStripeEmail, $DBuserEmails);
        dump($differenceArray);
        foreach ($arrayStripeEmailCustArray as $key => $object) {

            // echo $object->email."<br>";
            if (in_array($object->email, $differenceArray)) {
                // unset($arrayStripeEmailCustArray[$key];
                echo "key" . $key;
                echo "<br>Match found" . $object->email . "<br>";
            } else {
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
            echo ($key2 + 1) . ")." . $customer->id . " created " . date("m-d-y", $customer->created) . " email " . $customer->email . "<br>";
        }
        // While we have more results, iterate through them
        while ($customers->has_more) {
            $startcuscount = count($customers->data);
            // Add the `starting_after` parameter to reflect the last customer ID
            $customers = \Stripe\Customer::all(array("limit" => 100, "starting_after" => $customer->id));
            foreach ($customers->data as $key2 => $customer) {
                ($key2 + $startcuscount + 1) . ")." . $customer->id . " created " . date("m-d-y", $customer->created) . " email " . $customer->email . "<br>";
            }
        }

        //  $plans->data;//Customers::with('country')->paginate(20);
        // return view('admin.subscriptions.index', compact('subscriptions','plans','users'));

    }

    public function syncStripeUser()
    {
        $arrayStripeEmail = array();

        $arrayStripeEmailCustArray = array();
        $customers = \Stripe\Customer::all(['limit' => 100, 'expand' => ['data.subscriptions']]);
        //  foreach ($customers->autoPagingIterator() as $customer) {
        //     dump($customer->subscriptions->data);
        //   }
        //  $subscriptions = $customers->subscriptions->all();
        //  $customers = \Stripe\Plan::all();
        //  dump($subscriptions);
        echo "<br>";
        dump($customers->data);
        foreach ($customers->data as $key2 => $customer) {
            echo ($key2 + 1) . ")." . $customer->id . " created " . date("m-d-y", $customer->created) . " email " . $customer->email;
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
            $startcuscount = count($customers->data);
            // Add the `starting_after` parameter to reflect the last customer ID
            $customers = \Stripe\Customer::all(array("limit" => 100, "starting_after" => $customer->id, 'expand' => ['data.subscriptions']));
            foreach ($customers->data as $key2 => $customer) {
                ($key2 + $startcuscount + 1) . ")." . $customer->id . " created " . date("m-d-y", $customer->created) . " email " . $customer->email;
                // echo "sub".$customer->subscriptions->data['id']. "<br>";
                dump($customer->subscriptions->data);
                array_push($arrayStripeEmail, $customer->email);
                $arrayStripeEmailCust = new \stdClass;
                $arrayStripeEmailCust->email = $customer->email;
                $arrayStripeEmailCust->customerid = $customer->id;
                $arrayStripeEmailCust->created = date("m-d-y", $customer->created);
                array_push($arrayStripeEmailCustArray, $arrayStripeEmailCust);
            }
        }
        // whereIn('email', $emailAddresses)
        $DBuserEmails = User::select('email')->where([
            ['is_active', '=', '1'],
            ['email_verified_at', '<>', NULL],
            ['stripe_id', '<>', NULL],
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
                echo "key" . $key;
                echo "<br>Match found" . $object->email . "<br>";
            } else {
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
        if (!empty($differenceArray)) {
            $this->insertInDB($differenceArray);
        }


        //  $plans->data;//Customers::with('country')->paginate(20);
        // return view('admin.subscriptions.index', compact('subscriptions','plans','users'));

    }


    private function insertInDB($arrEmails)
    {
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
    public function paginatewithscroll()
    {
        $plans = \Stripe\Plan::all(['active' => true]);
        $allPlan = $plans->data;
        $planidArr = collect($allPlan)->pluck('id', 'nickname');
        // dd($planidArr);
        $customers = \Stripe\Customer::all();
        $customers = $customers->data;
        $customerData = collect($customers)->pluck('id', 'email');
        // dd($customerData);
        return view('admin.subscriptions.page', compact('planidArr', 'customerData'));
    }
    public function subscriptionstripe(Request $request)
    { //dd($request);

        $starting_after = $request->query('lastid') == 1 ? NULL : $request->query('lastid');
        $customer = $request->query('customer') ?? NULL;
        $plan = $request->query('plan') ?? NULL;
        // dd($starting_after); 
        $increment = 3;
        $customersJSON = \Stripe\Subscription::all(array(
            "customer" => $customer,
            "plan" => $plan, //will find it in plan.id
            // "customer"=>"cus_JgeZ3afo13HWyx",
            "limit" => $increment,
            "starting_after" => $starting_after,
            'expand' => ['data.plan', 'data.customer', 'data.items']
        ));
        return ['fulldata' => $customersJSON->data, 'has_more' => $customersJSON->has_more, 'lastid' => end($customersJSON->data)];
    }
    public function show($id)
    {   //$subscription = Subscription::find($id); 
        $user = User::all()->pluck('name', 'id');
        $subscription = Subscription::find($id);
        $plans = \Stripe\Plan::all();
        $plans->data;
        // dd($subscription);
        // $pack = SubscriptionPack::all()->pluck('name', 'id');

        return view('admin.subscriptions.show', compact('subscription', 'user', 'plans'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $STRIPE_KEY = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_key');
        $users = User::where('is_active', '=', '1')->get();
        $plans = \Stripe\Plan::all(['active' => true]);
        $plans->data;
        $pack = SubscriptionPack::where('is_active', '=', '1')->get();
        return view('admin.subscriptions.create', compact('users', 'pack', 'plans', 'STRIPE_KEY'));
    }
    public function store(Request $request)
    {
        // dd($request);exit;
        $user = User::find($request['users_id']);
        //echo trim($user);exit;
        //echo $user;exit;
        $productfullString = $request['product'];
        // $product=$request['product'];
        $explodedstring = explode("#~", $productfullString);
        $product = $explodedstring[0];
        $trial_days = $explodedstring[1];
        $coupon = $request['coupon'];
        // $trial_days=$request['trial_days'];


        try {
            // check already subscribed and if already subscribed with picked plan
            if ($user->subscribed('main') && !$user->subscribedToPlan($product, 'main')) {

                // swap if different plan attempt
                $user->subscription('main')->swap($product);
                return redirect('admin/subscriptions')->with('info', 'Subscriber existed ,so plan is swapped ');
            } else {

                // if user has a coupon, create new subscription with coupon applied
                if ($coupon = $request->get('coupon')) {
                    if (!empty($trial_days)) {
                        $user->newSubscription('main', $product)->trialDays($trial_days)
                            ->withCoupon($coupon)
                            ->create($request->payment_method, ['email' => $user->email]);
                    } else {
                        $user->newSubscription('main', $product)
                            ->withCoupon($coupon)
                            ->create($request->payment_method, ['email' => $user->email]);
                    }
                    return redirect('admin/subscriptions')->with('info', 'Subscriber Added Successfully with coupon');
                } else {
                    if (!empty($trial_days)) {
                        // Create subscription
                        $newSubscription = $user->newSubscription('main', $product)

                            ->trialDays($trial_days)->create($request->payment_method, ['email' => $user->email]);
                        return redirect('admin/subscriptions')->with('info', 'Subscriber Added Successfully');
                    }
                    $newSubscription = $user->newSubscription('main', $product)
                        // ->withCoupon($coupon)
                        ->create($request['payment_method'], ['email' => $user->email]);
                    return redirect('admin/subscriptions')->with('info', 'Subscriber Added Successfully');
                }
            }
        } catch (IncompletePayment $exception) {
            return redirect()->back()->with('info', json_encode($exception->getMessage(), true));
        } catch (Exception $exception) {


            return redirect()->back()->with('info', json_encode($exception->getMessage(), true));
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
        $userid = $subscription->user_id;

        $user = User::pluck('name', 'id');
        $plans = \Stripe\Plan::all();
        $plans->data;


        return view('admin.subscriptions.edit', compact('subscription', 'user', 'plans'));
    }
    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $product = $request['stripe_plan'];
        //dd($product);
        $planname = $request['name'];
        $userid = $request['user_id'];
        $user = User::find($userid);

        try {
            $user->subscription('main')->skipTrial()->swap($product);
            return redirect('admin/subscriptions')->with('info', 'Subscription updated Successfully');
        } catch (Exception $e) {
            return redirect('admin/subscriptions')->with('info', 'Subscription Error' . json_encode($e->getMessage(), true));
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
    {
        $user = User::find($id);

        try {
            $user->subscription('main')->skipTrial()->cancelNow();
            return redirect('admin/subscriptions')->with('info', 'Subscription Cancel Successfully');
        } catch (Exception $e) {
            return redirect('admin/subscriptions')->with('info', 'Error' . json_encode($e->getMessage(), true));
        }
    }

    public function changeStatus(Request $request)
    {
        $user = Subscription::find($request->user_id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => 'Status change successfully.']);
    }

    public function ajaxCheck(Request $request)
    { //dd($request);
        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');

        if ($request->has('coupon')) {
            \Stripe\Stripe::setApiKey($STRIPE_SECRET);
            try {
                $status = Coupon::retrieve($request->input('coupon'));
                if ($status->amount_off) {
                    $price = $status->currency . ' ' . $status->amount_off / 100 . ' Off ' . $status->duration;
                } else {
                    if ($status->duration == 'repeating') {
                        $duration = '(' . $status->duration_in_months . ' months)';
                    } else {
                        $duration = '(forever)';
                    }
                    $price = $status->percent_off . '% for ' . $duration;
                }
            } catch (\Stripe\Error\InvalidRequest $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return response()->json(['message' => 'Coupon is valid! & ' . $price, 'amountoff' => $status->amount_off / 100, 'percentoff' => $status->percent_off], 200);
        }
        if ($request->has('zip')) {
            $data = Zipcode::select('zipcode', 'city', 'state')->where([
                ['status', '=', '1'],
                ['zipcode', '=', $request['zip']]
            ])->get();

            if (count($data) == 0) {
                $data2 = Zipcode::select('zipcode', 'city', 'state')->where([

                    ['zipcode', '=', $request['zip']]
                ])->get();
                return response()->json(['message' => 'Zipcode not allowed or invalid', 'city' => $data2->pluck('city')->first() ?? 'Invalid City', 'state' => $data2->pluck('state')->first() ?? 'Invalid State'], 404);
            }
            return response()->json(['message' => 'Congratulations!  Your zipcode is in our service area.<br> Proceed to the next step to subscribe', 'city' => $data->pluck('city')->first(), 'state' => $data->pluck('state')->first()], 200);
        }
    }

    public function customUserView(Request $request, $id)
    {
        $plans = \Stripe\Plan::all([
            'expand' => ['data.tiers'],
            'active' => true,
        ]);

        $stripekey = DB::table('Stripe_keys')->where('status', 'active')->pluck('stripe_key')->first();

        $user = User::findOrFail($id);
        return view("admin.subscriptions.custom-user-create", compact("plans", "user", 'stripekey'));
    }
    /**
     * Store a new user and subscribe them to a plan on Stripe.
     */
    public function storeUserAndSubscription(Request $request)
    {


        $request->validate([
            'product' => "required|string|max:225",
            'name' => "required|string|max:225",
            'email' => "required|string|max:225",
            'address_line1' => "required|string|max:225",
            'address_city' => "required|string|max:225",
            'address_state' => "required|string|max:225",
            'address_country' => "required|string|max:225",
            'card_holder_name' => "required|string|max:225",
            'zip' => 'required|digits_between:5,8',
            'payment_method' => "required|string|max:225",
        ], [
            'product.required' => 'Please select plan.',
            'address_line1.required' => 'Address field is required.',
            'address_city.required' => 'City field is required.',
            'address_state.required' => 'State field is required.',
            'address_country.required' => 'Country field is required.',
            'zip.required' => 'Please enter your ZIP code.',
            'zip.digits_between' => 'ZIP code must be between 5 and 8 digits.',
            'payment_method.required' => 'Credit card info field is required.',
        ]);
        list($productId, $trialDays) = explode('|', $request->product);

        $plan = \Stripe\Plan::retrieve($productId);
        DB::beginTransaction();
        try {

            $user = User::find($request->user_id);
            if ($user->is_custom_user == 0 || $user->user_type != "custom") {
                alert()->error('Oops !', 'You can only update custom user on stripe.');
                return redirect()->back();
            }

            $customer = Customer::create([
                'email' => $request->email,
                'name' => $request->name,
                'metadata' => [
                    'user_id' => $request->user_id ?? 'N/A',
                ],
            ]);
            // update user Stripe customer id 
            if ($user != null && $user->is_custom_user == 1) {
                $user->update([
                    'stripe_id' => $customer->id
                ]);
            }
            PaymentMethod::retrieve($request->payment_method)
                ->attach(['customer' => $customer->id]);
            Customer::update($customer->id, [
                'invoice_settings' => [
                    'default_payment_method' => $request->payment_method
                ]
            ]);
            $subscription = StripeSubscription::create([
                'customer' => $customer->id,
                'items' => [
                    ['price' => $plan->id]
                ],
                'expand' => ['latest_invoice.payment_intent'], // To get payment status
            ]);

            // update subscription detail on db
            $subsEndDate = Carbon::createFromTimestamp($subscription->current_period_end)->toDateTimeString();
            $userSubs = Subscription::where('user_id', $user->id)->first();
            $trialDaysEndDate = null;
            if (!empty($trialDays) && $trialDays != "") {
                $trialDaysEndDate = Carbon::now()->addDays($trialDays)->toDateTimeString();
            }

            if ($userSubs != null) {
                $userSubs->stripe_id = $subscription->id;
                $userSubs->stripe_plan = $plan->id;
                $userSubs->stripe_status = $subscription->status;
                $userSubs->quantity = $subscription->items->data[0]->quantity;
                $userSubs->trial_ends_at = $trialDaysEndDate;
                $userSubs->renews_at = $subsEndDate;
                $userSubs->ends_at = $subsEndDate;
                $userSubs->created_at = now();
                $userSubs->save();
            }
            // User update on stripe flag
            $user->is_live_on_stripe = 1;
            $user->save();
            DB::commit();
            Alert::success('Success', 'Subscription update on Stripe successfully !');
            return redirect()->route('admin.users.custom.list');
        } catch (\Throwable $th) {
            DB::rollBack();
            // Alert::error('Error', $th->getMessage());
            Alert::error('Error', "Something went wrong. Please try again later.");
            return redirect()->back();
        }
    }

    public function subscriptionUpgradeMail($id)
    {
        try {
            $user = User::find($id);
            Mail::to($user->email)->send(new SubscriptionUpgradeMail($user));
            Alert::success("Success ", "Mail sent successfully !");
            return redirect()->back();
        } catch (\Throwable $th) {
            Alert::error("Error !", $th->getMessage());
            return redirect()->back();
        }
    }

    public function subscriptionCancelById($id)
    {
        try {
            $user = User::find($id);
            if ($user->is_live_on_stripe == 1) {
                $userSubs = Subscription::where('user_id', $user->id)->first();
                if ($userSubs == null) {
                    Alert::error("Error !", "Subscription not found !.");
                    return redirect()->back();
                }
                $subscription = \Stripe\Subscription::retrieve($userSubs->stripe_id);
                $subscription->cancel();
                $userSubs->stripe_status = $subscription->status;
                $userSubs->save();
            } else {
                $userSubs = Subscription::where('user_id', $user->id)->first();
                if ($userSubs == null) {
                    Alert::error("Error !", "Subscription not found !.");
                    return redirect()->back();
                }
                $userSubs->stripe_status = "canceled";
                $userSubs->save();
            }
            Alert::success("Success ", "Subscription Canceled successfully !");
            return redirect()->back();
        } catch (\Throwable $th) {
            Alert::error("Error !", $th->getMessage());
            return redirect()->back();
        }
    }

    public function bulkAction(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:1',
        ], [
            'user_ids.required' => 'Please select at least one user.',
            'user_ids.array' => 'Invalid data format for selected user.',
            'user_ids.min' => 'You must select at least one user before proceeding.',
        ]);
        if ($validator->fails()) {
            toast($validator->errors()->first(), "error")
                ->timerProgressBar()
                ->autoClose(5000);
            return redirect()->back();
        }


        $users = User::whereIn('id', $request->user_ids)->get();
        switch ($request->action) {
            case 'send-mail':
                try {
                    dispatch(new SendUpgradeMailJob($users));
                    Alert::success("Success ", "Mail sent successfully !");
                    return redirect()->back();
                } catch (\Throwable $th) {
                    Alert::success("Error ", $th->getMessage());
                    return redirect()->back();
                }

            case 'subscription-cancel':
                try {
                    dispatch(new CancelStripeSubscriptionJob($users));
                    Alert::success("Success ", "Subscription cancel successfully !");
                    return redirect()->back();
                } catch (\Throwable $th) {
                    Alert::success("Error ", $th->getMessage());
                    return redirect()->back();
                }

            default:
                Alert::error("Error ", "Please select any action");
                redirect()->back();
                break;
        }
    }

    public function exportUsersSubscription(Request $request, $type)
    {
        try {
            $filters = $request->only(['sort_subscribe_at', 'status']);
            $filename = 'subscription_' . now()->format('d-m-Y_H-i-s') . '.' . $type;
            // return Excel::download(new UsersSubscriptionExport($filters), $filename);
            return Excel::download(new SubscriptionNewReportExport($filters), $filename);
        } catch (\Throwable $th) {
            //throw $th;
            Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }


    public function paymentSuccess(Request $request)
    {
        // try {
        //     $sessionId = $request->session_id;
        //     $StripeSecretKey = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');
        //     $stripe = new StripeClient($StripeSecretKey);
        //     $session = $stripe->checkout->sessions->retrieve($sessionId, []);

        //     $subscriptionId = $session->subscription;
        //     $customerId = $session->customer;
        //     $userId = $session->metadata->user_id;
        //     $
        //     $userSubs = Subscription::where('user_id', $userId)->first();
        //     $subscription = \Stripe\Subscription::retrieve($subscriptionId);
        //     $subsEndDate = Carbon::createFromTimestamp($subscription->current_period_end)->toDateTimeString();
        //     $trialDaysEndDate = null;
        //     if (!empty($trialDays) && $trialDays != "") {
        //         $trialDaysEndDate = Carbon::now()->addDays($subscription->plan->trial_period_days)->toDateTimeString();
        //     }

        //     if ($userSubs != null) {
        //         $userSubs->stripe_id = $subscription->id;
        //         $userSubs->stripe_plan = $subscription->plan->id;
        //         $userSubs->stripe_status = $subscription->status;
        //         $userSubs->quantity = $subscription->quantity;
        //         $userSubs->trial_ends_at = $trialDaysEndDate;
        //         $userSubs->renews_at = $subsEndDate;
        //         $userSubs->ends_at = $subsEndDate;
        //         $userSubs->created_at = now();
        //         $userSubs->save();
        //     }
        //     // User update on stripe flag
        //     $user->is_live_on_stripe = 1;
        //     $user->save();
        //     return response()->json(["session" => $session, "subscription" => $subscription]);
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     dd($th->getMessage());
        // }
    }
}
