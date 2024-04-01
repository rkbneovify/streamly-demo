// import { Component, NgModule, OnInit, ViewChild } from '@angular/core';
// import { StripeCardNumberComponent, StripeService } from 'ngx-stripe';
// import {
//   StripeCardElementOptions,
//   StripeElementsOptions,
//   PaymentIntent,
// } from '@stripe/stripe-js';
// import { FormBuilder, FormGroup, FormsModule, NgForm, ReactiveFormsModule, Validators } from '@angular/forms';
// import { HttpClient } from '@angular/common/http';
// import { Observable, switchMap } from 'rxjs';
// import { CommonModule } from '@angular/common';

// @Component({
//   selector: 'app-payment-testing',
//   standalone: true,
//   imports: [ReactiveFormsModule,FormsModule,CommonModule],
//   templateUrl: './payment-testing.component.html',
//   styleUrl: './payment-testing.component.scss'
// })
// export class PaymentTestingComponent implements OnInit{
//   paymentForm!: FormGroup;
//   constructor(private http: HttpClient,
//     private fb: FormBuilder,
//     private stripeService: StripeService) {

//        this.paymentForm = this.fb.group({
//         name: ['John', [Validators.required]],
//         email: ['john@gmail.com', [Validators.required]],
//         amount: [100, [Validators.required, Validators.pattern(/d+/)]],
//     });
//     }

//   @ViewChild(StripeCardNumberComponent) card!: StripeCardNumberComponent;

//   public cardOptions: StripeCardElementOptions = {
//       style: {
//         base: {
//           fontWeight: 400,
//           fontFamily: 'Circular',
//           fontSize: '14px',
//           iconColor: '#666EE8',
//           color: '#002333',
//           '::placeholder': {
//             color: '#919191',
//           },
//         },
//       },
//     };
  
//   public elementsOptions: StripeElementsOptions = {
//       locale: 'en',
//     };
  
 
  
//     // this.loginFormWithEmail = this.fb.group({
//     //   email: ['', [Validators.required, Validators.email]],
//     //   password: ['', [Validators.required, Validators.minLength(6)]],
//     // });

  
//     ngOnInit() {}
  
//     pay(): void {
//       if (this.paymentForm.valid) {
//         this.createPaymentIntent(this.paymentForm.get('amount')?.value)
//           .pipe(
//             switchMap((pi:any) =>
//               this.stripeService.confirmCardPayment(pi.client_secret, {
//                 payment_method: {
//                   card: this.card.element,
//                   billing_details: {
//                     name: this.paymentForm.get('name')?.value,
//                   },
//                 },
//               })
//             )
//           )
//           .subscribe((result) => {
//             if (result.error) {
//               // Show error to your customer (e.g., insufficient funds)
//               console.log(result.error.message);
//             } else {
//               // The payment has been processed!
//               if (result.paymentIntent.status === 'succeeded') {
//                 // Show a success message to your customer
//               }
//             }
//           });
//       } else {
//         console.log(this.paymentForm);
//       }
//     }
  
//     createPaymentIntent(amount: number): Observable<PaymentIntent> {
//       return this.http.post<PaymentIntent>(
//         `$/create-payment-intent`,
//         { amount }
//       );
//     }

// }




import { CommonModule } from '@angular/common';
import { HttpClient, HttpResponse } from '@angular/common/http';
import {Component, OnDestroy, OnInit} from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { StripeFactoryService, StripeInstance } from 'ngx-stripe';
import { Subscription, switchMap } from 'rxjs';

@Component({
  selector: 'app-payment-testing',
  standalone: true,
  imports: [ReactiveFormsModule,FormsModule,CommonModule],
  templateUrl: './payment-testing.component.html',
  styleUrl: './payment-testing.component.scss'
})
export class PaymentTestingComponent implements OnInit{
  public stripe!: StripeInstance;
  public stripeAmount!: number;
  public stripePublicKey = 'pk_test_51OzBloSA7waI3emKqFtc5EwG8do6KT1gbLAgAy3x4tpoRCjscvMnAf5UHsLpBMAJPfKVw8U0XpRCV2De6fonkom600TWicawcL';

  private subscriptions: Subscription;

  constructor(
      private http: HttpClient,
      private stripeFactory: StripeFactoryService,
  ) {
    this.subscriptions = new Subscription();
  }

  ngOnDestroy(): void {
    this.subscriptions.unsubscribe();
  }

  ngOnInit(): void {
    // dynamically load the stripe script
    this.stripe = this.stripeFactory.create(this.stripePublicKey);
    // stripe amount is in cents, you should multiply your amount by 100
    this.stripeAmount = 100;
  }

  checkout() {
    const host = 'http://localhost:3000';
    const checkout: Subscription = this.http.post(host + '/create-checkout-session', {data: {amount: this.stripeAmount * 100}}, {observe: 'response'})
        .pipe(
            switchMap((response: HttpResponse<Object>) => {
              const session: IStripeSession = response.body as IStripeSession;
              return this.stripe.redirectToCheckout({ sessionId: session.id });
            })
        )
        .subscribe(result => {
          // If `redirectToCheckout` fails due to a browser or network
          if (result.error) {
            console.log(result.error)
          }
        });
    this.subscriptions.add(checkout);
  }
}

interface IStripeSession {
  id: string;
}
