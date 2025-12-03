<?php

namespace App\Http\Controllers\Api;

use App\HoaSubUserSubscription;
use App\HoaUser;
use App\Http\Controllers\Controller;
use App\Mail\HoaSubUserForgotPasscode;
use App\Mail\HoaSubUserPasscodeRegenerate;
use App\Mail\HoaSubUserWelcome;
use App\Mail\VerifyHoaSubUserEmail;
use App\PasscodeResetToken;
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
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Stripe\ApiOperations\All;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiHoaController extends Controller
{

    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {


            $user = Auth::user();
            $query = HoaUser::query();
            $query->with('session');
            $query->withCount('session');
            $query->with('subscription');
            $query->where('user_id', $user->id);

            $query->when($request->has('status'), function ($q) use ($request) {
                $q->where('status', $request->status);

            });
            $query->when($request->has('q'), function ($q) use ($request) {
                $q->where('passcode', 'like', '%' . $request->q . '%')
                    ->orWhere('name', 'like', '%' . $request->q . '%')
                    ->orWhere('phone', 'like', '%' . $request->q . '%')
                    ->orWhere('house_no', 'like', '%' . $request->q . '%');
            });
            $limit = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $data = $query->paginate($limit, ['*'], 'page', $page);
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Data fetched successfully !',
                'total_houses' => auth()->user()->num_of_houses,
                'device_limit_per_house' => auth()->user()->device_per_house,
                'active_house_count' => HoaUser::activeHouseCount(),
                'deactive_house_count' => HoaUser::inactiveHouseCount(),
                'data' => $data->items(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ], 200);



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

    public function resendMail($id)
    {
        try {
            $hoaUser = HoaUser::where('id', $id)->first();
            if ($hoaUser == null) {
                return $this->notFoundResponse("User not found !");
            }


            if ($hoaUser->passcode != null) {
                return $this->errorResponse("User already verified !");
            }
            $subscription = HoaSubUserSubscription::where('sub_user_id', $hoaUser->id)->first();
            if ($subscription != null) {

                Mail::to($hoaUser->email)->send(new VerifyHoaSubUserEmail($hoaUser, intval(trim($subscription->plan_name)), $subscription->amount));
            }

            DB::commit();
            return $this->successResponse("Verification mail resend successfully !");
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

    public function regeneratePasscode(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',

        ]);


        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());
        }
        $tokenData = PasscodeResetToken::where('email', $request->email)
            ->where('token', $token)
            ->latest()
            ->first();


        if ($tokenData == null) {

            Alert::error('Error', 'Invalid link. Please request a new passcode reset.');
            return redirect('/');
            // return redirect('/')->with('mainalert', [
            //     'title' => 'Error !',
            //     'text' => 'Link expired. Please request again forgot passcode .',
            //     'icon' => 'error'
            // ]);
        }
        $expireIn = Carbon::parse($tokenData->created_at)->diffInMinutes(now());
        if ($expireIn >= 30) {
            Alert::error('Error', 'Link expired. Please request a new passcode reset.');
            return redirect('/');
            // return redirect('/')->with('mainalert', [
            //     'title' => 'Error !',
            //     'text' => 'Link expired. Please request again forgot passcode .',
            //     'icon' => 'error'
            // ]);
        }

        $hoaUser = HoaUser::where('email', $request->email)
            ->where('status', 1)
            ->first();
        if ($hoaUser == null) {

            Alert::error('Error', 'No active account found for the provided email.');
            return redirect('/');
            // return redirect('/')->with('mainalert', [
            //     'title' => 'Error !',
            //     'text' => 'No active account found for the provided email.',
            //     'icon' => 'error'
            // ]);

        }



        try {
            DB::beginTransaction();
            $this->removeActiveSessions($hoaUser->passcode, $hoaUser->user_id);
            $newPasscode = HoaUser::generatePasscodeForHoa();
            $hoaUser->passcode = $newPasscode;
            $hoaUser->save();

            PasscodeResetToken::where('token', $token)->delete();

            DB::commit();

            Mail::to($hoaUser->email)
                ->send(new HoaSubUserPasscodeRegenerate($hoaUser));

            Alert::success('Success', 'Passcode regenerated successfully ! Please check your email.')
                ->persistent(true)
                ->autoClose(false);
            return redirect('/');
            // return redirect('/')->with('mainalert', [
            //     'title' => 'Success!',
            //     'text' => 'Passcode regenerated successfully ! Please check your email.',
            //     'icon' => 'success'
            // ]);


        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error('Error', $th->getMessage());
            return redirect('/');
            // return redirect('/')->with('mainalert', [
            //     'title' => 'Error !',
            //     'text' => 'Something went wrong ! Please try again later.',
            //     'icon' => 'error'
            // ]);

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

    public function changeStatus($id)
    {




        $user = Auth::user();

        if ($id != null) {
            $hoaUser = HoaUser::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
        }
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
            if (!empty($hoaUser->passcode)) {
                $this->removeActiveSessions($hoaUser->passcode, $user->id);
            }



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
            } else {
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
            return redirect('/auth/login');

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
            if ($hoaUser->passcode != null) {
                toast("Account already verified !", "error")
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect('/auth/login');
            }
            HoaUser::markedEmailVerified($id);
            // send link to frontend

            // $accountRegisterUrl = URL::temporarySignedRoute(
            //     url('/hoa/sub-user-account/register'),
            //     Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            //     [
            //         'id' => $id,
            //         'hash' => sha1($hoaUser->email),
            //         'plan_in_month' => $request->plan_in_month,
            //         'amount' => $request->amount,
            //     ]
            // );



            // $params = [
            //     'id' => $id,
            //     'hash' => sha1($hoaUser->email),
            //     'plan_in_month' => $request->plan_in_month,
            //     'amount' => $request->amount,
            //     'expires' => $expires,
            // ];
            $expires = now()->addMinutes(60)->timestamp;
            $accountRegisterUrl = url('/hoa/sub-user-account/register')
                . "/" . $id . "/" . sha1($hoaUser->email)
                . "?plan_in_month=" . $request->plan_in_month
                . "&amount=" . $request->amount . "&expires=" . $expires;

            $subscription = HoaSubUserSubscription::where('sub_user_id', $id)->first();
            if ($subscription == null) {
                $subs = new HoaSubUserSubscription();
                $subs->user_id = $hoaUser->user_id;
                $subs->sub_user_id = $hoaUser->id;
                $subs->amount = $request->amount;
                $subs->plan_name = $request->plan_in_month . ' Month Plan';
                $subs->save();
            }
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


    public function forgotPasscode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:225',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());

        }
        $hoaUser = HoaUser::where('email', $request->email)->first();
        if ($hoaUser == null) {
            return $this->notFoundResponse("Data not found !");
        } elseif ($hoaUser->status == 0) {
            return $this->errorResponse("Account is deactivated, kindly contact admin.", 403);
        }



        try {
            DB::beginTransaction();
            $token = Str::random(64);
            $count = PasscodeResetToken::where('email', $request->email)
                ->whereDate('created_at', today())
                ->count();
            if ($count >= 3) {

                return response()->json([
                    'success' => false,
                    'response_code' => 429,
                    'message' => 'You have exceeded the number of allowed forgot passcode requests for today. Please try again later after 24 hours.',
                ], 429);
            }
            DB::table('passcode_reset_tokens')->insert(
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => now()
                ]
            );


            $resetPasscodeUrl = route('sub.user.regenerate.passcode', $token) . '?email=' . $request->email;

            Mail::to($request->email)->send(new HoaSubUserForgotPasscode($resetPasscodeUrl));
            DB::commit();
            return $this->successResponse("Please check your email for regenerate passcode link !");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }




}
