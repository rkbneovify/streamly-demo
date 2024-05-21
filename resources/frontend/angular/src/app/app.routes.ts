import { Routes } from '@angular/router';
import { LandingPageComponent } from './landing-page/landing-page.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { ResetPasswordComponent } from './reset-password/reset-password.component';
import { ScheduleChartComponent } from './schedule-chart/schedule-chart.component';
import { PaymentComponent } from './payment/payment.component';
import { authguardGuard } from './guards/authguard.guard';
import { HelpAndSupportComponent } from './help-and-support/help-and-support.component';
import { TermConditionComponent } from './term-condition/term-condition.component';
import { PrivacyPolicyComponent } from './privacy-policy/privacy-policy.component';
import { FaqComponent } from './faq/faq.component';
import { ContactUsComponent } from './contact-us/contact-us.component';
import { ReportFormComponent } from './report-form/report-form.component';
import { SupportedDevicesComponent } from './supported-devices/supported-devices.component';

export const routes: Routes = [
  {path:'',component:LandingPageComponent},
  {path:'password/reset/:token',component:ResetPasswordComponent},
  {path:'schedule',component:ScheduleChartComponent},
  {path:'payment',component:PaymentComponent},
  {path:'auth', loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule)},
  {path:'user', canActivate: [authguardGuard], loadChildren: () => import('./user/user.module').then(m => m.UserModule)},
  {path:'help-and-support',component:HelpAndSupportComponent},
  {path:'term-condition',component:TermConditionComponent},
  {path:'privacy-policy',component:PrivacyPolicyComponent},
  {path:'faq',component:FaqComponent},
  {path:'contact-us',component:ContactUsComponent},
  {path:'report',component:ReportFormComponent},
  {path:'supported-devices',component:SupportedDevicesComponent},

  


  
  {path:'**',component:PageNotFoundComponent},

];
