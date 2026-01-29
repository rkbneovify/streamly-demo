<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;

trait DbTransactionTrait
{
    public function dbTransaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();
            DB::commit();
            return $result;

        } catch (\Throwable $e) {
            DB::rollBack();

            // API request
            if (request()->expectsJson()) {

                $response = [
                    'message' => env('APP_DEBUG')
                        ? $e->getMessage()
                        : 'Something went wrong.',
                ];

                if (env('APP_DEBUG')) {
                    $response['exception'] = class_basename($e);
                    $response['file'] = $e->getFile();
                    $response['line'] = $e->getLine();
                }

                throw new HttpResponseException(
                    response()->json($response, 500)
                );
            }

            // Web request
            if (!env('APP_DEBUG')) {
                abort(500, 'Something went wrong.');
            }

            throw $e; // debug ON → full stack trace
        }
    }
}
