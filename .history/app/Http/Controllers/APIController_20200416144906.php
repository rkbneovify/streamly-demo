<?php

namespace App\Http\Controllers;
use JWTAuth;
use App\User;
use App\SessionTable;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
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
        $user = JWTAuth::user();//all the user info
      $uid=  $user['id'];
        
   checkLoginLimit($uid,$token);


   
}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request,$id)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {  //$user = JWTAuth::user();//all the user info
            //$uid=  $user['id'];
            
            if($id==''){
                return response()->json([
                    'success' => false,
                    'message' => 'the user cannot be logged out because reqired id is not provided',
                   
                ]);
            } else {

                JWTAuth::invalidate($request->token);
                LogoutandDecrementDevice($id);
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
                'error'=>$exception,
                
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