<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\SessionTable;
use App\HotelRoomTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Exception;

class UserController extends Controller
{
    public function accountEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required_without:name|string|min:6',
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
                        $response = array('success' => false, 'response_code' => 400, 'message' => 'Please enter correct name');
                        return response()->json($response, 401);
                    }
                    $user->name = $request['name'];
                }
                if ($request->has('old_password')) {
                    $credentials = ['email' => $user->email, 'password' => $request->old_password];
                    $token = JWTAuth::attempt($credentials);
                    if (!$token) {
                        $response = array('success' => false, 'response_code' => 400, 'message' => 'Wrong old password');
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

    public function multiSessionDestroy(Request $request)
    {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'required|distinct',
        ]);

        $session_ids = $request->id;
        $ids = [];
        foreach ($session_ids as $id) {
            $ids[] = UnHashUserID($id);
        }
        // dd($ids);
        $sessions = SessionTable::select('id', 'connected_devices', 'users_id', 'access_token')
            ->whereIn('id', $ids)
            ->get();

        $errors = $this->destroyJWTtoken($sessions->toArray());
        $userId = $sessions->isEmpty() ? null : $sessions->first()->users_id;

        $errorString = "";
        if (!$errors == 'no error') {
            $errorCount = count($errors);
            $errorString = ($errorCount > 0) ? "$errorCount error(s) were found" : '';
        }

        $sessionRemove = SessionTable::whereIn('id', $ids)
            ->update(['status' => 0, 'modified_on' => Carbon::now()]);

        SessionTable::where('users_id', $userId)
            ->where('status', 1)
            ->where('connected_devices', '!=', 1)
            ->update(['connected_devices' => DB::raw('connected_devices - 1')]);

        $hotelRoomCount = HotelRoomTable::whereIn('session_id', $ids)->count();

        if ($hotelRoomCount > 0) {
            HotelRoomTable::whereIn('session_id', $ids)->update(['session_id' => null]);
        }

        if ($sessionRemove) {
            return response()->json([
                'message' => "User Session(s) Deleted Successfully. $errorString",
                'jwterror' => $errors,
            ], 200);
        } else {
            return response()->json(['message' => "User Session Not found"], 400);
        }
    }


    private function destroyJWTtoken($jwtValues)
    {
        $errors = [];

        foreach ($jwtValues as $value) {
            try {
                config(['jwt.blacklist_enabled' => true]);
                JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($value['access_token']), $forceForever = false);
            } catch (TokenInvalidException $e) {
                $value['error'] = $e->getMessage();
                $errors[] = $value;
            } catch (TokenExpiredException $e) {
                $value['error'] = $e->getMessage();
                $errors[] = $value;
            } catch (Exception $e) {
                $value['error'] = $e->getMessage();
                $errors[] = $value;
            }
        }

        return (!empty($errors)) ? $errors : 'no error';
    }

    public function forceChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:6',
            'password_confirmation' => 'required',
            'old_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $user = JWTAuth::user();

            if ($user) {

                if ($request->has('old_password')) {
                    $credentials = ['email' => $user->email, 'password' => $request->old_password];
                    $token = JWTAuth::attempt($credentials);
                    if (!$token) {
                        $response = array('success' => false, 'response_code' => 400, 'message' => 'Wrong old password');
                        return response()->json($response, 401);
                    }
                }

                if ($request->password == $request->old_password) {
                    return response()->json(['error' => 'Your Old Password Cannot Be New Password'], 400);
                }

                if ($request->has('password')) {
                    $user->password = Hash::make($request->password);
                    $user->forced_change = 0;
                }
                if ($user->save()) {
                    $response = array('success' => true, 'response_code' => 200, 'message' => 'Password updated successfully.');
                    return response()->json($response, 200);
                }
            } else {
                $response = array('success' => false, 'response_code' => 404, 'message' => 'User not found');
                return response()->json($response, 404);
            }
        }
    }
}
