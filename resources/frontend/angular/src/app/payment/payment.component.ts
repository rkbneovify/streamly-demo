import { ChangeDetectorRef, Component, ElementRef, NgModule, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { NgIf } from '@angular/common';
// interface StripeServerCheckoutOptions {
//   lineItems: Array<{ price: string; quantity: number }>;
//   mode: 'payment' | 'subscription';
//   successUrl?: string;
//   cancelUrl?: string;
// }
declare var $: any;
declare var stripe: any;
declare var elements: any

@Component({
  selector: 'app-payment',
  standalone: true,
  imports: [ReactiveFormsModule, NgIf, FormsModule],
  templateUrl: './payment.component.html',
  styleUrl: './payment.component.scss'
})
export class PaymentComponent implements OnInit {

  @ViewChild('cardInfo') public cardInfo!: ElementRef;
  _totalAmount: number = 10;
  card: any;
  cardHandler = this.onChange.bind(this);
  cardError: any;

  constructor(private cd: ChangeDetectorRef,) { }

  ngOnInit() { }
  ngOnDestroy() {
    if (this.card) {
      this.card.removeEventListener('change', this.cardHandler);
      this.card.destroy();
    }
  }
  ngAfterViewInit() {
    this.initiateCardElement();
  }
  initiateCardElement() {
    const cardStyle = {
      base: {
        color: '#32325d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4',
        },
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a',
      },
    };
    this.card = elements.create('card', { cardStyle });
    this.card.mount(this.cardInfo.nativeElement);
    this.card.addEventListener('change', this.cardHandler);
  }
  onChange(error: any) {
    if (error) {
      this.cardError = error.message;
    } else {
      this.cardError = null;
    }
    this.cd.detectChanges();
  }
  async createStripeToken() {
    debugger

    console.log(this.card, 'card');

    const { token, error } = await stripe.createToken(this.card);
    console.log(token, 'token');

    if (token) {
      this.onSuccess(token);
    } else {
      this.onError(error);
    }
  }
  onSuccess(token: any) {
  }
  onError(error: any) {
    if (error.message) {
      this.cardError = error.message;
    }
  }
}







// export class PaymentComponent implements OnInit {
//   stripe:any;
//   options!: StripeServerCheckoutOptions
//   constructor() { }
//   ngOnInit(): void {
//     this.stripe = Stripe(
//       "pk_test_51P40xLSImeftE9DrP1msVHBgpXZTDyUgtjx080RIKlV2gG3QLEl85znHUa08cV2OhIc5mNrxdD0PZCdFyCfhOvxq00ufEqblZ5"
//     );
//   }
//   stripePayment() {
//     this.stripe.redirectToCheckout({
//       lineItems: [{ price: 'price_1P45HASImeftE9Dr4qtyZHQ4', quantity: 1 }],
//       mode: 'subscription',
//       successUrl: "http://localhost:4200/payment?success=true",
//       cancelUrl: "http://localhost:4200/payment?failure=false",
//     }).then(function (result: any) {

//       if (result.error) {
//         console.error(result.error.message);
//       }
//     });
//   };
// }
