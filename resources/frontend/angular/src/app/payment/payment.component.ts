import { Component, NgModule, OnInit} from '@angular/core';
import { environment } from '../../environments/environment';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import {loadStripe, StripeCardElement, StripeCardElementOptions } from '@stripe/stripe-js';
import { StripeService } from 'ngx-stripe';



@Component({
  selector: 'app-payment',
  standalone: true,
  imports: [ReactiveFormsModule],
  templateUrl: './payment.component.html',
  styleUrl: './payment.component.scss'
})
export class PaymentComponent implements OnInit {
  stripe!:any;
  stripePromise = loadStripe(environment.stripe.publicKey);
  ngOnInit(): void {
    
}

paymentForm: FormGroup;
card!: StripeCardElement;
cardOptions: StripeCardElementOptions = {
  style: {
    base: {
      iconColor: '#666EE8',
      color: '#31325F',
      fontWeight: '300',
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSize: '18px',
      '::placeholder': {
        color: '#CFD7E0'
      }
    }
  }
};

constructor(private formBuilder: FormBuilder, private stripeService: StripeService) {
  this.paymentForm = this.formBuilder.group({
    name: ['', Validators.required]
  });
}

submitPayment() {
  if (this.paymentForm.valid) {
    this.stripeService.createToken(this.card, { name: this.paymentForm.get('name')?.value })
      .subscribe((result:any) => {
        if (result.token) {
          // Send the token to your server to process the payment
          console.log(result.token.id);
        } else if (result.error) {
          // Display the error to the user
          console.log(result.error.message);
        }
      });
  }
}

}
