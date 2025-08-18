<?php

namespace App\Http\Controllers\Admin;


use App\Exports\SubscriptionReportExport;
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
use App\Models\Subscription;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function userdetails(Request $request)
    {

        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');

        $userIds = DB::table('users')
            ->where('mode', $mode)
            ->pluck('id')
            ->toArray();

        $query  = SessionTable::query()->with('user')
            ->selectRaw("users_id , count(id) activesession")
            ->where("status", '1')
            ->whereIn('users_id', $userIds);

        if (!empty($request->search)) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $allSession = $query->groupBy('users_id')->paginate(10)->appends(request()->query());

        if ($request->ajax()) {
            return view('admin.reports.user-detail-table', compact('allSession'));
        }
        return view('admin.reports.userdetails', compact('allSession'));
        // return $allSession;
    }

    public function channelanalysis()
    {

        return 'Under Maintenance';
    }

    public function TransactionsReportsOld()
    {

        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.stripe.com/v1/payment_intents?limit=20',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . $STRIPE_SECRET . ''
            ),
        ));

        $response = curl_exec($curl);
        $newdatatrans = json_decode($response);
        curl_close($curl);

        // dd($newdatatrans->data);
        // die();

        $alltransactions = [];
        for ($x = 0; $x < count($newdatatrans->data); $x++) {

            if ($newdatatrans->data[$x]->status == 'succeeded') {
                $status = 'Succeeded';
                $color = '#8df93b';
            } else {
                $status = 'Failed';
                $color = '#f94848';
            }

            if ($newdatatrans->data[$x]->description == 'Subscription creation') {
                $paymentDescription = 'Subscription Creation';
            } else {
                $paymentDescription = 'Subscription Update';
            }

            $userDetails = DB::table('users')->select('email', 'name')->where('stripe_id', $newdatatrans->data[$x]->customer)->get();
            if ($userDetails->isEmpty()) {
                continue; // Skip this transaction if user not found
            }

            $transaction = [
                'id' => $newdatatrans->data[$x]->id,
                'name' => $userDetails[0]->name,
                'email' => $userDetails[0]->email,
                'type' => $paymentDescription,
                'status' => $status,
                'date' => date("Y-m-d H:i:s", $newdatatrans->data[$x]->created),
                'amount' => $newdatatrans->data[$x]->amount,
                'color' => $color
            ];

            array_push($alltransactions, $transaction);
        }

        $serial_no = 1;
        $page_no = 2;
        $has_more = $newdatatrans->has_more;

        // dd($alltransactions);
        // die();



        return view('admin.reports.transactionsreport', compact('alltransactions', 'serial_no', 'has_more', 'page_no'));
    }
    public function TransactionsReports(Request $request)
    {





        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');
        Stripe::setApiKey($STRIPE_SECRET);


        // $paymentIntents = \Stripe\PaymentIntent::all([
        //     'customer' => 'cus_P3GSZBpYVPkJZ2', // Customer ID
        //     'limit' => 10,
        // ]);

        // return response()->json($paymentIntents);



        $params = ['limit' => 10];
        // Date range filter
        if ($request->daterange !== null) {
            [$startDate, $endDate] = explode(' - ', $request->daterange);

            // Convert to timestamps
            $startTimestamp = strtotime($startDate . ' 00:00:00');
            $endTimestamp = strtotime($endDate . ' 23:59:59');
            $params['created'] = [
                'gte' => $startTimestamp,
                'lte' => $endTimestamp
            ];
        }


        // Fetch payment intents

        if ($request->page == null) {
            $request->merge(['page' => 1]);
            Session::put('page', 1);
        } else {
            Session::put('page', $request->page);
        }
        $startingAfter = request()->get('starting_after');
        $endingBefore = request()->get('ending_before');



        if ($startingAfter) {
            $params['starting_after'] = $startingAfter;
        } elseif ($endingBefore) {
            $params['ending_before'] = $endingBefore;
        }
        // return response()->json($params);
        try {
            $paymentIntents = PaymentIntent::all($params);
            // $paymentIntents = PaymentIntent::all($params);
            // $alltransactions = $this->PaymentModifyData($paymentIntents);
            // $previousPageUrl = $paymentIntents->has_more  ? url()->current() . '?ending_before=' . reset($paymentIntents->data)->id . '&page=' . ($request->page - 1) : null;
            // $nextPageUrl = $paymentIntents->has_more || $request->page == 1 ? url()->current() . '?starting_after=' . end($paymentIntents->data)->id . '&page=' . ($request->page + 1) : null;
            // return response()->json([
            //     'data' => $paymentIntents,
            //     'payments' => $this->PaymentModifyData($paymentIntents),
            //     'previous_page_url' => $paymentIntents->has_more  ? url()->current() . '?ending_before=' . reset($paymentIntents->data)->id . '&page=' . ($request->page - 1) : null,
            //     'next_page_url' => $paymentIntents->has_more ? url()->current() . '?starting_after=' . end($paymentIntents->data)->id . '&page=' . ($request->page + 1) : null,

            // ], 200);




            $alltransactions = $this->PaymentModifyData($paymentIntents);
            $previousPageUrl = $request->page < 2 ? ($request->page <= 1 ? null : url()->current()) : url()->current() . '?page=' . ($request->page - 1) . '&ending_before=' . reset($paymentIntents->data)->id;
            $nextPageUrl = $paymentIntents->has_more || $request->page == 1 ? url()->current() . '?page=' . ($request->page + 1) . '&starting_after=' . end($paymentIntents->data)->id : null;

            if ($request->query() && $request->query('daterange') !== null) {
                $previousPageUrl = $request->page < 2 ? ($request->page <= 1 ? null : url()->current()) : url()->current() . '?daterange=' . $request->query('daterange') . '&page=' . ($request->page - 1) . '&ending_before=' . reset($paymentIntents->data)->id;
                $nextPageUrl = $paymentIntents->has_more || $request->page == 1 ? url()->current() . '?daterange=' . $request->query('daterange') . '&page=' . ($request->page + 1) . '&starting_after=' . end($paymentIntents->data)->id : null;
            }



            if ($request->ajax()) {
                return view('admin.reports.table', compact('alltransactions', 'previousPageUrl', 'nextPageUrl'));
            }
            return view('admin.reports.transactionsreport', compact('alltransactions', 'previousPageUrl', 'nextPageUrl'));
        } catch (\Throwable $th) {
            //throw $th;
            // dd('Error: ' . $th->getMessage());
        }
    }



    private function PaymentModifyData($paymentIntents)
    {
        $payments = collect($paymentIntents->data)->map(function ($intent) {
            $user = User::where('stripe_id', $intent->customer)->first(); // Ensure user exists
            if ($user !== null) {
                return [
                    'id' => $intent->id,
                    'amount' => $intent->amount,
                    'status' => $intent->status,
                    'created' => date("Y-m-d H:i:s", $intent->created),
                    'customer' => $intent->customer,
                    'currency' => $intent->currency,
                    'name' => $user ? $user->name : null,
                    'email' => $user ? $user->email : null
                ];
            }
        });
        return $payments->filter()->values();
    }

    public function TransactionspagingReports($order, $id, $no, $number)
    {

        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.stripe.com/v1/payment_intents?limit=20&' . $order . '=' . $id . '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . $STRIPE_SECRET . ''
            ),
        ));

        $response = curl_exec($curl);
        $newdatatrans = json_decode($response);
        curl_close($curl);

        $alltransactions = [];
        for ($x = 0; $x < count($newdatatrans->data); $x++) {

            if ($newdatatrans->data[$x]->status == 'succeeded') {
                $status = 'Succeeded';
                $color = '#8df93b';
            } else {
                $status = 'Failed';
                $color = '#f94848';
            }

            if ($newdatatrans->data[$x]->description == 'Subscription creation') {
                $paymentDescription = 'Subscription Creation';
            } else {
                $paymentDescription = 'Subscription Update';
            }

            $userDetails = DB::table('users')->select('email', 'name')->where('stripe_id', $newdatatrans->data[$x]->customer)->get();

            $transaction = [
                'id' => $newdatatrans->data[$x]->id,
                'name' => $userDetails[0]->name,
                'email' => $userDetails[0]->email,
                'type' => $paymentDescription,
                'status' => $status,
                'date' => date("Y-m-d H:i:s", $newdatatrans->data[$x]->created),
                'amount' => $newdatatrans->data[$x]->amount,
                'color' => $color
            ];

            array_push($alltransactions, $transaction);
        }

        //  dd($alltransactions);
        //  die();
        $serial_no = $no + 20;
        $page_no = $number + 1;
        $has_more = $newdatatrans->has_more;

        return view('admin.reports.transactionsreport', compact('alltransactions', 'serial_no', 'has_more', 'page_no'));
    }

    public function Transactionsview($id)
    {

        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.stripe.com//v1/payment_intents/' . $id . '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . $STRIPE_SECRET . ''
            ),
        ));

        $response = curl_exec($curl);
        $viewTransaction = json_decode($response);
        curl_close($curl);

        $singletransaction = [];

        if ($viewTransaction->status == 'succeeded') {
            $status = 'Succeeded';
            $color = '#65a734';
        } else {
            $status = 'Failed';
            $color = '#f94848';
        }

        $email = DB::table('users')->where('stripe_id', $viewTransaction->customer)->value('email');

        $view = [
            'id' => $viewTransaction->id,
            'amount' => $viewTransaction->amount,
            'name' => $viewTransaction->charges->data[0]->billing_details->name,
            'email' => $email,
            'status' => $status,
            'color' => $color,
            'date' => date("Y-m-d H:i:s", $viewTransaction->charges->data[0]->created),
            'risk_level' => $viewTransaction->charges->data[0]->outcome->risk_level,
            'customer' => $viewTransaction->charges->data[0]->customer,
            'currency' => $viewTransaction->charges->data[0]->currency,
            'last4' => $viewTransaction->charges->data[0]->payment_method_details->card->last4,
            'exp_year' => $viewTransaction->charges->data[0]->payment_method_details->card->exp_year,
            'exp_month' => $viewTransaction->charges->data[0]->payment_method_details->card->exp_month,
            'receipt_url' => $viewTransaction->charges->data[0]->receipt_url,
            'address' => json_decode(json_encode($viewTransaction->charges->data[0]->billing_details->address), true)
        ];

        array_push($singletransaction, $view);

        //dd($singletransaction);

        return view('admin.reports.viewtransaction', compact('singletransaction'));
    }

    public function SubscriptionsReportsOld($sort)
    {

        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');

        $userIds = DB::table('users')
            ->where('mode', $mode)
            ->pluck('id')
            ->toArray();

        $transaction = DB::table('subscriptions')
            ->select('id', 'user_id', 'stripe_plan', 'stripe_status', 'trial_ends_at', 'renews_at', 'ends_at', 'created_at')
            ->whereIn('user_id', $userIds)
            ->orderBy('created_at', $sort)
            ->get();



        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');

        $array = [];
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
                'Authorization: Bearer ' . $STRIPE_SECRET . ''
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
        foreach ($transaction as $data) {

            if ($data->renews_at == null && $data->stripe_status == 'active') {
                $email = DB::table('users')->where('id', $data->user_id)->value('email');
                $name = DB::table('users')->where('id', $data->user_id)->value('name');
                $newUser = [
                    'name' => $name,
                    'email' => $email
                ];
                array_push($arrayRes1, $newUser);
            } elseif (!empty($data->renews_at) && $data->stripe_status == 'active') {
                $email = DB::table('users')->where('id', $data->user_id)->value('email');
                $name = DB::table('users')->where('id', $data->user_id)->value('name');
                $renewedUser = [
                    'name' => $name,
                    'email' => $email
                ];
                array_push($arrayRes2, $renewedUser);
            } elseif ($data->stripe_status == 'canceled') {
                $email = DB::table('users')->where('id', $data->user_id)->value('email');
                $name = DB::table('users')->where('id', $data->user_id)->value('name');
                $terminatedUser = [
                    'name' => $name,
                    'email' => $email
                ];
                array_push($arrayRes3, $terminatedUser);
            } else {
                $email = DB::table('users')->where('id', $data->user_id)->value('email');
                $name = DB::table('users')->where('id', $data->user_id)->value('name');
                $overdueUser = [
                    'name' => $name,
                    'email' => $email
                ];
                array_push($arrayRes4, $overdueUser);
            }

            $userEmail = DB::table('users')->where('id', $data->user_id)->value('email');
            $userZipcode = DB::table('users')->where('id', $data->user_id)->value('zipcode');

            if (!empty($data->renews_at)) {
                $time = strtotime($data->renews_at);
            } else {
                $time = strtotime($data->created_at);
            }

            for ($k = 0; $k < count($stripeplans); $k++) {
                if ($data->stripe_plan == $stripeplans[$k]['id']) {
                    $final = date("Y-m-d H:i:s", strtotime("+" . $stripeplans[$k]['interval_count'] . $stripeplans[$k]['interval'] . "", $time));
                    break;
                } else {
                    $final = date("Y-m-d H:i:s", strtotime("1 month"));
                }
            }

            $arry = [
                'id' => $data->id,
                'email' => $userEmail,
                'zipcode' => $userZipcode,
                'stripe_status' => $data->stripe_status,
                'start_at' => $data->created_at,
                'ends_at' => $data->renews_at,
                'cancle_at' => $data->ends_at,
                'trial_ends_at' => $data->trial_ends_at,
                'null' => $final,
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

        return view('admin.reports.subscriptionsreport', compact('resultCount'));

        //return $resultCount;
    }

    public function SubscriptionsReports(Request $request)
    {

        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');

        $userIds = DB::table('users')
            ->where('mode', $mode)
            ->pluck('id')
            ->toArray();


        $query = Subscription::query();
        $query = $query->with('user:id,name,email,zipcode,stripe_id')
            ->select('id', 'user_id', 'stripe_id', 'stripe_plan', 'stripe_status', 'trial_ends_at', 'renews_at', 'ends_at', 'created_at')
            ->whereIn('user_id', $userIds)
            ->whereNotIn('stripe_plan', ['price_1R7AsbJSdAGJhCxpc0nHB1e8', 'lLqM2zp3oUMO', 'price_1MqxXZGbCi8tAySGisSrhf0S', '4NTNZkcX4wYM'])
            ->orderBy('created_at', 'desc');

        if (!empty($request->status)) {
            $query->where('stripe_status', $request->status);
        }

        if (!empty($request->search)) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                    ->orWhere('zipcode', 'like', '%' . $request->search . '%');
            });
        }

        $transaction =   $query->paginate(10)->appends($request->query());

        $transaction->getCollection()->transform(function ($item) {
            $plan = \Stripe\Plan::retrieve($item->stripe_plan);
            $product = \Stripe\Product::retrieve($plan->product);
            $item->plan_name = $product->name;
            $item->plan_desc = $product->description;
            return $item;
        });



        if ($request->ajax()) {
            return view('admin.reports.subscription-report-table', compact('transaction'));
        }
        return view('admin.reports.subscriptionsreport', compact('transaction'));

        //return $resultCount;
    }

    public function subscriptionDetailView($subscriptionId)
    {
        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');
        Stripe::setApiKey($STRIPE_SECRET);

        // if provide user id in subscriptionId params
        if (strpos($subscriptionId, 'sub_') === false) {
            $subscriptionId = Subscription::where('user_id', $subscriptionId)->first()->stripe_id;
        }

        $subscription = \Stripe\Subscription::retrieve($subscriptionId);
        $customer = \Stripe\Customer::retrieve($subscription->customer);
        $user = User::where('email', $customer->email)->first();
        $invoices = \Stripe\Invoice::all([
            'customer' => $subscription->customer,
            'limit' => 100
        ]);

        $subscriptions = \Stripe\Subscription::all([
            'customer' => $subscription->customer,
            'status'   => 'all', // active, trialing, canceled, unpaid, or all
            'limit'    => 3
        ]);


        // return response()->json($subscriptions);
        return view("admin.reports.subscription-detail", compact("subscription", 'user', 'invoices'));
    }

    function getAllStripeCustomers($secretKey)
    {
        Stripe::setApiKey($secretKey);

        $allCustomers = [];
        $hasMore = true;
        $startingAfter = null;

        while ($hasMore) {
            $params = ['limit' => 100];
            if ($startingAfter) {
                $params['starting_after'] = $startingAfter;
            }

            $response = \Stripe\Customer::all($params);
            $allCustomers = array_merge($allCustomers, $response->data);

            $hasMore = $response->has_more;
            $startingAfter = end($response->data)->id ?? null;
        }

        return $allCustomers;
    }



    public function SubscriptionsReportstimeframe($to, $from, $value)
    {

        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');

        $mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');

        $userIds = DB::table('users')
            ->where('mode', $mode)
            ->pluck('id')
            ->toArray();

        if ($value == 'all') {
            $transaction = DB::table('subscriptions')
                ->select('id', 'user_id', 'stripe_plan', 'stripe_status', 'trial_ends_at', 'renews_at', 'ends_at', 'created_at')
                ->whereIn('user_id', $userIds)
                ->whereDate('created_at', '>=', $to)
                ->whereDate('created_at', '<=', $from)
                ->get();
        } else {
            $transaction = DB::table('subscriptions')
                ->select('id', 'user_id', 'stripe_plan', 'stripe_status', 'trial_ends_at', 'renews_at', 'ends_at', 'created_at')
                ->where('stripe_status', $value)
                ->whereIn('user_id', $userIds)
                ->whereDate('created_at', '>=', $to)
                ->whereDate('created_at', '<=', $from)
                ->get();
        }
        $array = [];
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
                'Authorization: Bearer ' . $STRIPE_SECRET . ''
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
        foreach ($transaction as $data) {

            if ($data->renews_at == null && $data->stripe_status == 'active') {
                $email = DB::table('users')->where('id', $data->user_id)->value('email');
                $name = DB::table('users')->where('id', $data->user_id)->value('name');
                $newUser = [
                    'name' => $name,
                    'email' => $email
                ];
                array_push($arrayRes1, $newUser);
            } elseif (!empty($data->renews_at) && $data->stripe_status == 'active') {
                $email = DB::table('users')->where('id', $data->user_id)->value('email');
                $name = DB::table('users')->where('id', $data->user_id)->value('name');
                $renewedUser = [
                    'name' => $name,
                    'email' => $email
                ];
                array_push($arrayRes2, $renewedUser);
            } elseif ($data->stripe_status == 'canceled') {
                $email = DB::table('users')->where('id', $data->user_id)->value('email');
                $name = DB::table('users')->where('id', $data->user_id)->value('name');
                $terminatedUser = [
                    'name' => $name,
                    'email' => $email
                ];
                array_push($arrayRes3, $terminatedUser);
            } else {
                $email = DB::table('users')->where('id', $data->user_id)->value('email');
                $name = DB::table('users')->where('id', $data->user_id)->value('name');
                $overdueUser = [
                    'name' => $name,
                    'email' => $email
                ];
                array_push($arrayRes4, $overdueUser);
            }

            $userEmail = DB::table('users')->where('id', $data->user_id)->value('email');
            $userZipcode = DB::table('users')->where('id', $data->user_id)->value('zipcode');

            if (!empty($data->renews_at)) {
                $time = strtotime($data->renews_at);
            } else {
                $time = strtotime($data->created_at);
            }

            for ($k = 0; $k < count($stripeplans); $k++) {
                if ($data->stripe_plan == $stripeplans[$k]['id']) {
                    $final = date("Y-m-d H:i:s", strtotime("+" . $stripeplans[$k]['interval_count'] . $stripeplans[$k]['interval'] . "", $time));
                    break;
                } else {
                    $final = date("Y-m-d H:i:s", strtotime("1 month"));
                }
            }

            $arry = [
                'id' => $data->id,
                'email' => $userEmail,
                'zipcode' => $userZipcode,
                'stripe_status' => $data->stripe_status,
                'start_at' => $data->created_at,
                'ends_at' => $data->renews_at,
                'cancle_at' => $data->ends_at,
                'trial_ends_at' => $data->trial_ends_at,
                'null' => $final,
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

        //dd($resultCount);

        return view('admin.reports.subscriptionsreport', compact('resultCount'));

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

        $zipCode = DB::table('zipcodes')
            ->select('id', 'zipcode', 'city', 'state', 'county_area', 'status')
            ->orderBy('status', 'DESC')
            ->simplePaginate(125);

        $activezipCode = DB::table('zipcodes')
            ->where('status', '0')
            ->select('id', 'zipcode', 'city', 'state', 'county_area', 'status')
            ->get();

        $arrayactive = [];
        foreach ($activezipCode as $activedata) {

            // $userEmail=DB::table('users')->where('id', $data->user_id)->value('email');
            $useractiveZipcode = DB::table('users')->where('zipcode', $activedata->zipcode)->get();

            $Activecount = count($useractiveZipcode);
            if ($activedata->status == 1) {
                $activezipcodeStatus = 'Active';
            } else {
                $activezipcodeStatus = 'Inactive';
            }

            $arryactive = [
                'zipcode' => $activedata->zipcode,
                'city' => $activedata->city,
                'state' => $activedata->state,
                'country' => $activedata->county_area,
                'status' => $activezipcodeStatus,
                'userCount' => $Activecount
            ];
            array_push($arrayactive, $arryactive);
        };

        $array = [];
        foreach ($zipCode as $data) {

            // $userEmail=DB::table('users')->where('id', $data->user_id)->value('email');
            $userZipcode = DB::table('users')->where('zipcode', $data->zipcode)->get();

            $count = count($userZipcode);
            if ($data->status == 1) {
                $zipcodeStatus = 'Active';
            } else {
                $zipcodeStatus = 'Inactive';
            }

            $arry = [
                'zipcode' => $data->zipcode,
                'city' => $data->city,
                'state' => $data->state,
                'country' => $data->county_area,
                'status' => $zipcodeStatus,
                'userCount' => $count
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
            'Inactivezipcode' => $arrayactive
        ];

        // dd($resultsData);
        // die();
        // return response()->json($array, 200);
        return view('admin.reports.zipcodreport', compact('resultsData'));

        // return $resultsData;
    }

    public function graph()
    {

        // $allSession = SessionTable::with('user')->selectRaw("users_id , count(id) activesession")->where("status",'1')->groupBy('users_id')->get();

        $allSession = SessionTable::with(['hotel', 'pdname' => function ($q) {
            // Query the name field in status table
            $q->select('id', 'personal_devicename', 'device_id', 'user_id');
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
        foreach ($allSession as $data) {

            if ($data->deviceSign == 'normaltv') {
                array_push($device1, $data->deviceSign);
            } elseif ($data->deviceSign == 'roku') {
                array_push($device2, $data->deviceSign);
            } elseif ($data->deviceSign == 'appletv') {
                array_push($device3, $data->deviceSign);
            } elseif ($data->deviceSign == 'firetv') {
                array_push($device4, $data->deviceSign);
            } elseif ($data->deviceSign == 'smarttv') {
                array_push($device5, $data->deviceSign);
            } elseif ($data->deviceSign == 'mobile') {
                array_push($device6, $data->deviceSign);
            } elseif ($data->deviceSign == 'tablet') {
                array_push($device7, $data->deviceSign);
            } elseif ($data->deviceSign == 'ipad') {
                array_push($device8, $data->deviceSign);
            } elseif ($data->deviceSign == 'iphone') {
                array_push($device9, $data->deviceSign);
            } else {
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

    public function zipcodeUsers($zipcode)
    {

        $UsersINZipcode = DB::table('users')
            ->where('zipcode', $zipcode)
            ->select('id', 'name', 'email')
            ->get();

        //return view('admin.reports.zipcodreport', compact('UsersINZipcode'));
        return $UsersINZipcode;
    }

    // export fun

    public function exportSubscription(Request $request, $type)
    {

        $filters = $request->only(['status']);
        $filename = 'subscription_report_' . now()->format('d-m-Y_H-i-s') . '.' . $type;
        return Excel::download(new SubscriptionReportExport($filters), $filename);
    }
}
