<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class ApiSubscriptionPlanController extends Controller
{
    public function index()
    {
        try {
            $plans =  SubscriptionPlan::where('status', 1)->get();
            return response()->json([
                'status' => true,
                'response_code' => 200,
                'data' => $plans
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'response_code' => 500,
            ], 500);
        }
    }
}
