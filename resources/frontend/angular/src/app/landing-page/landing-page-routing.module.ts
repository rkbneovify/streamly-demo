import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {SubContentComponent} from './sub-content/sub-content.component'
import { HelpAndSupportComponent } from '../components/footerComponents/help-and-support/help-and-support.component';
import { TermConditionComponent } from '../components/footerComponents/term-condition/term-condition.component';
import { PrivacyPolicyComponent } from '../components/footerComponents/privacy-policy/privacy-policy.component';
import { FaqComponent } from '../components/footerComponents/faq/faq.component';
import { ContactUsComponent } from '../components/footerComponents/contact-us/contact-us.component';
import { ReportFormComponent } from '../components/footerComponents/report-form/report-form.component';
import { SupportedDevicesComponent } from '../components/footerComponents/supported-devices/supported-devices.component';

const routes: Routes = [
  {path:'',component:SubContentComponent},
  {path:'help-and-support',component:HelpAndSupportComponent},
  {path:'term-condition',component:TermConditionComponent},
  {path:'privacy-policy',component:PrivacyPolicyComponent},
  {path:'faq',component:FaqComponent},
  {path:'contact-us',component:ContactUsComponent},
  {path:'report',component:ReportFormComponent},
  {path:'supported-devices',component:SupportedDevicesComponent},

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LandingPageRoutingModule { }
