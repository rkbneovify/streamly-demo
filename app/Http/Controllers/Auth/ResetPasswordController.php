<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\User;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(Request $request)
    {
        $this->validate($request, $this->valRules(), $this->validationErrorMessages());
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        if ($request->wantsJson()) {
            if ($response == Password::PASSWORD_RESET) {
                $response = array('success' => false,'message'=>trans('passwords.reset'));
                return response()->json($response, 200);
            } else {
                $response = array('success' => false,'message'=>'Password reset token is invalid');
                return response()->json($response, 400);                
            }
        } else {
            if ($response == Password::PASSWORD_RESET) {
                Auth::logout();
                return redirect('/')->with('message', 'Your password has been successfully reset. Please login to continue.');    
            }    else {
                return redirect('/')->with('message', 'Password reset token is invalid.');           
            }
        }
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        // return $response == Password::PASSWORD_RESET
        // ? $this->sendResetResponse($response)
        // : $this->sendResetFailedResponse($request, $response);
    }

    public function showResetForm(Request $request, $token = null)
    {

        if($request->has('email')) {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return redirect('/')->with('reseterror', 'Password reset token is invalid');
            }

            $status = $this->broker()->tokenExists($user, $token);

            if (!$status) {
                // Token is not valid
                return redirect('/')->with('reseterror', 'Password reset token is invalid');
            } else {
                // Token is valid, proceed with your logic
                // For example, show the password reset for

                return view('auth.passwords.reset')->with(
                    ['token' => $token, 'email' => $request->email]
                );
            }
        } else {
            return redirect('/')->with('reseterror', 'Password reset token is invalid');    
        }
    }

    protected function valRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
