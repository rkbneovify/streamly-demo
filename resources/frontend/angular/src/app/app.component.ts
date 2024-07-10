import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { loadStripe } from '@stripe/stripe-js';
import { NgxStripeModule } from 'ngx-stripe';
import { environment } from '../environments/environment';
import { BrowserModule } from '@angular/platform-browser';
// import { LoaderComponent } from "./components/others/loader/loader.component";


@Component({
    selector: 'app-root',
    standalone: true,
    templateUrl: './app.component.html',
    styleUrl: './app.component.css',
    imports: [RouterOutlet, HttpClientModule]
})
export class AppComponent {
  title = 'angular';
  sripePromise = loadStripe(environment.stripe.publicKey);
}