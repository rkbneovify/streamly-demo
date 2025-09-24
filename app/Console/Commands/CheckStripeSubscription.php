<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckStripeSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if subscription exist in Stripe one by one';

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
        $stripeService = new StripeService();

        Subscription::whereHas('user')
            ->whereNull('livemode')
            ->latest()->chunk(50, function ($subscriptions) use ($stripeService) {
                foreach ($subscriptions as $subscription) {
                    try {

                        if ($subscription->user->stripe_id != null) {
                            $customer = $stripeService->getAllCustomerById($subscription->user->stripe_id);

                            if ($customer && count($customer) > 0) {
                                $lastCustomer = $customer->last();

                                if (isset($lastCustomer->livemode) && $lastCustomer->livemode) {
                                    $subscription->update(['livemode' => 1]);
                                } else {
                                    $subscription->update(['livemode' => 0]);
                                }
                            }
                        }


                        // usleep(200000); // 0.2 second delay to avoid rate limits
                    } catch (\Exception $e) {
                        Log::error("Stripe subscription check failed for ID {$subscription->id}: {$e->getMessage()}");
                    }
                }
            });

        $this->info('stripe:check-subscriptions Command run successfully!');
        return 0;
    }


}
