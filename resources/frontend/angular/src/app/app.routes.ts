import { Routes } from '@angular/router';
import { LandingPageComponent } from './landing-page/landing-page.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { ResetPasswordComponent } from './reset-password/reset-password.component';
import { ScheduleChartComponent } from './schedule-chart/schedule-chart.component';
import { PaymentComponent } from './payment/payment.component';
import {  authGuarLoginStatus, authGuarRedirectToUser, authguardGuard } from './guards/authguard.guard';
import { DashjstestComponent } from './dashjstest/dashjstest.component';

export const routes: Routes = [
  {path:'',canActivate:[authGuarRedirectToUser],component:LandingPageComponent,loadChildren:()=>import('./landing-page/landing-page.module').then(m=>m.LandingPageModule)},
  {path:'password/reset/:token',component:ResetPasswordComponent},
  {path:'schedule',component:ScheduleChartComponent},
  {path:'demo',component:DashjstestComponent},
  {path:'payment',component:PaymentComponent},
  {path:'auth',canActivate:[authGuarLoginStatus], loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule)},
  {path:'user', canActivate: [authguardGuard], loadChildren: () => import('./user/user.module').then(m => m.UserModule)},
  {path:'**',component:PageNotFoundComponent},
];
