<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendText;
use Illuminate\Http\Request;

class FrontendTextController extends Controller
{
   
    public function index()
    {   
        $frontendjson = FrontendText::find(1)->frontendjson;
        
        return view('admin.frontendtext.edit', compact('frontendjson'));
        
    } 
    public function update(Request $request)
    {  
        # Get the user https://www.qcode.in/use-mysql-json-field-in-laravel/
        
        $data = $request->only('billingdetailspage','supportapitext','supportemail','supportphone','supportphone2');
        
        
        
        $user = FrontendText::find(1);
        $user->forceFill(['frontendjson' => $data]);


         $user->update();
        
        return redirect()->back()->with('message', 'data successfully updated');
    } 


   

    public function create(Request $request)
    {

        return view('admin.frontendtext.create');
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   $data = $request->only('billingdetailspage','supportapitext','supportemail','supportphone','supportphone2');
        
        $test['frontendjson'] = json_encode($data);
        $enter =FrontendText::insert($test);
        if($enter){
            echo "data successfully entered";
        }else{
            echo "something occurred";
        }
        // $model=new Zipcode;
        // $model->fill($request->all());

        // if ($model->save()) {
            
        //     return redirect('admin/zipcode')->with('info','Zipcode Added Successfully');
        
        //     } else {
        //         return redirect('admin/zipcode')->with('info','Some error occured');
        
        //     }
        
    } 

     

    
}
