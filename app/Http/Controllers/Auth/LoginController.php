<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    protected function redirectTo()
    {  
        $u=Auth::user();
        $userRole= $u->roles->pluck('name');
         
        if( @$userRole[0]== 'admin' || @$userRole[0]== 'superadmin' ) {
            return '/admin';
        } else{
            return '/';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
{
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => '1']))
    {
        // Updated this line
        return $this->sendLoginResponse($request);

        // OR this one
        // return $this->authenticated($request, auth()->user());
    }
    else
    {
        return $this->sendFailedLoginResponse($request, 'auth.failed_status');
    }
}


}
