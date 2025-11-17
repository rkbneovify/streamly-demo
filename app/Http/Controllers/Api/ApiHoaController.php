<?php

namespace App\Http\Controllers\Api;

use App\HoaSubUserSubscription;
use App\HoaUser;
use App\Http\Controllers\Controller;
use App\Mail\HoaSubUserWelcome;
use App\Mail\VerifyHoaSubUserEmail;
use App\SessionTable;
use App\Traits\ApiResponseTrait;
use App\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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

    public function addHouse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|array',
            'email' => 'required|email|max:225',
            'plan_in_month' => 'required|in:1,3,6,12',
            'amount' => 'nullable|numeric',
            'device_limit' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());
        }

        DB::beginTransaction();
        try {
            if (!HoaUser::isHouseAvailable()) {
                return $this->maxlimitExceed("You have reached the maximum number of houses.");
            } elseif ($request->device_limit > auth()->user()->device_per_house) {
                return $this->validationErrorResponse("Per house device limit exceed !");
            }

            $hoaUser = HoaUser::where('email', $request->email)->first();
            if ($hoaUser == null) {
                $hoaUser = new HoaUser();
                $hoaUser->user_id = auth()->user()->id;
                $hoaUser->email = $request->email;
                $hoaUser->device_limit = $request->device_limit;
                $hoaUser->save();
            } else {
                $hoaUser->device_limit = $request->device_limit;
                $hoaUser->email_verified_at = null;
                $hoaUser->save();
            }

            Mail::to($request->email)->send(new VerifyHoaSubUserEmail($hoaUser, $request->plan_in_month, $request->amount));
            DB::commit();
            return $this->successResponse("House added successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->exceptionResponse($th);
        }



    }

    public function deleteHouse($id)
    {

        $hoaUser = HoaUser::where('id', $id)->first();

        if ($hoaUser == null) {
            return $this->notFoundResponse("House not found !");
        }
        DB::beginTransaction();
        try {
            HoaSubUserSubscription::where('sub_user_id', $hoaUser->id)->delete();
            $hoaUser->delete();
            DB::commit();
            return $this->successResponse("House deleted successfully !");
        } catch (\Throwable $th) {
            //throw $th;
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
            return $this->successResponse('Account ' . $message . ' suscessfully !');

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

    public function accountActive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'house_no' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'plan_in_month' => 'required|in:1,3,6,12',
            'amount' => 'nullable|numeric',
            'sub_user_id' => 'required|integer',
            'zipcode' => 'required|integer',




        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());

        }

        DB::beginTransaction();
        try {
            $hoaUser = HoaUser::where('id', $request->sub_user_id)
                ->first();
            if ($hoaUser == null) {
                return $this->notFoundResponse("Data not found !");
            }
            if (!HoaUser::isEmailVerified($hoaUser->id)) {
                return $this->errorResponse("Please verify your email", 403);
            }
            // if ($hoaUser->status == 1) {
            //     return $this->errorResponse("Account already active.", 403);
            // }
            $user = User::where('id', $hoaUser->user_id)->first();
            if ($user == null) {
                return $this->notFoundResponse("Data not found !");
            }

            $hoaUser->name = $request->name;
            $hoaUser->house_no = $request->house_no;
            $hoaUser->phone = $request->phone;
            $hoaUser->passcode = HoaUser::generatePasscodeForHoa();
            $hoaUser->status = 1;
            $hoaUser->zipcode = $request->zipcode;
            $hoaUser->save();
            $subs = HoaSubUserSubscription::where('user_id', $user->id)->where('sub_user_id', $hoaUser->id)->first();
            if ($subs == null) {
                $subs = new HoaSubUserSubscription();
                $subs->user_id = $hoaUser->user_id;
                $subs->sub_user_id = $hoaUser->id;
                $subs->passcode = $hoaUser->passcode;
                $subs->amount = $request->amount;
                $subs->plan_name = $request->plan_in_month . ' Month Plan';
                $subs->plan_start_at = now();
                $subs->plan_expires_at = HoaSubUserSubscription::calcExpiryDate($request->plan_in_month);
                $subs->created_at = now();
                $subs->updated_at = now();
                $subs->save();
            }

            DB::commit();
            $hoaUser = HoaUser::where('id', $request->sub_user_id)
                ->with('subscriptionLatest')
                ->first();

            Mail::to($hoaUser->email)
                ->send(new HoaSubUserWelcome($hoaUser, $user->email));


            return $this->successResponse("Account active successfully ! Please check your email for details.");

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return $this->exceptionResponse($th);
        }

    }
    public function emailVerify($id, $hash, Request $request)
    {

        $expireTime = Carbon::createFromTimestamp($request->expires);
        $minutesRemaining = now()->diffInMinutes($expireTime, false);



        if ($minutesRemaining <= 0) {
            toast("Verification link expired. Please contact your admin.", "error")
                ->autoClose(3000)
                ->timerProgressBar();
            return redirect('/');

        }
        DB::beginTransaction();
        try {
            $hoaUser = HoaUser::where('id', $id)->first();
            if ($hoaUser != null) {
                if (!hash_equals(sha1($hoaUser->email), $hash)) {
                    toast("Invalid verification link. Please contact your admin.", "error")
                        ->autoClose(3000)
                        ->timerProgressBar();
                    return redirect('/');
                }
            }

            HoaUser::markedEmailVerified($id);
            // send link to frontend
            $accountRegisterUrl = URL::temporarySignedRoute(
                'sub.user.account.register',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $id,
                    'hash' => sha1($hoaUser->email),
                    'plan_in_month' => $request->plan_in_month,
                    'amount' => $request->amount,
                ]
            );
            DB::commit();
            return redirect($accountRegisterUrl);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return response()->json($th->getMessage());

        }

    }


    public function accountRegister($id, $hash, Request $request)
    {
        $hoaUser = HoaUser::where('id', $id)->first();
        if ($hoaUser != null) {
            if (!hash_equals(sha1($hoaUser->email), $hash)) {
                toast("Invalid verification link. Please contact your admin.", "error")
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect('/');
            }
        }

        if (!HoaUser::isEmailVerified($id)) {
            toast("Email not verified.", "error")
                ->autoClose(3000)
                ->timerProgressBar();

            return redirect('/');
        }
        return response()->json([
            "id" => $id,
            "hash" => $hash,
            'plan_in_month' => $request->plan_in_month,
            'amount' => $request->amount,
            'is_email_verified' => HoaUser::isEmailVerified($id)
        ]);
    }
}
