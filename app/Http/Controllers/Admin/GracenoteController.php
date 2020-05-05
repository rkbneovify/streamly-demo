<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Gracenote;

use App\Models\Role;


/**
 * Description of GracenoteController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */

class GracenoteController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        // $users = Gracenote::with('user')->paginate(20);//Customers::with('country')->paginate(20);
        // return view('admin.subscriptions.index', compact('users'));
        $record = Gracenote::all();
        return view('admin.gracenote.index', compact('record'));
    }    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Gracenote  $gracenote
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { $model = Gracenote::find($id); 
        return view('admin.gracenote.show', compact('model'));
        

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        

        return view('admin.gracenote.create');  
        
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model=new Gracenote;
        $model->fill($request->all());

        if ($model->save()) {
            
            session()->flash('app_message', 'Gracenote saved successfully');
            return redirect()->route('gracenote.index');
            } else {
                session()->flash('app_message', 'Something is wrong while saving Gracenote');
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Gracenote  $gracenote
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Gracenote::find($id);

        $roles = Role::get(); //get all roles
        
        
            return view('admin.gracenote.edit',compact('model','roles','userRoles'));
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Gracenote  $gracenote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Gracenote $gracenote)
    {
        $gracenote->fill($request->all());

        if ($gracenote->save()) {
            
            session()->flash('app_message', 'Gracenote successfully updated');
            return redirect()->route('gracenote.index');
            } else {
                session()->flash('app_error', 'Something is wrong while updating Gracenote');
            }
        return redirect()->back();
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'confirmed',
            'roles' => 'required',
    
        ]);
        $input = $request->only('name', 'email', 'password','dob','gender','country','address','zipcode','contact');
        if ($request->has('toggleswitch')) {
           // $archived="helllo block user on";
            $input['is_active'] = 0;
           // $user->is_active=0;
        } else{
           // $archived="bye block off";
            //$user->is_active=1;
            $input['is_active'] = 1;
        }
    
        
     // dd($archived);
        
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']); //update the password
        }else{
            $input = Arr::except($input,array('password')); //remove password from the input array
        }
       //$allpost= $request->post();
       // dd($input); exit; get all the post data
        $user = User::find($id);
        $user->update($input); //update the user info

        //delete all roles currently linked to this user
        DB::table('role_user')->where('user_id',$id)->delete();

        //attach the new roles to the user
        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }
        return redirect('admin/users')->with('info','User Updated Successfully');
        
       

    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Request  $request
     * @param  Gracenote  $gracenote
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Gracenote $gracenote)
    {
        if ($gracenote->delete()) {
                session()->flash('app_message', 'Gracenote successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Gracenote');
            }

        return redirect()->back();
    }

    public function getChannel()
    {
        $users = Gracenote::all();
       
       $response = array('status' => true,'message'=>"users retrieved.",'data'=>$users);
            return response()->json($response, 200);
    }
}
