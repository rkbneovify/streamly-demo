<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

trait ActionTrait
{
    /**
     * Holds current action (request scoped)
     */
    public static $currentAction = null;

    public function startAction(callable $callback)
    {
        $action = new class {

            public bool $terminated = false;
            public $terminateResponse = null;

            public function terminate($response): void
            {
                $this->terminated = true;
                $this->terminateResponse = $response;
                throw new \RuntimeException('__ACTION_TERMINATED__');
            }
        };

        // expose action
        self::$currentAction = $action;

        return new class ($action, $callback) {

            protected $action;
            protected $initialCallback;
            protected array $thenCallbacks = [];
            protected $catchCallback;
            protected $finallyCallback;
            protected $result;

            public function __construct($action, callable $callback)
            {
                $this->action = $action;
                $this->initialCallback = $callback; // ❌ no bindTo
            }

            public function then(callable $callback): self
            {
                $this->thenCallbacks[] = $callback; // ❌ no bindTo
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

            public function execute()
            {
                DB::beginTransaction();

                try {
                    $this->result = ($this->initialCallback)();

                    foreach ($this->thenCallbacks as $cb) {
                        $this->result = $cb($this->result);
                    }

                    DB::commit();

                    if ($this->finallyCallback) {
                        return ($this->finallyCallback)($this->result);
                    }

                    return $this->result;

                } catch (Throwable $th) {

                    if (
                        $this->action->terminated &&
                        $th->getMessage() === '__ACTION_TERMINATED__'
                    ) {
                        DB::rollBack();
                        return $this->action->terminateResponse;
                    }

                    DB::rollBack();

                    if ($this->catchCallback) {
                        return ($this->catchCallback)($th);
                    }

                    throw $th;

                } finally {
                    ActionTrait::$currentAction = null;
                }
            }
        };
    }

    /**
     * terminate() usable from controller / traits / helpers
     */
    public function terminate($response): void
    {
        if (self::$currentAction) {
            self::$currentAction->terminate($response);
        }

        throw new \RuntimeException('No active action to terminate.');
    }
}
