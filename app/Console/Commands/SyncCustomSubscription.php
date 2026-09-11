<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncCustomSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom-subscription:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync and update subscription status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptions = Subscription::where('stripe_id', 'Like', '%sub_custom_%')
            ->where('renews_at', '<', Carbon::now())
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->update([
                'custom_status' => 'expired'
            ]);
        }

        // if only subscription current_period_start is null
        // $subscriptions = Subscription::whereHas('user')   // only subscriptions with a related user
        //     ->with('user')      // still eager load user data
        //     ->whereNotNull('custom_status')
        //     ->whereNull('current_period_start')
        //     ->get();

        // foreach ($subscriptions as $subscription) {
        //     $subscription->update([
        //         'current_period_start' => $subscription->created_at
        //     ]);
        // }

        $this->info('Subscription sync completed');

        return Command::SUCCESS;
    }
}
