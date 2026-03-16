<?php

namespace App\Http\Controllers\Api;

use App\ActivityLog;
use App\Enums\ActivityEventType;
use App\Exports\HoaSubUserExport;
use App\HoaSubUserSubscription;
use App\HoaUser;
use App\Http\Controllers\Controller;
use App\Mail\HoaSubUserEditVerification;
use App\Mail\HoaSubUserForgotPasscode;
use App\Mail\HoaSubUserPasscodeRegenerate;
use App\Mail\HoaSubUserWelcome;
use App\Mail\HoaSubUserWelcome2;
use App\Mail\VerifyHoaSubUserEmail;
use App\PasscodeResetToken;
use App\Services\NotificationService;
use App\SessionTable;
use App\Traits\ActivityLogger;
use App\Traits\ApiResponseTrait;
use App\Traits\DbTransactionTrait;
use App\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiHoaController extends Controller
{

    use ApiResponseTrait, DbTransactionTrait, ActivityLogger;

    public function index(Request $request)
    {


        try {



            $query = HoaUser::query()
                ->with(['session', 'subscription'])
                ->withCount('session')
                ->where('user_id', auth()->id());




            $query->status($request->status)
                ->search($request->q)
                ->sort($request->sort);







            if ($request->sort == null) {

                $query->latest();

            }
            $limit = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            $data = $query->paginate($limit, ['*'], 'page', $page);
            $data->getCollection()->transform(function ($item) {
                $item->house_no = $item->house_no ?? "house" . $item->ordering;
                return $item;
            });


            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Data fetched successfully !',
                'total_houses' => auth()->user()->num_of_houses,
                'device_limit_per_house' => auth()->user()->device_per_house,
                'total_devices' => (auth()->user()->num_of_houses * auth()->user()->device_per_house),
                'alloted_device_count' => HoaUser::getDeviceCount(),
                'active_house_count' => HoaUser::activeHouseCount(),
                'deactive_house_count' => HoaUser::inactiveHouseCount(),
                'data' => $data->items(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'is_more_data' => $page >= $data->lastPage() ? false : true,
            ], 200);



        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }

    public function index2(Request $request)
    {


        try {



            $query = HoaUser::query()
                ->with(['session', 'subscription'])
                ->withCount('session')
                ->where('user_id', auth()->id());




            $query->when($request->has('status'), function ($q) use ($request) {
                if ($request->status != '') {
                    $q->where('status', $request->status);
                }
            });


            $query->when($request->has('q'), function ($q) use ($request) {
                $txt = $request->q;
                if ($txt != '') {
                    $q->where(function ($subQuery) use ($txt) {
                        $subQuery->where('ordering', 'like', "%{$txt}%")
                            ->orWhere('passcode', 'like', "%{$txt}%")
                            ->orWhere('name', 'like', "%{$txt}%")
                            ->orWhere('email', 'like', "%{$txt}%")
                            ->orWhere('phone', 'like', "%{$txt}%")
                            ->orWhere('house_no', 'like', "%{$txt}%");
                    });
                }
            });



            $query->when($request->has('sort'), function ($q) use ($request) {
                if ($request->sort == 'latest') {
                    $q->latest();
                } elseif ($request->sort == 'oldest') {
                    $q->orderBy('created_at', 'asc');
                }


            });
            if ($request->sort == null) {

                $query->latest();

            }
            $data = $query->get()->map(function ($item) {
                $item->house_no = $item->house_no ?? "house" . $item->ordering;
                return $item;
            });
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Data fetched successfully !',
                'total_houses' => auth()->user()->num_of_houses,
                'device_limit_per_house' => auth()->user()->device_per_house,
                'active_house_count' => HoaUser::activeHouseCount(),
                'deactive_house_count' => HoaUser::inactiveHouseCount(),
                'data' => $data,

            ], 200);



        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }

    public function notificationUnreadCount()
    {
        $query = ActivityLog::query();
        $query->where('user_id', auth()->user()->id)
            ->where('is_hoa', 1)->where('is_read', 0);
        $count = $query->count();

        return response()->json([
            'success' => true,
            'response_code' => 200,
            'message' => 'Data fetched successfully !',
            'count' => $count,

        ], 200);
    }
    public function notificationList(Request $request)
    {

        $query = ActivityLog::query();
        $query->where('user_id', auth()->user()->id)
            ->where('is_hoa', 1);
        $limit = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $data = $query->paginate($limit, ['*'], 'page', $page);
        // ActivityLog::resolveNotificationForHoa(auth()->user()->id);
        return response()->json([
            'success' => true,
            'response_code' => 200,
            'message' => 'Data fetched successfully !',
            'data' => $data->items(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
            'is_more_data' => $page >= $data->lastPage() ? false : true,
        ], 200);
    }
    public function addHouse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|array',
            'email' => 'nullable|email|max:225',
            'plan_in_month' => 'required|in:1,3,6,12',
            'amount' => 'nullable|numeric',
            'device_limit' => 'required|integer'
        ]);



        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());
        }



        DB::beginTransaction();
        try {
            // if (!HoaUser::isHouseAvailable()) {
            //     return $this->maxlimitExceed("You have reached the maximum number of houses.");
            // } elseif ($request->device_limit > auth()->user()->device_per_house) {
            //     return $this->validationErrorResponse("Per house device limit exceed !");
            // }
            $remainingDeviceCount = HoaUser::getRemainingDeviceCount();

            if ($request->device_limit > $remainingDeviceCount) {
                return $this->errorResponse("Device limit exceed !");
            }


            $hoaUser = HoaUser::whereNotNull('email')->where('email', $request->email)->first();
            if ($hoaUser != null) {
                return $this->validationErrorResponse("Email already exits !");
            }
            if ($hoaUser == null && $request->email != null) {
                $hoaUser = new HoaUser();
                $hoaUser->user_id = auth()->user()->id;
                $hoaUser->ordering = HoaUser::where('user_id', auth()->user()->id)->max('ordering') + 1;
                $hoaUser->email = $request->email;
                $hoaUser->device_limit = $request->device_limit;
                $hoaUser->save();
                $subscription = HoaSubUserSubscription::where('sub_user_id', $hoaUser->id)->first();
                if ($subscription == null) {
                    $subs = new HoaSubUserSubscription();
                    $subs->user_id = $hoaUser->user_id;
                    $subs->sub_user_id = $hoaUser->id;
                    $subs->amount = $request->amount;
                    $subs->plan_name = $request->plan_in_month . ' Month Plan';
                    $subs->save();
                }
                $this->activity()->event("Hoa main")
                    ->eventType(ActivityEventType::CREATED)
                    ->description("New house created")
                    ->isUser()
                    ->properties([
                        "data" => $hoaUser
                    ])
                    ->save();
            } else {
                $hoaUser = new HoaUser();
                $hoaUser->user_id = auth()->user()->id;
                $hoaUser->ordering = HoaUser::where('user_id', auth()->user()->id)->max('ordering') + 1;
                $hoaUser->email = $request->email;
                $hoaUser->device_limit = $request->device_limit;
                $hoaUser->passcode = HoaUser::generatePasscodeForHoa();
                $hoaUser->status = 1;
                $hoaUser->save();
                $subscription = HoaSubUserSubscription::where('sub_user_id', $hoaUser->id)->first();
                if ($subscription == null) {
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

                $this->activity()->event("Hoa main")
                    ->eventType(ActivityEventType::CREATED)
                    ->isUser()
                    ->description("house created")
                    ->properties([
                        "data" => $hoaUser
                    ])
                    ->save();

            }




            if ($request->email != null) {
                Mail::to($request->email)->send(new VerifyHoaSubUserEmail($hoaUser, $request->plan_in_month, $request->amount));
            }

            DB::commit();
            return $this->successResponse("House added successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->exceptionResponse($th);
        }



    }

    public function editHouse(Request $request)
    {


        $validator = validator($request->all(), [
            'house_id' => 'required',
            'email' => 'nullable|email|max:225',
            'plan_in_month' => 'nullable|in:1,3,6,12',
            'device_limit' => 'nullable|integer',
            // 'zipcode' => 'nullable|digits:5',
            // 'house_no' => 'nullable|alpha_num|max:20',
            // 'phone' => 'nullable|digits:10',
            // 'name' => 'nullable|string|max:225',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse("Validation error !", $validator->errors());
        }


        return $this->dbTransaction(function () use ($request) {
            $hoaUser = HoaUser::where('id', $request->house_id)->first();
            if ($hoaUser == null) {
                return $this->notFoundResponse("Data not found !");
            }

            $this->activity()->event("Hoa main")
                ->eventType(ActivityEventType::UPDATED)
                ->description("House updated")
                ->properties([
                    "old_data" => $hoaUser
                ]);

            // logic if email already exists;
            if ($hoaUser->email == null && $request->email != null) {
                $emailAlreadyExists = HoaUser::where('email', $request->email)->first();
                if ($emailAlreadyExists != null) {
                    return $this->errorResponse("Email already exits", 422);
                }

                $hoaUser->email = $request->email;
                $hoaUser->save();
                $verificationUrl = url('hoa/sub-user-account/verify') . '/' . $hoaUser->id;
                Mail::to($request->email)->send(new HoaSubUserEditVerification($hoaUser, $verificationUrl));
            }





            // $remainingDevices = HoaUser::getRemainingDeviceCount();
            // return $this->errorResponse("You remaining device Limit is " . $remainingDevices);
            //  device limit update

            if ($request->device_limit != $hoaUser->device_limit) {
                if ($request->device_limit > $hoaUser->device_limit) {

                    $remainingDevices = HoaUser::getRemainingDeviceCount();
                    $deviceCountNeedToUpdate = $request->device_limit - $hoaUser->device_limit;

                    if ($deviceCountNeedToUpdate > $remainingDevices) {

                        return $this->errorResponse("You remaining device Limit is " . $remainingDevices);
                    }
                    $hoaUser->device_limit = $request->device_limit;
                    $hoaUser->save();
                }

                $hoaUser->device_limit = $request->device_limit;
                $hoaUser->save();
            }

            // subscription update
            if ($request->plan_in_month != null) {
                $subscription = HoaSubUserSubscription::where('sub_user_id', $hoaUser->id)->latest()->first();
                if ($subscription == null) {
                    return $this->notFoundResponse("Subscription not found !");
                }

                $currentPlan = intval($subscription->plan_name);
                $timestampAfterMonth = date('Y-m-d H:i:s', strtotime('+' . $request->plan_in_month . ' month', strtotime($subscription->plan_start_at)));
                if ($request->plan_in_month != $currentPlan) {
                    $subscription->plan_name = $request->plan_in_month . ' Month';
                    $subscription->plan_expires_at = $timestampAfterMonth;
                    $subscription->save();

                }
            }

            $this->properties([
                "new_data" => $hoaUser->refresh()
            ])
                ->isUser()
                ->save();

            return $this->dataResponse([
                'user' => $hoaUser
            ], 'User updated successfully !');
        });


    }

    public function verifyEditHouse(Request $request)
    {


        $validator = validator($request->all(), [
            'house_id' => 'required',
            // 'email' => 'nullable|email|max:225',
            // 'plan_in_month' => 'nullable|in:1,3,6,12',
            // 'device_limit' => 'nullable|integer',
            'zipcode' => 'nullable|digits:5',
            'house_no' => 'nullable|alpha_num|max:20',
            'phone' => 'nullable|digits:10',
            'name' => 'nullable|string|max:225',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse("Validation error !", $validator->errors());
        }


        return $this->dbTransaction(function () use ($request) {
            $hoaUser = HoaUser::where('id', $request->house_id)->first();
            if ($hoaUser == null) {
                return $this->notFoundResponse("Data not found !");
            }

            $hoaUser->name = $request->name;
            $hoaUser->house_no = $request->house_no;
            $hoaUser->phone = $request->phone;
            $hoaUser->zipcode = $request->zipcode;
            $hoaUser->edit_verify = 1;
            $hoaUser->email_verified_at = now();
            $hoaUser->save();
            $user = User::where('id', $hoaUser->user_id)->first();
            Mail::to($hoaUser->email)->send(new HoaSubUserWelcome2($hoaUser, $user->email));

            $this->activity($hoaUser->user_id)->event("Hoa sub user")
                ->eventType(ActivityEventType::Verification)
                ->description("Sub user account verified")
                ->isUser()
                ->isHoa()
                ->properties([
                    "data" => $hoaUser->refresh()
                ])->save();

            return $this->successResponse('Acoount Verification done successfully ! Please check your email.');
        });


    }

    public function resendEditVerification($id)
    {
        return $this->dbTransaction(function () use ($id) {
            $hoaUser = HoaUser::where('id', $id)->first();
            if ($hoaUser == null) {
                return $this->notFoundResponse("Data not found !");
            }
            $hoaUser->edit_verify = 0;
            $hoaUser->save();
            $verificationUrl = url('hoa/sub-user-account/verify') . '/' . $hoaUser->id;
            Mail::to($hoaUser->email)->send(new HoaSubUserEditVerification($hoaUser, $verificationUrl));

            $this->activity()->event("Hoa main")
                ->eventType(ActivityEventType::MailResend)
                ->description("Mail resent to user")
                ->isUser()
                ->properties([
                    "data" => $hoaUser->refresh()
                ])->save();
            return $this->successResponse('Mail resent successfully ! Please check your email.');
        });
    }

    public function addPlan(Request $request)
    {
        $validator = Validator($request->all(), [
            "house_id" => 'required|integer',
            "device_limit" => 'nullable|integer',
            'plan_in_month' => 'nullable|in:1,3,6,12',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->first(), $validator->errors());
        }


        return $this->dbTransaction(function () use ($request) {
            $hoaUser = HoaUser::where('id', $request->house_id)->first();
            if ($hoaUser == null) {
                return $this->errorResponse("Data not found !", 404);
            }

            $startDate = Carbon::now()->format('Y-m-d H:i:s');
            $endDate = Carbon::now()->addMonths(intval($request->plan_in_month))->format('Y-m-d H:i:s');
            $subscription = new HoaSubUserSubscription();
            $subscription->user_id = $hoaUser->user_id;
            $subscription->sub_user_id = $hoaUser->id;
            $subscription->passcode = $hoaUser->passcode;
            $subscription->plan_name = $request->plan_in_month . " Month";
            $subscription->plan_start_at = $startDate;
            $subscription->plan_expires_at = $endDate;
            $subscription->save();


            //  device limit change

            if ($request->device_limit > $hoaUser->device_limit) {
                $remainingDevices = HoaUser::getRemainingDeviceCount();
                $deviceCountNeedToUpdate = $request->device_limit - $hoaUser->device_limit;

                if ($deviceCountNeedToUpdate > $remainingDevices) {

                    return $this->errorResponse("You remaining device Limit is " . $remainingDevices);
                }

            }

            $hoaUser->device_limit = $request->device_limit;
            $hoaUser->save();

            $this->activity()->event("Hoa main")
                ->eventType(ActivityEventType::NewPlanAdded)
                ->description("New plan added")
                ->isUser()
                ->properties([
                    "data" => $hoaUser->subscriptionLatest

                ])->save();

            return $this->successResponse("Plan added successfully !");


        });
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

            $this->activity()->event("Hoa main")
                ->eventType(ActivityEventType::MailResend)
                ->description("Mail resent to user")
                ->isUser()
                ->properties([
                    "data" => $hoaUser
                ])->save();
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

            $this->activity()->event("Hoa main")
                ->eventType(ActivityEventType::DELETED)
                ->description("House deleted")
                ->isUser()
                ->properties([
                    "data" => $hoaUser
                ])
                ->save();
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

        $redirectUrl = url('/server-message');
        if ($tokenData == null) {


            $redirectUrl .= '?type=error&message=Invalid link. Please request a new passcode reset.';
            return redirect($redirectUrl);

        }
        $expireIn = Carbon::parse($tokenData->created_at)->diffInMinutes(now());
        if ($expireIn >= 30) {
            Alert::error('Error', 'Link expired. Please request a new passcode reset.');
            $redirectUrl .= '?type=error&message=Link expired. Please request a new passcode reset.';
            return redirect($redirectUrl);
        }

        $hoaUser = HoaUser::where('email', $request->email)
            ->where('status', 1)
            ->first();
        if ($hoaUser == null) {


            $redirectUrl .= '?type=error&message=No active account found for the provided email.';
            return redirect($redirectUrl);

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


            $this->activity()->event("Hoa sub user")
                ->eventType(ActivityEventType::Regenerate)
                ->descriptionSimple("Sub user regenerated new passcode | " . $hoaUser->email)
                ->isUser()
                ->isHoa()
                ->properties([
                    "data" => $hoaUser
                ])->save();
            $redirectUrl .= '?type=success&message=Passcode regenerated successfully ! Please check your email.';
            return redirect($redirectUrl);


        } catch (\Throwable $th) {
            DB::rollBack();

            // Alert::error('Error', $th->getMessage());
            // return redirect('/');
            // return redirect('/')->with('mainalert', [
            //     'title' => 'Error !',
            //     'text' => 'Something went wrong ! Please try again later.',
            //     'icon' => 'error'
            // ]);
            $redirectUrl .= '?type=error&message=Something went wrong ! Please try again later.';
            return redirect($redirectUrl);

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

    public function changeStatus(Request $request, $id = null)
    {


        if ($id == null) {
            $validator = Validator::make($request->all(), [
                'passcode' => 'required|string|max:20',
            ]);
            if ($validator->fails()) {
                return $this->validationErrorResponse(null, $validator->errors());

            }
        }



        $user = Auth::user();

        if ($id != null) {
            $hoaUser = HoaUser::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
        } elseif ($request->passcode != null) {
            $hoaUser = HoaUser::whereNotNull('passcode')
                ->where('passcode', $request->passcode)
                ->where('user_id', $user->id)
                ->first();
        }

        if ($hoaUser == null) {
            return $this->notFoundResponse('Data not found !');

        }
        $message = $hoaUser->status == 1 ? 'deactivated' : 'activated';
        try {
            DB::beginTransaction();
            if ($hoaUser->status == 1) {
                $hoaUser->status = 0;
                $this->activity()
                    ->event("Hoa main")
                    ->eventType(ActivityEventType::STATUSDISABLE)
                    ->description("House disable")
                    ->isUser()
                    ->properties([
                        "data" => $hoaUser
                    ])
                    ->save();
            } else {
                $hoaUser->status = 1;
                $this->activity()
                    ->event("Hoa main")
                    ->eventType(ActivityEventType::STATUSENABLE)
                    ->description("House enable")
                    ->isUser()
                    ->properties([
                        "data" => $hoaUser
                    ])
                    ->save();
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
            $this->activity()->event("Hoa main")
                ->eventType(ActivityEventType::REMOVED)
                ->description("Session removed")
                ->isUser()
                ->properties([
                    "data" => $session
                ])->save();
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


            $this->activity()->event("Hoa main")
                ->eventType(ActivityEventType::REMOVED)
                ->description("Multiple session removed")
                ->isUser()
                ->properties([
                    "data" => $sessions
                ])->save();

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
            // if (!HoaUser::isEmailVerified($hoaUser->id)) {
            //     return $this->errorResponse("Please verify your email", 403);
            // }
            // if ($hoaUser->status == 1) {
            //     return $this->errorResponse("Account already active.", 403);
            // }
            $user = User::where('id', $hoaUser->user_id)->first();
            if ($user == null) {
                return $this->notFoundResponse("Data not found !");
            }

            $hoaUser->ordering = HoaUser::where('user_id', $hoaUser->user_id)->max('ordering') + 1;
            $hoaUser->name = $request->name;
            $hoaUser->house_no = $request->house_no;
            $hoaUser->phone = $request->phone;
            $hoaUser->passcode = HoaUser::generatePasscodeForHoa();
            $hoaUser->status = 1;
            $hoaUser->zipcode = $request->zipcode;
            $hoaUser->email_verified_at = now();
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

            $this->activity($hoaUser->user_id)->event("Hoa sub user")
                ->eventType(ActivityEventType::AccountActive)
                ->descriptionSimple("Sub user account active | " . $hoaUser->email ?? $hoaUser->passcode)
                ->isUser()
                ->isHoa()
                ->properties([
                    "data" => $hoaUser
                ])->save();
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
            $hoaUser = HoaUser::where('id', $id)->first();
            $this->activity($hoaUser->user_id)->event("Hoa sub user")
                ->eventType(ActivityEventType::AccountActive)
                ->descriptionSimple("Verification link expired. House :" . $hoaUser->email ?? $hoaUser->passcode)
                ->isUser()
                ->isHoa()
                ->properties([
                    "data" => $hoaUser
                ])->save();

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
            // HoaUser::markedEmailVerified($id);


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
            $this->activity($hoaUser->user_id)->event("Hoa sub user")
                ->eventType(ActivityEventType::Forgot)
                ->description("Sub user passcode regenerate request")
                ->isUser()
                ->isHoa()
                ->properties([
                    "data" => $hoaUser
                ])->save();


            Mail::to($request->email)->send(new HoaSubUserForgotPasscode($resetPasscodeUrl));
            DB::commit();
            return $this->successResponse("Please check your email for regenerate passcode link !");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }

    public function deleteAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'house_ids' => 'nullable|array',
            'house_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse(null, $validator->errors());

        }

        DB::beginTransaction();
        try {
            $houseList = HoaUser::where('user_id', auth()->user()->id)->get();
            if (!empty($request->house_ids)) {
                $houseList = HoaUser::whereIn('id', $request->house_ids)->get();
            }
            if (count($houseList) <= 0) {
                return $this->notFoundResponse("Data not found !");
            }

            $this->activity()->event("Hoa main")
                ->eventType(ActivityEventType::DELETED)
                ->description("Multiple house deleted")
                ->isUser()
                ->properties([
                    "data" => $houseList
                ])->save();

            foreach ($houseList as $item) {
                HoaSubUserSubscription::where('sub_user_id', $item->id)->delete();

                $item->delete();
            }
            DB::commit();
            return $this->successResponse("Houses deleted successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }



    public function exportIntoExcel(Request $request)
    {

        try {
            $filename = 'hoa_house_list_' . now()->format('d-m-Y_H-i-s') . '.xlsx';
            return Excel::download(new HoaSubUserExport($request, auth()->user()->id), $filename);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->exceptionResponse($th);
        }
    }

    public function planExpireNotification(NotificationService $notification)
    {

        return $this->dbTransaction(function () use ($notification) {
            $subscriptions = HoaSubUserSubscription::whereDate('plan_expires_at', '<=', Carbon::now()->addDays(10))
                ->get();

            $tokenList = [];
            foreach ($subscriptions as $item) {
                $daysLeft = now()->diffInDays($item->plan_expires_at, false);
                $hoaUser = HoaUser::where('id', $item->sub_user_id)->first();

                $this->activity($item->user_id)
                    ->event("Hoa sub user")
                    ->eventType(ActivityEventType::Subscription)
                    ->notification()
                    ->isHoa()->isUser();

                if ($daysLeft > 0 && $hoaUser != null) {
                    $item->user = $hoaUser;
                    if ($daysLeft == 1) {
                        $this->descriptionSimple(($hoaUser->passcode ?? $hoaUser->email) . "  | Subscription expire in " . $daysLeft . " day.")
                            ->properties([
                                "data" => $item
                            ])->save();
                        $fcmToken = HoaUser::mainUserFcmToken($hoaUser->user_id);
                        $tokenList[] = $fcmToken;
                        if ($fcmToken != null) {
                            $notification->send($token, "Subscription", (($hoaUser->passcode ?? $hoaUser->email) . "  | Subscription expire in " . $daysLeft . " day."));
                        }


                    } elseif ($daysLeft > 1) {
                        $this->descriptionSimple(($hoaUser->passcode ?? $hoaUser->email) . "  | Subscription expire in " . $daysLeft . " days.")
                            ->properties([
                                "data" => $item
                            ])->save();

                        $fcmToken = HoaUser::mainUserFcmToken($hoaUser->user_id);
                        $tokenList[] = $fcmToken;
                        if ($fcmToken != null) {
                            foreach ($fcmToken as $token) {
                                $notification->send($token, "Subscription", (($hoaUser->passcode ?? $hoaUser->email) . "  | Subscription expire in " . $daysLeft . " day."));
                            }
                        }
                    }
                } else {
                    if ($hoaUser != null) {
                        $item->user = $hoaUser;
                        $this->descriptionSimple(($hoaUser->passcode ?? $hoaUser->email) . "  | Subscription expired")
                            ->properties([
                                "data" => $item
                            ])->save();

                        $fcmToken = HoaUser::mainUserFcmToken($hoaUser->user_id);
                        $tokenList[] = $fcmToken;
                        if ($fcmToken != null) {

                            foreach ($fcmToken as $token) {
                                $notification->send($token, "Subscription", (($hoaUser->passcode ?? $hoaUser->email) . "  | Subscription expired"));
                            }
                        }
                    }
                }


            }



            return response()->json(

                [
                    'status' => true,
                    // 'tokens' => $tokenList,
                    'message' => 'Notification sent successfully !',
                ]
            );



        });


    }





}
