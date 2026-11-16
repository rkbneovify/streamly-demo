<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrashReport;
use App\SessionTable;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiCrashReportController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected Request $request, protected CrashReport $crashReport) {}

    public function store()
    {
        $validator = Validator::make($this->request->all(), [
            'crash_reports' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->first(), $validator->errors());
        }

        DB::beginTransaction();
        try {
            // $crashReports = $this->request->input('crash_reports', []);

            foreach ($this->request->crash_reports as $item) {
                $item['user_id'] = auth()->id();
                $item['session_id'] = SessionTable::getCurrentUserByToken()->id;
                $this->crashReport->create($item);
            }
            DB::commit();
            return $this->successResponse("Data saved successfully !");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $this->exceptionResponse($th);
        }
    }
}
