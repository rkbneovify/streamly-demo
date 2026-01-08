<?php
namespace App\Traits;

use Illuminate\Support\Facades\DB;

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
            throw $e;
        }
    }
}