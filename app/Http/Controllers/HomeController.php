<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\User;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Crypt;
use Mail;
use Exception;

class HomeController extends Controller
{

    private $user;
    private $DEFAULT_ROLE_ID = 3;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if( Auth::check()){
            $login = true;
            $user=Auth::user();
            if (!$user->subscribed('main')) {
                return redirect('/payment');
            } else {   return redirect('/livetv');}
        } else {
            $login = false;
        }

        return view('frontend.home', compact('login'));
    }
    public function verified()
    {
        

        return view('frontend.verified');
    }

    public function createUser(Request $request){

        $attributeNames = array(
           'user-email' => 'Email',
           'user-password' => 'Password',
           'name' => 'Name',
           'zip_code' => 'Zip Code',
        );

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'user-email' => 'required|string|email|max:255|unique:users,email',
            'user-password' => 'required|string|min:8',
            'zip_code' => 'required|regex:/^[0-9]{3,7}$/',
            'zip_code'=>'required|exists:zipcodes,zipcode,status,1'
        ], ['zip_code.exists'=>'Service not available at this zipcode or your zipcode is not valid'], $attributeNames);

        /*if ( $validatedData->fails() ) {
            return redirect()->back()->withInput()->with( 'errors', $validatedData->errors() );
        }*/

        $User = User::create([
            'name' => $request['name'],
            'email' => $request['user-email'],
            'password' => Hash::make($request['user-password']),
            'zipcode' => $request['zip_code'],
            'email_verified_at'=>null
        ]);

        $role = RoleUser::create([
            'user_id' => $User->id,
            'role_id' => $this->DEFAULT_ROLE_ID,
        ]);


        $verification_code=Crypt::encrypt($User->email);

        $FRONTEND_URL=env('FRONTEND_URL');

        $account_verification_link=$FRONTEND_URL.'/api/auth/verify?token='.$verification_code;
        $html='<html>
            Hi, '.$User->name.'<br><br>

            Thank you for registering on '.env('APP_NAME').'.

            Here is your account verification link. Click on below link to verify you account. <br><br><a href="'.$account_verification_link.'" target=_blank >'.$account_verification_link.'</a>
        </html>';
      try{
        Mail::send('emails.general',["html"=>$html] , function($message) use ($request,$User){
            $message->to($request['user-email'], $User->name)
            ->subject(env('APP_NAME').': Account Verification');
            // return redirect()->route('home')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
        });} catch (Exception $ex) {
             //$ex->getMessage();
             return redirect()->back()->with('message', "We've got errors!".json_encode($ex->getMessage(), true));
            // return "We've got errors!".json_encode($ex->getMessage(), true);
        }

        return redirect('/')->with('message', 'Your account has been successfully registered!,check email and click on verification link to activate your account.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function rediretToGoogleLogin(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login');
        }

        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser                    = new User;
            $newUser->provider_name     = 'google';
            $newUser->provider_id       = $user->getId();
            $newUser->name              = $user->getName();
            $newUser->email             = $user->getEmail();
            $newUser->email_verified_at = now();
            $newUser->avatar            = $user->getAvatar();
            $newUser->save();

            auth()->login($newUser, true);
        }

        return redirect('/');
    }

}
