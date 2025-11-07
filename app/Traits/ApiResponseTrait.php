<?php
namespace App\Traits;

trait ApiResponseTrait
{

    public function successResponse($message = null, $code = 200, $success = true)
    {
        return response()->json([
            'success' => $success,
            'response_code' => $code,
            'message' => $message ?? 'Request processed successfully !',

        ], $code);
    }
    public function exceptionResponse($th, $message = null, $code = 500, $success = false)
    {
        if (env('APP_DEBUG')) {
            $errorMessage = $th->getMessage();
        } else {
            $errorMessage = $message ?? 'Something went wrong! Please try again later.';
        }
        return response()->json([
            'success' => $success,
            'response_code' => $code,
            'message' => $errorMessage,
        ], $code);
    }

    public function notFoundResponse($message = null, $code = 404, $success = false)
    {
        return response()->json([
            'success' => $success,
            'response_code' => $code,
            'message' => $message ?? 'Resource not found !',

        ], $code);
    }

    public function validationErrorResponse($message = null, $errors, $code = 422, $success = false)
    {
        return response()->json([
            'success' => $success,
            'response_code' => $code,
            'message' => $message ?? 'Validation error !',
            'errors' => $errors,

        ], $code);
    }
}