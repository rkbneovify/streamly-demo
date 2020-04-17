<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPack;
use App\Http\Requests\SubscriptionPack\Index;
use App\Http\Requests\SubscriptionPack\Show;
use App\Http\Requests\SubscriptionPack\Create;
use App\Http\Requests\SubscriptionPack\Store;
use App\Http\Requests\SubscriptionPack\Edit;
use App\Http\Requests\SubscriptionPack\Update;
use App\Http\Requests\SubscriptionPack\Destroy;


/**
 * Description of SubscriptionPackController
 *
 * 
 */

class SubscriptionPackController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return view('pages.subscription_pack.index', ['records' => SubscriptionPack::paginate(10)]);
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, SubscriptionPack $subscriptionpack)
    {
        return view('pages.subscription_pack.show', [
                'record' =>$subscriptionpack,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request)
    {

        return view('pages.subscription_pack.create', [
            'model' => new SubscriptionPack,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $model=new SubscriptionPack;
        $model->fill($request->all());

        if ($model->save()) {
            
            session()->flash('app_message', 'SubscriptionPack saved successfully');
            return redirect()->route('subscription_pack.index');
            } else {
                session()->flash('app_message', 'Something is wrong while saving SubscriptionPack');
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function edit(Edit $request, SubscriptionPack $subscriptionpack)
    {

        return view('pages.subscription_pack.edit', [
            'model' => $subscriptionpack,

            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request,SubscriptionPack $subscriptionpack)
    {
        $subscriptionpack->fill($request->all());

        if ($subscriptionpack->save()) {
            
            session()->flash('app_message', 'SubscriptionPack successfully updated');
            return redirect()->route('subscription_pack.index');
            } else {
                session()->flash('app_error', 'Something is wrong while updating SubscriptionPack');
            }
        return redirect()->back();
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Destroy $request, SubscriptionPack $subscriptionpack)
    {
        if ($subscriptionpack->delete()) {
                session()->flash('app_message', 'SubscriptionPack successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting SubscriptionPack');
            }

        return redirect()->back();
    }
}
