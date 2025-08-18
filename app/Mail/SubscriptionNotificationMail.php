<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class SubscriptionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Get active prices with product details
        $prices = \Stripe\Price::all([
            'active' => true,
            'expand' => ['data.product']
        ]);
        $plans = collect($prices->data)->map(function ($price) {
            return [
                'name' => $price->product->name,
                'description' => $price->product->description,
                'amount' => number_format($price->unit_amount / 100, 2),
                'interval' => $price->recurring->interval,
                'url' => $this->createCheckoutLink($price->id, $this->user),
            ];
        });


        return $this->subject('Subscription Notification')
            ->view('emails.subscription.notification')
            ->with([
                'user' => $this->user,
                'plans' => $plans,
            ]);
    }

    private function createCheckoutLink($priceId, $user)
    {
        $session = Session::create([
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'customer_email' => $user->email,
            'metadata' => [
                'user_id' => $user->id,

            ],
            'success_url' => url('/subscription/success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/subscription/cancel'),
        ]);

        return $session->url;
    }
}
