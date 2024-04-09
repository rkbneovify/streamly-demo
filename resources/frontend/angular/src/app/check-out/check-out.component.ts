import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { switchMap } from 'rxjs/operators';
// import { StripeService } from '@stripe/stripe-js';

@Component({
  selector: 'app-check-out',
  standalone: true,
  imports: [],
  templateUrl: './check-out.component.html',
  styleUrl: './check-out.component.css'
})
export class CheckOutComponent {
  // constructor(private http: HttpClient, private stripeService: StripeService) {}

  // checkout() {
  //   this.http.post('/create-checkout-session', {})
  //     .pipe(
  //       switchMap(session => {
  //         return this.stripeService.redirectToCheckout({ sessionId: session.id });
  //       })
  //     )
  //     .subscribe(result => {
  //       if (result.error) {
  //         alert(result.error.message);
  //       }
  //     });
  // }
}
