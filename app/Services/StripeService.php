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


    }
    public function isLiveSubscription($subscriptionId)
    {
        $subscriptions = $this->getAllSubscriptionsById($subscriptionId);
        if (count($subscriptions) == 0) {
            return false;
        } else {
            if (count($subscriptions) > 0) {
                if ($subscriptions->last()->livemode) {
                    return true;

                } else {
                    return false;

                }
            }
        }


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


    public function getInvoiceByCustomerId(string $customerId, string $mode = 'Live')
    {
        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);

        try {
            $invoices = $stripe->invoices->all([
                'customer' => $customerId,
                'limit' => 3,
            ]);
            return $invoices->data;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAllInvoiceByCustomerId(string $customerId, $mode = 'Live')
    {

        $key = DB::table('Stripe_keys')->where('type', $mode)->value('stripe_secret');
        $stripe = new StripeClient($key);
        $statuses = ['draft', 'open', 'paid', 'void', 'uncollectible'];

        $allInvoices = collect();

        foreach ($statuses as $status) {
            $invoices = $stripe->invoices->all([
                'customer' => $customerId,
                'status' => $status,
                'limit' => 100, // get more from Stripe so we can paginate locally
            ]);

            $allInvoices = $allInvoices->merge($invoices->data);
        }


        return $allInvoices->sortByDesc('created')->values();
    }








}


