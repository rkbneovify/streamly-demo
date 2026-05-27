<?php

namespace App\Http\Controllers\Api;

use App\Enums\ActivityEventType;
use App\Http\Controllers\Controller;
use App\SubscriptionPlanRequest;
use App\Traits\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiSubscriptionPlanRequestController extends Controller
{
    use ActivityLogger;
    public function planRequestByUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|string|max:225|exists:subscription_plans,id'
        ], [
            'plan_id.exists' => 'Plan id invalid.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'response_code' => 422,
                'message' => $validator->errors()->first(),
                'error' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $planReq = new SubscriptionPlanRequest();
            $planReq->user_id = Auth::id();
            $planReq->plan_id = $request->plan_id;
            $planReq->status = 'under_review';
            $planReq->save();

            $this->activity()
                ->event("Subscription")
                ->eventType(ActivityEventType::PlanRequest)
                ->isAdmin()
                ->description("Subscription plan request by user")
                ->properties([
                    "data" => $planReq
                ])
                ->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'response_code' => 200,
                'message' => 'Subscription sent successfully !',

            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return response()->json([
                'status' => false,
                'response_code' => 500,
                'message' => 'Something went wrong ! Please try again later .',

            ], 500);
        }
    }
}
