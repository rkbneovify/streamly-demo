import { Routes } from '@angular/router';
import { LandingPageComponent } from './landing-page/landing-page.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { ResetPasswordComponent } from './reset-password/reset-password.component';
import { ScheduleChartComponent } from './schedule-chart/schedule-chart.component';

export const routes: Routes = [
  {path:'',component:LandingPageComponent},
  {path:'password/reset/:token',component:ResetPasswordComponent},
  {path:'schedule',component:ScheduleChartComponent},
  {path:'auth', loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule)},
  {path:'**',component:PageNotFoundComponent},

];
