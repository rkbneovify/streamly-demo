<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPack;


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
    public function index()
    {  $subscriptionpack = SubscriptionPack::all();
       // return view('pages.subscription_pack.index', ['records' => SubscriptionPack::paginate(10)]);
       return view('admin.subscriptionpack.index', compact('subscriptionpack'));
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  $subscriptionpack = SubscriptionPack::find($id); 
        return view('admin.subscriptionpack.show', compact('subscriptionpack'));
        

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        return view('admin.subscriptionpack.create', compact('subscriptionpack'));
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model=new SubscriptionPack;
        $model->fill($request->all());

        if ($model->save()) {
            
            return redirect('admin/subscriptionpack')->with('info','SubscriptionPack Added Successfully');
        
            } else {
                return redirect('admin/subscriptionpack')->with('info','Some error occured');
        
            }
        
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscriptionpack = SubscriptionPack::find($id);
        return view('admin.subscriptionpack.edit', compact('subscriptionpack'));
        
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   $this->validate($request, [
        'name' => 'required|max:255',
        'duration' => 'required',
        'rate' => 'required',
        

    ]);
    $input = $request->only('name', 'duration', 'rate');
    $model = SubscriptionPack::find($id);
    if(!$model){
        return redirect('admin/subscriptionpack')->with('info','subscription with id ' . $id . ' cannot be found.'); 
    }
    else{$model->update($input); //update the subscription info

        return redirect('admin/subscriptionpack')->with('info','Subscription updated Successfully');
   }

        
        
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  SubscriptionPack  $subscriptionpack
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    
    public function destroy($id)
    {
        $model= SubscriptionPack::whereId($id)->delete(); 
        
         if($model){
            return redirect('admin/subscriptionpack')->with('info','SubscriptionPack Deleted Successfully');
                      
        
        }else{
            return redirect('admin/subscriptionpack')->with('info','SubscriptionPack Not found');
            
        }

        
    }
}
