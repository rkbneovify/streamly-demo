<?php
namespace App\Traits;

use Throwable;

trait ApiResponseTrait
{

    public function successResponse($data = null, $message = null, $code = 200, $success = true)
    {

        return response()->json(
            [
                'success' => $success,
                'response_code' => $code,
                'message' => $message ?? 'Request processed successfully !',
            ],
            $code
        );



    }

    public function dataResponse($data = [], $message = null, $code = 200, $success = true)
    {

        return response()->json(
            array_merge([
                'success' => $success,
                'response_code' => $code,
                'message' => $message ?? 'Request processed successfully !',
            ], $data),
            $code
        );



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
            'line' => $th->getLine(),
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

    public function validationErrorResponse($message = null, $errors = null, $code = 422, $success = false)
    {
        if ($errors == null) {
            return response()->json([
                'success' => $success,
                'response_code' => $code,
                'message' => $message ?? 'Validation error !',


            ], $code);
        }
        return response()->json([
            'success' => $success,
            'response_code' => $code,
            'message' => $message ?? 'Validation error !',
            'errors' => $errors,

        ], $code);
    }

    public function maxlimitExceed($message = null, $code = 429, $success = false)
    {
        return response()->json([
            'success' => $success,
            'response_code' => $code,
            'message' => $message ?? 'max limit exceeded',

        ], $code);
    }

    public function errorResponse($message, $code = 422, $success = false)
    {
        return response()->json([
            'success' => $success,
            'response_code' => $code,
            'message' => $message,

        ], $code);
    }
}