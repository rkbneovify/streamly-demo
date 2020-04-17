<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPack;
use App\Models\User;
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
    public function index(Index $request)
    {
        return view('pages.subscriptions.index', ['records' => Subscription::paginate(10)]);
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, Subscription $subscription)
    {
        return view('pages.subscriptions.show', [
                'record' =>$subscription,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request)
    {
		$subscription_pack = SubscriptionPack::all(['id']);
		$users = User::all(['id']);

        return view('pages.subscriptions.create', [
            'model' => new Subscription,
			"subscription_pack" => $subscription_pack,
			"users" => $users,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model=new Subscription;
        $packid=$request->subscriptionpack_id;
        if($request->has('start_date') && !empty($request->input('start_date'))) {
            $startdate=$request->start_date;
        } else {
            $startdate=Carbon::now();
        } 

         // a helper function addInterval(Carbon $date)
         
        $model->subscriptionpack_id = $packid;
        $model->users_id = 1;
        $model->start_date = $startdate;
       // $model->expiry_date = Carbon::now()->addMonths(1);;
       if($packid==1){
         
            $model->expiry_date = $startdate->addMonth(1);
       }else if($packid==2){
            $model->expiry_date = $startdate->addMonths(6);
       }else{
        $model->expiry_date = Carbon::now();
       }
            
      echo $model->expiry_date;
        
     
        $model->status = 1;
        $model->created_on = Carbon::now();
        $model->modified_on = Carbon::now();
        
        if ($model->save()) {
            
            return response()->json([
                'success' => true,
                'msg'=>'Subscription added successfully',
                'data' => $model
            ]);
            // session()->flash('app_message', 'Subscription saved successfully');
            // return redirect()->route('subscriptions.index');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while saving Subscription'
                ], 500);
                //session()->flash('app_message', 'Something is wrong while saving Subscription');
            }
        //return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Edit $request, Subscription $subscription)
    {
		$subscription_pack = SubscriptionPack::all(['id']);
		$users = User::all(['id']);

        return view('pages.subscriptions.edit', [
            'model' => $subscription,
			"subscription_pack" => $subscription_pack,
			"users" => $users,

            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {    $subscription = Subscription::find($id);
        // $task = $this->user->tasks()->find($id);
         if(!$subscription){
             return response()->json([
                 'success' => false,
                 'message' => 'Sorry, subscription with id ' . $id . ' cannot be found.'
             ], 400);
         }
        

         $packid=$request->subscriptionpack_id;
         if($request->has('start_date') && !empty($request->input('start_date'))) {
             $startdate=$request->start_date;
         } else {
             $startdate=Carbon::now();
         } 
 
          // a helper function addInterval(Carbon $date)
          
         $subscription->subscriptionpack_id = $packid;
         
         $subscription->start_date = $startdate;
        // $model->expiry_date = Carbon::now()->addMonths(1);;
        if($packid==1){
          
             $subscription->expiry_date = $startdate->addMonth(1);
        }else if($packid==2){
             $subscription->expiry_date = $startdate->addMonths(6);
        }else{
         $subscription->expiry_date = Carbon::now();
        }
             
      // echo $subscription->expiry_date;
         
         $subscription->modified_on = Carbon::now();
         if($subscription->save())
         {return response()->json([
          'success' => true,
          'msg'=>'Subscription successfully updated',
          'data' => $subscription
      ]);} else {
                return response()->json([
                      'success' => false,
                      'message' => 'Something is wrong while updating Subscription',
                      'error'=>$e
                  ], 500);
      }
       

        // if ($subscription->save()) {
            
        //     session()->flash('app_message', 'Subscription successfully updated');
        //     return redirect()->route('subscriptions.index');
        //     } else {
        //         session()->flash('app_error', 'Something is wrong while updating Subscription');
        //     }
       // return redirect()->back();
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Destroy $request, Subscription $subscription)
    {
        if ($subscription->delete()) {
                session()->flash('app_message', 'Subscription successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Subscription');
            }

        return redirect()->back();
    }
}
