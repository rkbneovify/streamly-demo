<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function accountEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required_without:name',
            'name' => 'required_without:password',
            'old_password' => 'required_with:password,'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $user = JWTAuth::user();
            if ($user) {

                if ($request->has('name')) {
                    if (strlen(trim($request['name'])) <  1) {
                        return redirect()->back()->with('errormsgname', 'Please enter correct name');
                    }
                    $user->name = $request['name'];
                }
                if ($request->has('old_password')) {
                    $credentials = ['email' => $user->email, 'password' => $request->old_password];
                    $token = JWTAuth::attempt($credentials);
                    if (!$token) {
                        $response = array('success' => false, 'response_code' => 401, 'message' => 'Wrong old password');
                        return response()->json($response, 401);
                    }
                }

                if ($request->has('password')) {
                    $user->password = Hash::make($request->password);
                }
                if ($user->save()) {
                    $response = array('success' => true, 'response_code' => 200, 'message' => 'Account Details updated successfully.');
                    return response()->json($response, 200);
                }
            } else {
                $response = array('success' => false, 'response_code' => 404, 'message' => 'User not found');
                return response()->json($response, 404);
            }
        }
    }
}
