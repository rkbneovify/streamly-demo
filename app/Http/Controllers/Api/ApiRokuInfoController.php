<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\RokuInfo;
use App\Traits\ApiResponseTrait;
use App\Traits\DbTransactionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiRokuInfoController extends Controller
{
    use ApiResponseTrait, DbTransactionTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'nullable|string',
            'build_version' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->first(), $validator->errors());
        }

        return $this->dbTransaction(function () use ($request) {
            $roku = new RokuInfo();
            $roku->user_id = Auth::id() == null ? null : Auth::id();
            $roku->token = $request->token;
            $roku->build_version = $request->build_version;
            $roku->save();
            return $this->successResponse("Data saved successfully !");

        });
    }
}
