<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\User;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getResetToken(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        if ($request->wantsJson()) {
            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                $response = array('status' => false, 'message' => trans('passwords.user'));
                return response()->json($response, 404);
            }

            $token = $this->broker()->createToken($user);

            $FRONTEND_URL = env('FRONTEND_URL');

            $password_reset_link = $FRONTEND_URL . '/password-reset/' . $token . '?email=' . $request->email;

            $html = '<html>
                Hi, ' . $user->name . '<br><br>

                You have requested to reset password on ' . env('APP_NAME') . '.

                Here is your Password reset link. Click on below link to reset your password <br><br><a href="' . $password_reset_link . '" target=_blank >' . $password_reset_link . '</a>
            </html>';

            Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
                $message->to($request->email, $user->name)
                    ->subject(env('APP_NAME') . ': Password Reset');
            });

            $response = array('success' => true, 'message' => 'Password reset link sent on your email.');
            return response()->json($response, 200);
        }
    }

    public function webgetResetToken(Request $request)
    {
        $attributeNames = array(
            'forgotpassword-email' => 'Email',
        );

        $validatedData = $request->validate([

            'forgotpassword-email' => 'required|exists:users,email,is_active,1'
        ], ['forgotpassword-email.exists' => 'Email not found'], $attributeNames);


        $user = User::where('email', $request->input('forgotpassword-email'))->first();
        //print_r($user);exit;
        if (!$user) {
            return redirect('/')->with('message', trans('passwords.user'));
        }
        $token = $this->broker()->createToken($user);
        $FRONTEND_URL = env('FRONTEND_URL');

        $password_reset_link = $FRONTEND_URL . '/password/reset?token=' . $token;

        $html = '<html>
                Hi, ' . $user->name . '<br><br>

                You have requested to reset password on ' . env('APP_NAME') . '.

                Here is your Password reset link. Click on below link to reset your password <br><br><a href="' . $password_reset_link . '" target=_blank >' . $password_reset_link . '</a>
            </html>';

        Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
            $message->to($request->email, $user->name)
                ->subject(env('APP_NAME') . ': Password Reset');
        });

        return redirect('/')->with('message', 'Password reset link sent on your email.');
        // return response()->json($response, 200);

    }
}
