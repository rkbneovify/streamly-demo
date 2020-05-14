<?php

namespace App\Http\Controllers\Admin;

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

class UsersController extends Controller
{     
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        $users = User::with('roles')->paginate(20);//Customers::with('country')->paginate(20);
        
          
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        

        $roles = Role::all()->pluck('name', 'id');

        return view('admin.users.create', compact('roles'));  
    }

    public function store(Request $request)
    {
        $email_verified_at=Carbon::now();

        $user = User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'contact'=>$request->contact,
            'gender'=>$request->gender,
            'dob'=>$request->dob,
            'country'=>$request->country,
            'address'=>$request->address,
            'zipcode'=>$request->zipcode,
            'email_verified_at'=>$email_verified_at
        ]);
        $user->roles()->sync($request->input('roles', []));

     return redirect('admin/users')->with('info','User Added Successfully');
    }
    public function update(Request $request, $id)
    {
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
        
        
    }

     public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::get(); //get all roles
        $userRoles = $user->roles->pluck('id')->toArray();
        //dd($roles);
        return view('admin.users.edit',compact('user','roles','userRoles'));
    }

    

    public function show($id)
    {

        $user = User::find($id); //Find the requested role

        //Get the permissions linked to the role
        // $permissions =
        //     Permission::join("permission_role","permission_role.permission_id","=","permissions.id")
        //     ->where("permission_role.role_id",$id)
        //     ->get();

        //return the view with the role info and its permissions
       // return view('roles.show',compact('role','permissions'));
        return view('admin.users.show', compact('user'));
    }

    public function destroy($id)
    {
        $user= User::whereId($id)->delete(); 
         if($user){
            return redirect('admin/users')->with('info','User Deleted Successfully');
                      
        
        }else{
            return redirect('admin/users')->with('info','User Not found');
            
        }

        
    }

    public function massDestroy(Request $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }
}
