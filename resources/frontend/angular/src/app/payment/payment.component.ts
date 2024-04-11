import { Component, NgModule, OnInit } from '@angular/core';
import { environment } from '../../environments/environment';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { loadStripe, Stripe, StripeCardElement, StripeCardElementOptions } from '@stripe/stripe-js';
import { StripeService } from 'ngx-stripe';
import { NgIf } from '@angular/common';
import { StripeCard } from 'stripe-angular';
import { promises } from 'dns';
interface StripeServerCheckoutOptions {
  lineItems: Array<{ price: string; quantity: number }>;
  mode: 'payment' | 'subscription';
  successUrl?: string;
  cancelUrl?: string;
}

@Component({
  selector: 'app-payment',
  standalone: true,
  imports: [ReactiveFormsModule, NgIf],
  templateUrl: './payment.component.html',
  styleUrl: './payment.component.scss'
})
export class PaymentComponent implements OnInit {
  // stripe: any = environment.stripe.publicKey;
  stripe:any;
  options!: StripeServerCheckoutOptions
  constructor() { }
  ngOnInit(): void {

    this.stripe = Stripe(
      "pk_test_51P40xLSImeftE9DrP1msVHBgpXZTDyUgtjx080RIKlV2gG3QLEl85znHUa08cV2OhIc5mNrxdD0PZCdFyCfhOvxq00ufEqblZ5"
    );
  }

  // var checkoutButton = document.getElementById("checkout");
  // var checkoutSamosaButton = document.getElementById("checkout-samosa");

  stripePayment() {
    this.stripe.redirectToCheckout({
      lineItems: [{ price: 'price_1P45HASImeftE9Dr4qtyZHQ4', quantity: 1 }],
      mode: 'subscription',
      successUrl: "http://localhost:4200/payment?success=true",
      cancelUrl: "http://localhost:4200/payment?failure=false",
    }).then(function (result: any) {

      if (result.error) {
        console.error(result.error.message);
      }
    });
    // this.stripe.redirectToCheckout({
    //     lineItems: [{ price: item, quantity: 1 }],
    //     mode: 'subscription',
    //     successUrl: "https://example.com/success",
    //     cancelUrl: "https://example.com/canceled",
    //   }).then(function (result) {
    //     if (result.error) {
    //       var displayError = document.getElementById("error-message");
    //       displayError.textContent = result.error.message;
    //     }
    //   });
  };

//   checkoutSamosaButton.addEventListener(
//     "click",
//     stripePayment("price_1P43taSImeftE9DrP7j8zpcR", "subscription")
//   );
// checkoutButton.addEventListener(
//   "click",
//   stripePayment("price_1P41IFSImeftE9DrgZQaFubC")
// );
}
