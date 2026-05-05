<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use App\SessionTable;
use App\SubSession;
use App\Traits\ApiResponseTrait;
use App\Traits\DbTransactionTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ApiSubSessionController extends Controller
{

    use ApiResponseTrait, DbTransactionTrait;

    public function store(Request $request)
    {

        return $this->dbTransaction(function () use ($request) {
            $session = SessionTable::where('access_token', $request->bearerToken())
                ->where('status', 1)->first();
            if ($session != null) {
                //  1 HOUR INTERVAL
                $alreadyExists = SubSession::where('session_id', $session->id)
                    ->where('created_at', '>=', Carbon::now()->subHour())
                    ->exists();

                if (!$alreadyExists) {

                    $sub = new SubSession();
                    $sub->session_id = $session->id;
                    $sub->user_id = $session->users_id;
                    $sub->is_main_account = $session->is_main_account;
                    $sub->passcode = $session->passcode;
                    $sub->platform = $request->platform;
                    $sub->device_model = $request->device_model;
                    $sub->build_version = $request->build_version;
                    $sub->device_os_version = $request->device_os_version;
                    $sub->zipcode = $request->zipcode;
                    $sub->save();

                    return $this->successResponse("Data store successfully !");

                } else {
                    return $this->validationErrorResponse("Session already exits");
                }

            }


        });

    }

    public function upload(Request $request, FileUploadService $uploadService)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        try {
            $fileData = $uploadService->upload($request->file('file'), [
                'path' => 'tickets',
                'allowed_types' => ['jpg', 'png', 'pdf', 'docx'],
                'max_size' => 2048, // KB
            ]);

            // Save in DB if needed
            // Attachment::create($fileData);

            return response()->json($fileData);
        } catch (Throwable $th) {
            return response()->json($th->getMessage());

        }
    }


}
