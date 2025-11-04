<?php

namespace App\Http\Controllers\Api;

use App\HoaUser;
use App\Http\Controllers\Controller;
use App\SessionTable;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiHoaController extends Controller
{

    public function passcodeRegenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'response_code' => 422,
                'message' => 'Validation error !',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $hoaUser = HoaUser::where('passcode', $request->passcode)
            ->where('user_id', $user->id)
            ->first();
        if ($hoaUser == null) {
            return response()->json([
                'success' => false,
                'response_code' => 404,
                'message' => 'Passcode not found !'
            ], 404);
        }

        try {
            DB::beginTransaction();
            $hoaUser->passcode = HoaUser::generatePasscodeForHoa();
            $hoaUser->save();

            $this->removeActiveSessions($request->passcode, $user->id);

            DB::commit();
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Passcode regenerated successfully !'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error regenerating passcode: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'response_code' => 500,
                'message' => "Something went wrong ! Please try again later."
            ], 500);
        }


    }

    public function updateSubUserDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|digits:10',
            'house_no' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'response_code' => 422,
                'message' => 'Validation error !',
                'errors' => $validator->errors()
            ], 422);
        }



        $user = Auth::user();
        $hoaUser = HoaUser::where('passcode', $request->passcode)
            ->where('user_id', $user->id)
            ->first();
        if ($hoaUser == null) {
            return response()->json([
                'success' => false,
                'response_code' => 404,
                'message' => 'Passcode not found !'
            ], 404);
        }

        try {
            DB::beginTransaction();
            $hoaUser->name = $request->name;
            $hoaUser->phone = $request->phone;
            $hoaUser->house_no = $request->house_no;
            $hoaUser->save();



            DB::commit();
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'User details updated successfully !'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error regenerating passcode: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'response_code' => 500,
                'message' => "Something went wrong ! Please try again later."
            ], 500);
        }


    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'response_code' => 422,
                'message' => 'Validation error !',
                'errors' => $validator->errors()
            ], 422);
        }



        $user = Auth::user();
        $hoaUser = HoaUser::where('passcode', $request->passcode)
            ->where('user_id', $user->id)
            ->first();
        if ($hoaUser == null) {
            return response()->json([
                'success' => false,
                'response_code' => 404,
                'message' => 'Passcode not found !'
            ], 404);
        }

        try {
            DB::beginTransaction();
            if ($hoaUser->status == 1) {
                $hoaUser->status = 0;
            } else {
                $hoaUser->status = 1;
            }
            $hoaUser->save();
            $this->removeActiveSessions($request->passcode, $user->id);


            DB::commit();
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'User status changed successfully !'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error regenerating passcode: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'response_code' => 500,
                'message' => "Something went wrong ! Please try again later."
            ], 500);
        }


    }

    public function updateDeviceLimit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
            'device_limit' => 'required|integer|min:1'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'response_code' => 422,
                'message' => 'Validation error !',
                'errors' => $validator->errors()
            ], 422);
        }



        $user = Auth::user();
        $hoaUser = HoaUser::where('passcode', $request->passcode)
            ->where('user_id', $user->id)
            ->first();
        if ($hoaUser == null) {
            return response()->json([
                'success' => false,
                'response_code' => 404,
                'message' => 'Passcode not found !'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $sessionRemoveCount = 0;
            $sessionRemoveCount = $hoaUser->device_limit - (int) $request->device_limit;
            $hoaUser->device_limit = $request->device_limit;
            $hoaUser->save();
            if ($sessionRemoveCount > 0) {
                $this->removeActiveSessions($request->passcode, $user->id, $sessionRemoveCount);

            }


            DB::commit();
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Device limit updated successfully !'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'response_code' => 500,
                'message' => "Something went wrong ! Please try again later."
            ], 500);
        }


    }

    public function removeActiveSessions($passcode, $userId, $count = 0)
    {
        if ($count > 0) {

            $sessions = SessionTable::where('passcode', $passcode)
                ->where('users_id', $userId)
                ->where('status', 1)
                ->limit($count)
                ->get();
            foreach ($sessions as $session) {
                JWTAuth::setToken($session->access_token)->invalidate();
                $session->delete();
            }


        } else {
            $sessions = SessionTable::where('passcode', $passcode)
                ->where('users_id', $userId)
                ->where('status', 1)
                ->get();
            foreach ($sessions as $session) {
                JWTAuth::setToken($session->access_token)->invalidate();
                $session->delete();
            }
        }

    }

    public function removeSessionById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'response_code' => 422,
                'message' => 'Validation error !',
                'errors' => $validator->errors()
            ], 422);
        }





        try {
            DB::beginTransaction();
            $session = SessionTable::where('id', $request->session_id)->first();

            if ($session == null) {
                return response()->json([
                    'success' => false,
                    'response_code' => 404,
                    'message' => 'Session not found !'
                ], 404);
            }
            JWTAuth::setToken($session->access_token)->invalidate();
            $session->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Session remove  successfully !'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'response_code' => 500,
                'message' => "Something went wrong ! Please try again later."
            ], 500);
        }


    }

    public function removeAllSessions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'response_code' => 422,
                'message' => 'Validation error !',
                'errors' => $validator->errors()
            ], 422);
        }





        try {
            DB::beginTransaction();
            $user = Auth::user();

            $sessions = SessionTable::where('passcode', $request->passcode)
                ->where('users_id', $user)
                ->where('status', 1)

                ->get();
            foreach ($sessions as $session) {
                JWTAuth::setToken($session->access_token)->invalidate();
                $session->delete();
            }

            if ($session == null) {
                return response()->json([
                    'success' => false,
                    'response_code' => 404,
                    'message' => 'Session not found !'
                ], 404);
            }
            JWTAuth::setToken($session->access_token)->invalidate();
            $session->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'All session remove  successfully !'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'response_code' => 500,
                'message' => "Something went wrong ! Please try again later."
            ], 500);
        }


    }
}
