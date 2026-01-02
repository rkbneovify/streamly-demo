<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

trait ActionTrait
{
    public function startAction(callable $callback)
    {
        return new class ($callback) {

            protected $initialCallback;
            protected array $thenCallbacks = [];
            protected $catchCallback = null;
            protected $finallyCallback = null;

            public function __construct(callable $callback)
            {
                $this->initialCallback = $callback;
            }

            public function then(callable $callback): self
            {
                $this->thenCallbacks[] = $callback;
                return $this;
            }

            public function catch(callable $callback): self
            {
                $this->catchCallback = $callback;
                return $this;
            }

            public function finally(callable $callback): self
            {
                $this->finallyCallback = $callback;
                return $this;
            }

            /**
             * Execute chain and RETURN response
             */
            public function execute()
            {
                DB::beginTransaction();

                try {
                    $result = ($this->initialCallback)();

                    foreach ($this->thenCallbacks as $callback) {
                        $result = $callback($result);
                    }

                    DB::commit();

                    // ✅ SUCCESS → return finally response
                    if ($this->finallyCallback) {
                        return ($this->finallyCallback)();
                    }

                    return $result;

                } catch (Throwable $e) {
                    DB::rollBack();

                    if (!config('app.debug')) {
                        Log::error($e);
                        $e = new \Exception('Something went wrong. Please try again.');
                    }

                    // ❌ ERROR → return catch response
                    if ($this->catchCallback) {
                        return ($this->catchCallback)($e);
                    }

                    throw $e;
                }
            }
        };
    }
}
