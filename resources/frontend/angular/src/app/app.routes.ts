import { Routes } from '@angular/router';
import { LandingPageComponent } from './landing-page/landing-page.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { ResetPasswordComponent } from './reset-password/reset-password.component';
import { ScheduleChartComponent } from './schedule-chart/schedule-chart.component';
import { PlansComponent } from './plans/plans.component';
import { DashjstestComponent } from './dashjstest/dashjstest.component';
import { PaymentTestingComponent } from './payment-testing/payment-testing.component';

export const routes: Routes = [
  {path:'',component:LandingPageComponent},
  {path:'password/reset/:token',component:ResetPasswordComponent},
  {path:'schedule',component:ScheduleChartComponent},
  {path:'plans',component:PlansComponent},
  {path:'payment',component:PaymentTestingComponent},
  {path:'auth', loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule)},
  {path:'user', loadChildren: () => import('./user/user.module').then(m => m.UserModule)},
  {path:'**',component:PageNotFoundComponent},

];
