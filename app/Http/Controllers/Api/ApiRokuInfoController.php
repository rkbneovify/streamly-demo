<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\RokuInfo;
use App\SessionTable;
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
        // return response()->json(request()->bearerToken());
        $validator = Validator::make($request->all(), [
            'token' => 'nullable|string',
            'build_version' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->first(), $validator->errors());
        }

        return $this->dbTransaction(function () use ($request) {

            $token = request()->bearerToken();
            $sesstion = SessionTable::where(
                'access_token',
                'LIKE',
                '%' . $token . '%'
            )->first();
            $roku = new RokuInfo();
            $roku->user_id = $sesstion != null ? $sesstion->users_id : null;
            $roku->token = $request->token;
            $roku->build_version = $request->build_version;
            $roku->save();
            return $this->successResponse("Data saved successfully !");

        });
    }
}
