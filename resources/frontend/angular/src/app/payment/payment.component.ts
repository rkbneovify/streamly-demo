import { Component, NgModule, OnInit} from '@angular/core';
import { environment } from '../../environments/environment';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import {loadStripe, Stripe,StripeCardElement, StripeCardElementOptions } from '@stripe/stripe-js';
import { StripeService } from 'ngx-stripe';
import { NgIf } from '@angular/common';
import { StripeCard } from 'stripe-angular';
import { promises } from 'dns';

@Component({
  selector: 'app-payment',
  standalone: true,
  imports: [ReactiveFormsModule,NgIf],
  templateUrl: './payment.component.html',
  styleUrl: './payment.component.scss'
})
// export class PaymentComponent implements OnInit {
//   stripe!:any;
//   stripePromise = loadStripe(environment.stripe.publicKey);
//   ngOnInit(): void {
    
// }

// paymentForm: FormGroup;
// card!: StripeCardElement;
// cardOptions: StripeCardElementOptions = {
//   style: {
//     base: {
//       iconColor: '#666EE8',
//       color: '#31325F',
//       fontWeight: '300',
//       fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
//       fontSize: '18px',
//       '::placeholder': {
//         color: '#CFD7E0'
//       }
//     }
//   }
// };

// constructor(private formBuilder: FormBuilder, private stripeService: StripeService) {
//   this.paymentForm = this.formBuilder.group({
//     name: ['', Validators.required]
//   });
// }

// submitPayment() {
//   if (this.paymentForm.valid) {
//     this.stripeService.createToken(this.card, { name: this.paymentForm.get('name')?.value })
//       .subscribe((result:any) => {
//         if (result.token) {
//           // Send the token to your server to process the payment
//           console.log(result.token.id);
//         } else if (result.error) {
//           // Display the error to the user
//           console.log(result.error.message);
//         }
//       });
//   }
// }

// }

// export class PaymentComponent implements OnInit{
//   ngOnInit(): void {
    
//   }

  
//   cardCaptureReady = false

//   onStripeInvalid( error: Error ){
//     console.log('Validation Error', error)
//   }

//   onStripeError( error: Error ){
//     console.error('Stripe error', error)
//   }

//   setPaymentMethod( token: stripe.paymentMethod.PaymentMethod ){
//     console.log('Stripe Payment Method', token)
//   }

//   setStripeToken( token: stripe.Token ){
//     console.log('Stripe Token', token)
//   }

//   setStripeSource( source: stripe.Source ){
//     console.log('Stripe Source', source)
//   }
// }


// 1st 
// export class PaymentComponent implements OnInit{

//   stripe!:any;
  
//   constructor(){}

//   async ngOnInit() {
//     this.stripe = await loadStripe(environment.stripe.publicKey);
//     console.log(this.stripe);

//     const el = this.stripe?.elements();

//     const card = el.create('card');

//     card.mount('#card');

//     card.on('change',(ev:any)=>{
//       const displayError:any = document.getElementById('card-errors');
//       ev.error ? displayError.textContent = ev.error.message: displayError.textContent = '';
//     });

//     const button = document.getElementById("button");
//     button?.addEventListener('click',async (event)=>{
//       event.preventDefault();
//       const ownerInfo = {
//         owner:{name:'Deepak'},
//         // amount:1000,
//         // currency:'USD'
//       }

//       try {
//         const result:any = await this.stripe.createPaymentMethod({type:'card',card,'billing_details':{name:'Deepak'}});
//         console.log(result);
//       } catch (error) {
//         console.log(error);
//       }
//     });
//   }
// }

// 2nd

// export class PaymentComponent implements OnInit{
  
//   ngOnInit(): void {
    
//   }
//   paymentForm!: FormGroup;
//   card!: StripeCardElement;
//   cardOptions: StripeCardElementOptions = {
//     style: {
//       base: {
//         iconColor: '#666EE8',
//         color: '#31325F',
//         fontWeight: '300',
//         fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
//         fontSize: '18px',
//         '::placeholder': {
//           color: '#CFD7E0'
//         }
//       }
//     }
//   };

//   constructor(private formBuilder: FormBuilder, private stripeService: StripeService) {
//     this.paymentForm = this.formBuilder.group({
//       name: ['', Validators.required]
//     });
//   }

//   submitPayment() {
//     if (this.paymentForm.valid) {
//       this.stripeService.createToken(this.card, { name: this.paymentForm.get('name')?.value })
//         .subscribe(result => {
//           if (result.token) {
//             // Send the token to your server to process the payment
//             console.log(result.token.id);
//           } else if (result.error) {
//             // Display the error to the user
//             console.log(result.error.message);
//           }
//         });
//     }
//   }
// }


// 3rd
export class PaymentComponent implements OnInit{
  paymentHandler: any = null;
  constructor() {}
  ngOnInit() {
    this.invokeStripe();
  }
  makePayment(amount: any) {
    const paymentHandler = (<any>window).StripeCheckout.configure({
      key: environment.stripe.publicKey,
      locale: 'auto',
      token: function (stripeToken: any) {
        console.log(stripeToken);
        alert('Stripe token generated!');
      },
    });
    paymentHandler.open({
      name: 'Positronx',
      description: '3 widgets',
      amount: amount * 100,
    });
  }
  invokeStripe() {
    if (!window.document.getElementById('stripe-script')) {
      const script = window.document.createElement('script');
      script.id = 'stripe-script';
      script.type = 'text/javascript';
      script.src = 'https://checkout.stripe.com/checkout.js';
      script.onload = () => {
        this.paymentHandler = (<any>window).StripeCheckout.configure({
          key: environment.stripe.publicKey,
          locale: 'auto',
          token: function (stripeToken: any) {
            console.log(stripeToken);
            alert('Payment has been successfull!');
          },
        });
      };
      window.document.body.appendChild(script);
    }
  }
}