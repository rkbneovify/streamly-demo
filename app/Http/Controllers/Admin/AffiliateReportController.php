<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;



/**
 * Description of BugReportingController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */

class AffiliateReportController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }
    public function index()
    {
        $affiliate = DB::table('affiliate_transaction')
            ->join('users', 'users.id', '=', 'affiliate_transaction.user_id')
            ->select('affiliate_transaction.*','users.name', 'users.email')
            ->orderBy('affiliate_transaction.id', 'DESC')
            ->get();
        //echo "<pre>";print_r($affiliate);
        return view('admin.affilate_report.index', ['affiliate' => $affiliate]);
    }  
    
}
