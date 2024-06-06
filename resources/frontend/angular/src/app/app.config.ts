import { ApplicationConfig, importProvidersFrom } from '@angular/core';
import { provideRouter } from '@angular/router';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ToastrModule } from 'ngx-toastr';
import { NgxStripeModule, provideNgxStripe } from 'ngx-stripe';
import { environment } from '../environments/environment';
import { StripeModule } from 'stripe-angular';

import { routes } from './app.routes';
import { BrowserModule, provideClientHydration } from '@angular/platform-browser';
import { HttpClientModule, provideHttpClient, withInterceptors } from '@angular/common/http';
import { NgSelectModule } from '@ng-select/ng-select';
import { authInterceptor } from './_interceptor/auth.interceptor';
export const appConfig: ApplicationConfig = {
  providers: [
    provideHttpClient(withInterceptors([authInterceptor])),
    provideRouter(routes),
    provideClientHydration(),
    provideNgxStripe(),
    importProvidersFrom(
      HttpClientModule,
      NgSelectModule,
      NgxStripeModule.forRoot(''),
      StripeModule.forRoot(environment.stripe.publicKey),      
      BrowserAnimationsModule,
      BrowserModule,
      ToastrModule.forRoot({
        timeOut: 10000,
        positionClass: 'toast-top-right',
        preventDuplicates: true,
      })
    ),
  ],
};
