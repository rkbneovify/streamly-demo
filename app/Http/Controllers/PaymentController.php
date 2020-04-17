<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Http\Requests\Payment\Index;
use App\Http\Requests\Payment\Show;
use App\Http\Requests\Payment\Create;
use App\Http\Requests\Payment\Store;
use App\Http\Requests\Payment\Edit;
use App\Http\Requests\Payment\Update;
use App\Http\Requests\Payment\Destroy;


/**
 * Description of PaymentController
 *
 * 
 */

class PaymentController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return view('pages.payment.index', ['records' => Payment::paginate(10)]);
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, Payment $payment)
    {
        return view('pages.payment.show', [
                'record' =>$payment,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request)
    {
		$subscriptions = Subscription::all(['id']);
		$users = User::all(['id']);

        return view('pages.payment.create', [
            'model' => new Payment,
			"subscriptions" => $subscriptions,
			"users" => $users,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $model=new Payment;
        $model->fill($request->all());

        if ($model->save()) {
            
            session()->flash('app_message', 'Payment saved successfully');
            return redirect()->route('payment.index');
            } else {
                session()->flash('app_message', 'Something is wrong while saving Payment');
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Edit $request, Payment $payment)
    {
		$subscriptions = Subscription::all(['id']);
		$users = User::all(['id']);

        return view('pages.payment.edit', [
            'model' => $payment,
			"subscriptions" => $subscriptions,
			"users" => $users,

            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request,Payment $payment)
    {
        $payment->fill($request->all());

        if ($payment->save()) {
            
            session()->flash('app_message', 'Payment successfully updated');
            return redirect()->route('payment.index');
            } else {
                session()->flash('app_error', 'Something is wrong while updating Payment');
            }
        return redirect()->back();
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  Payment  $payment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Destroy $request, Payment $payment)
    {
        if ($payment->delete()) {
                session()->flash('app_message', 'Payment successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Payment');
            }

        return redirect()->back();
    }
}
