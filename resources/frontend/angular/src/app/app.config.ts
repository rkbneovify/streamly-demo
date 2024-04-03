import { ApplicationConfig, importProvidersFrom } from '@angular/core';
import { provideRouter } from '@angular/router';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ToastrModule } from 'ngx-toastr';

import { routes } from './app.routes';
import { provideClientHydration } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { NgSelectModule } from '@ng-select/ng-select';
export const appConfig: ApplicationConfig = {
  providers: [provideRouter(routes), provideClientHydration(),importProvidersFrom(HttpClientModule,NgSelectModule ,BrowserAnimationsModule,
  ToastrModule.forRoot({
    timeOut: 10000,
    positionClass: 'toast-top-right',
    preventDuplicates: true,
  }),)]
};
