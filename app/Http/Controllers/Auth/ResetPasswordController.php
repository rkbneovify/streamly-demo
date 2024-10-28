<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        if ($request->wantsJson()) {
            if ($response == Password::PASSWORD_RESET) {
                $response = array('success' => false, 'message' => trans('passwords.reset'));
                return response()->json($response, 200);
            } else {
                $response = array('success' => false, 'message' => 'Password reset token is invalid');
                return response()->json($response, 400);
            }
        } else {
            if ($response == Password::PASSWORD_RESET) {
                Auth::logout();
                return redirect('/')->with('reset-success', 'Your password has been successfully reset. Please login to continue.');
            } else {
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

        if ($request->has('email')) {
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

    public function validateToken(Request $request)
    {
        // Define validation rules based on the existing validation logic
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $token = $request->input('token');
        $email = $request->input('email');

        // Retrieve the record from the password_resets table
        $passwordReset = DB::table('password_resets')->where('email', $email)->first();

        // Check if the record exists and validate the token hash
        if (!$passwordReset || !Hash::check($token, $passwordReset->token)) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired token.'], 400);
        }

        // Check if the token is expired (assuming a 60-minute lifespan)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['success' => false, 'message' => 'Token has expired.'], 400);
        }

        // Token is valid
        return response()->json(['success' => true, 'message' => 'Token is valid.'], 200);

        /*
        // Retrieve the token and email entry from the password_resets table
        $passwordReset = DB::table('password_resets')->where('email', $email)
            ->where('token', $token)
            ->first();

        // Check token validity and expiration
        if (!$passwordReset || Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            $response = [
                'success' => false,
                'message' => 'Password reset token is invalid or has expired.'
            ];
            return response()->json($response, 400);
        }

        $response = [
            'success' => true,
            'message' => 'Token is valid.'
        ];
        return response()->json($response, 200);
        */
    }
}
