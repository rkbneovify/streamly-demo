<?php

namespace App\Http\Controllers\Api;

use App\HoaSubUserSubscription;
use App\HoaUser;
use App\Http\Controllers\Controller;
use App\SessionTable;
use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiHoaController extends Controller
{

    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $perPage = null;
            $page = null;
            if ($request->has('page')) {
                $perPage = $request->get('per_page', 10);
                $page = $request->get('page', 1);

            }
            $user = Auth::user();
            $query = HoaUser::query();
            $query->with('session');
            $query->withCount('session');
            $query->where('user_id', $user->id);
            $query->when($request->has('q'), function ($q) use ($request) {
                $q->where('passcode', 'like', '%' . $request->q . '%')
                    ->orWhere('name', 'like', '%' . $request->q . '%')
                    ->orWhere('phone', 'like', '%' . $request->q . '%')
                    ->orWhere('house_no', 'like', '%' . $request->q . '%');
            });
            if ($page == null) {


                $data = $query->get();
                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'Data fetched successfully !',
                    'data' => $data,

                ], 200);


            } else {
                $data = $query->paginate($perPage, ['*'], 'page', $page);
                return response()->json([
                    'success' => true,
                    'response_code' => 200,
                    'message' => 'Data fetched successfully !',
                    'data' => $data->items(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ], 200);
            }



        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }

    public function subUserDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());
        }

        try {
            $user = Auth::user();
            $hoaUser = HoaUser::where('passcode', $request->passcode)
                ->with('session')
                ->where('user_id', $user->id)
                ->first();
            if ($hoaUser == null) {
                return $this->notFoundResponse('Passcode not found !');

            }

            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Data fetched successfully !',
                'data' => $hoaUser
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }


    }

    public function passcodeRegenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());
        }

        $user = Auth::user();
        $hoaUser = HoaUser::where('passcode', $request->passcode)
            ->where('user_id', $user->id)
            ->first();
        if ($hoaUser == null) {
            return $this->notFoundResponse('Passcode not found !');

        }

        try {
            DB::beginTransaction();
            $newPasscode = HoaUser::generatePasscodeForHoa();
            $hoaUser->passcode = $newPasscode;
            $hoaUser->save();

            $this->removeActiveSessions($request->passcode, $user->id);

            DB::commit();
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Passcode regenerated successfully !',
                'new_passcode' => $newPasscode
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);
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
            return $this->validationErrorResponse(null, $validator->errors());

        }



        $user = Auth::user();
        $hoaUser = HoaUser::where('passcode', $request->passcode)
            ->where('user_id', $user->id)
            ->first();
        if ($hoaUser == null) {
            return $this->notFoundResponse('Passcode not found !');

        }

        try {
            DB::beginTransaction();
            $hoaUser->name = $request->name;
            $hoaUser->phone = $request->phone;
            $hoaUser->house_no = $request->house_no;
            $hoaUser->save();



            DB::commit();
            return $this->successResponse('User details updated successfully !');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);

        }


    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());

        }



        $user = Auth::user();
        $hoaUser = HoaUser::where('passcode', $request->passcode)
            ->where('user_id', $user->id)
            ->first();
        if ($hoaUser == null) {
            return $this->notFoundResponse('Passcode not found !');

        }
        $message = $hoaUser->status == 1 ? 'deactivated' : 'activated';
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
            return $this->successResponse('Account' . $message . 'suscessfully !');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);

        }


    }

    public function updateDeviceLimit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
            'device_limit' => 'required|integer|min:1'
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());

        }



        $user = Auth::user();
        $hoaUser = HoaUser::where('passcode', $request->passcode)
            ->where('user_id', $user->id)
            ->first();
        if ($hoaUser == null) {
            return $this->notFoundResponse('Passcode not found !');

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
            return $this->successResponse('Device limit updated successfully !');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);
        }


    }



    public function removeSessionById($id)
    {

        try {
            DB::beginTransaction();
            $session = SessionTable::where('id', $id)->first();

            if ($session == null) {
                return $this->notFoundResponse('Session not found !');

            }
            JWTAuth::setToken($session->access_token)->invalidate();
            $session->delete();
            DB::commit();
            return $this->successResponse('Session remove  successfully !');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);

        }


    }

    public function removeAllSessions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());

        }





        try {
            DB::beginTransaction();
            $user = Auth::user();

            $sessions = SessionTable::where('passcode', $request->passcode)
                ->where('users_id', $user->id)
                ->get();
            if ($sessions->isEmpty()) {
                return $this->notFoundResponse('Session not found !');

            }
            foreach ($sessions as $session) {
                JWTAuth::setToken($session->access_token)->invalidate();
                $session->delete();
            }



            DB::commit();
            return $this->successResponse('All session remove successfully !');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);

        }


    }

    public function subscriptionPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'passcode' => 'required|string|max:255',
            'duration' => 'required|in:1,3,6,12',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());

        }





        try {
            DB::beginTransaction();
            $user = Auth::user();

            $hoaUser = HoaUser::where('passcode', $request->passcode)
                ->where('user_id', $user->id)
                ->first();
            if ($hoaUser == null) {
                return $this->notFoundResponse('User not found !');

            }



            $hoaSubscription = new HoaSubUserSubscription();
            $hoaSubscription->user_id = Auth::id();
            $hoaSubscription->sub_user_id = $hoaUser->id;
            $hoaSubscription->passcode = $hoaUser->passcode;
            $hoaSubscription->plan_name = $request->duration . ' Month Plan';
            $hoaSubscription->plan_start_at = now();
            $hoaSubscription->plan_expires_at = HoaSubUserSubscription::calcExpiryDate($request->duration);
            $hoaSubscription->created_at = now();
            $hoaSubscription->updated_at = now();
            $hoaSubscription->save();


            DB::commit();
            return $this->successResponse('Plan Subscribed successfully !');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);

        }


    }


    // private function
    private function removeActiveSessions($passcode, $userId, $count = 0)
    {
        if ($count > 0) {

            $sessions = SessionTable::where('passcode', $passcode)
                ->where('users_id', $userId)
                ->limit($count)
                ->get();
            foreach ($sessions as $session) {
                JWTAuth::setToken($session->access_token)->invalidate();
                $session->delete();
            }


        } else {
            $sessions = SessionTable::where('passcode', $passcode)
                ->where('users_id', $userId)
                ->get();
            foreach ($sessions as $session) {
                JWTAuth::setToken($session->access_token)->invalidate();
                $session->delete();
            }
        }

    }
}
