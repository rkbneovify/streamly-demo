<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\SessionTable;
use App\SubSession;
use App\Traits\ApiResponseTrait;
use App\Traits\DbTransactionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiSubSessionController extends Controller
{

    use ApiResponseTrait, DbTransactionTrait;

    public function store(Request $request)
    {
        return $this->dbTransaction(function () use ($request) {
            $sub = SubSession::whereDate('created_at', now())->exists();

            if (!$sub) {
                $session = SessionTable::where('access_token', $request->bearerToken())
                    ->where('status', 1)->first();
                if ($session != null) {
                    $sub = new SubSession();
                    $sub->session_id = $session->id;
                    $sub->user_id = $session->users_id;
                    $sub->platform = $request->platform;
                    $sub->device_model = $request->device_model;
                    $sub->build_version = $request->build_version;
                    $sub->device_os_version = $request->device_os_version;

                    $sub->save();

                    return $this->successResponse("Data store successfully !");

                }

            } else {
                return $this->validationErrorResponse("Current date session already exits");
            }

        });

    }


}
