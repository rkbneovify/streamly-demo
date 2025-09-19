<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class StripeService
{
    public function getPlans($mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        return $stripe->prices->all([
            'expand' => ['data.product'],
            'limit' => 100,
        ]);
    }

    public function getSubscriptionById(string $subscriptionId, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        try {
            return $stripe->subscriptions->retrieve($subscriptionId, []);
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getAllSubscriptionsById(string $subscriptionId)
    {
        $testSubscription = $this->getSubscriptionById($subscriptionId, 'Testing');
        $liveSubscription = $this->getSubscriptionById($subscriptionId, 'Live');

        return collect([$testSubscription, $liveSubscription])->filter()->values();
    }



    public function getAllPlans()
    {
        $testPlans = $this->getPlans('Testing')->data ?? [];
        $livePlans = $this->getPlans('Live')->data ?? [];
        return collect(array_merge($testPlans, $livePlans));
    }

    public function getPlanMode($planId)
    {
        return $this->getAllPlans()->where('id', $planId)->first()->livemode;

    }


    public function findCustomerById(string $customerId, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        try {
            return $stripe->customers->retrieve($customerId, []);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAllCustomerById(string $customerId)
    {
        $testCustomer = $this->findCustomerById($customerId, 'Testing');
        $liveCustomer = $this->findCustomerById($customerId, 'Live');

        return collect([$testCustomer, $liveCustomer]);
    }


    public function hasSubscription($subscriptionId)
    {
        $subscriptions = $this->getAllSubscriptionsById($subscriptionId);
        if (count($subscriptions) == 0) {
            return "Admin/Custom";
        } else {
            if (count($subscriptions) > 0) {
                if ($subscriptions->last()->livemode) {
                    return "Stripe/Live";

                } else {
                    return "Stripe/Testing";

                }
            }
        }

        return "N/A";
    }
    protected function findCustomers(string $email, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);


        // Stripe API supports filtering customers by email
        $customers = $stripe->customers->all([
            'email' => $email,
            'limit' => 1, // Stripe may return multiple
        ]);



        return collect($customers->data);
    }

    public function findByEmail(string $email)
    {
        $testCustomers = $this->findCustomers($email, 'Testing') ?? [];
        $liveCustomers = $this->findCustomers($email, 'Live') ?? [];

        return $testCustomers->merge($liveCustomers);
    }

    public function isCustomerInLiveMode(string $email)
    {
        $customer = $this->findByEmail($email);
        if (count($customer) == 0) {
            return "N/A";

        }

        if (count($customer) > 0 && $customer->first()->livemode == true) {
            return "Live Mode";

        } else {
            return "Testing Mode";
        }


    }






}


