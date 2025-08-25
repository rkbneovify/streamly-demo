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
            'limit' => 20,
        ]);
    }

    public function getAllPlans()
    {
        $testPlans = $this->getPlans('Testing')->data ?? [];
        $livePlans = $this->getPlans('Live')->data ?? [];
        return collect(array_merge($testPlans, $livePlans));
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



}


