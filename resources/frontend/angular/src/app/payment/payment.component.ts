import { HttpClient } from '@angular/common/http';
import { ChangeDetectorRef, Component, ElementRef, ViewChild } from '@angular/core';
import { FormsModule, NgForm } from '@angular/forms';
import { AngularStripeService } from '@fireflysemantics/angular-stripe-service'
declare var Stripe: any;
@Component({
  selector: 'app-payment',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './payment.component.html',
  styleUrl: './payment.component.scss'
})
export class PaymentComponent {
  
  @ViewChild('cardInfo', { static: false }) cardInfo!: ElementRef;

  stripe:any;
  loading = false;
  confirmation:any;

  card: any;
  cardHandler = this.onChange.bind(this);
  error:any;
  // stripe = Stripe('pk_test_51BTUDGJAJfZb9HEBwDg86TN1KNprHjkfipXmEDMb0gSCassK5T3ZfxsAbcgKVmAIXF7oZ6ItlZZbXO6idTHE67IM007EwQ4uN3');
  // sessionId: any;
  constructor(private cd: ChangeDetectorRef, private stripeService: AngularStripeService, private http: HttpClient) {
    // this.initialize();

  }


  // async initiateCheckout() {
  //   const id  = await this.http.post('/create-checkout-session', {})
  //   this.sessionId = id;
  //   console.log(this.sessionId,'session id');
    
  //   this.stripe.redirectToCheckout({ sessionId: this.sessionId });
  // }
  // async initialize() {
  //   const fetchClientSecret = async () => {
  //     const response = await fetch("/create-checkout-session", {
  //       method: "POST",
  //     });
  //     const { clientSecret } = await response.json();
  //     return clientSecret;
  //   };

  //   // Initialize Checkout
  //   const checkout = await this.stripe.initEmbeddedCheckout({
  //     fetchClientSecret,
  //   });

  //   // Mount Checkout
  //   checkout.mount('#checkout');
  // }















  
  ngAfterViewInit() {
    this.stripeService.setPublishableKey('pk_test_51OzBloSA7waI3emKqFtc5EwG8do6KT1gbLAgAy3x4tpoRCjscvMnAf5UHsLpBMAJPfKVw8U0XpRCV2De6fonkom600TWicawcL').then(
      (stripe:any)=> {
        this.stripe = stripe;
    const elements = stripe.elements();    
    this.card = elements.create('card');
    this.card.mount(this.cardInfo.nativeElement);
    this.card.addEventListener('change', this.cardHandler);
    });
  }

  ngOnDestroy() {
    this.card.removeEventListener('change', this.cardHandler);
    this.card.destroy();
  }

  onChange( error :any) {
    if (error) {
      this.error = error.message;
    } else {
      this.error = null;
    }
    this.cd.detectChanges();
  }

  async onSubmit(form: NgForm) {
    const { token, error } = await this.stripe.createToken(this.card);

    if (error) {
      console.log('Error:', error);
    } else {
      console.log('Success!', token);
    }
  }
}
