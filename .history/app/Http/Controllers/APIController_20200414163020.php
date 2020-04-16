<?php

namespace App\Http\Controllers;
use JWTAuth;
use App\User;
use App\SessionTable;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;
use Jenssegers\Agent\Agent;
class APIController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }


        



        // to detect browser
        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();
return response()->json([
    'success' => true,
    'token' => $token,
    'browser_name'=>$browser.''.$device.''.$platform,
]);
}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    /**
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationFormRequest $request)
    { $date = date('Y-m-d H:i:s');
        $user = new User();
        $user->first_name = $request->first_name;
        $user->role_id = 1;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->address = 'adress 1';
        $user->address2 = 'adress 2';
        $user->postal_code = '111111';
        $user->state = 'state';
        $user->country = 'country';
        $user->active = 'x';
        $user->created_on = $date;
        $user->modified_on = $date;
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success'   =>  true,
            'data'      =>  $user
        ], 200);
    }
}
