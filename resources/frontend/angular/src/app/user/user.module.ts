import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { UserComponent } from './user.component';
import { FavouritesComponent } from './favourites/favourites.component';
import { UserHomeComponent } from './user-home/user-home.component';
import { PlaylistScheduleComponent } from './playlist-schedule/playlist-schedule.component';
import { PlaystreamComponent } from './playstream/playstream.component';
import { DownloadappComponent } from './downloadapp/downloadapp.component';
import { ScheduleDetailComponent } from './playlist-schedule/schedule-detail/schedule-detail.component';
import { AccountsettingsComponent } from './accountsettings/accountsettings.component';
import { TotalaccountComponent } from './totalaccount/totalaccount.component';
import { PlansComponent } from './plans/plans.component';
import { authGuarLoginStatus, authguardGuard } from '../guards/authguard.guard';
import { SubscriptionExpiredComponent } from './subscription-expired/subscription-expired.component';
import { SubPaymentComponent } from './sub-payment/sub-payment.component';
import { AllChannelComponent } from './channels/all-channel/all-channel.component';
import { NewsComponent } from './channels/news/news.component';
import { FamilyComponent } from './channels/family/family.component';
import { ComedyComponent } from './channels/comedy/comedy.component';
import { HelpAndSupportComponent } from '../components/footerComponents/help-and-support/help-and-support.component';
import { TermConditionComponent } from '../components/footerComponents/term-condition/term-condition.component';
import { PrivacyPolicyComponent } from '../components/footerComponents/privacy-policy/privacy-policy.component';
import { FaqComponent } from '../components/footerComponents/faq/faq.component';
import { ContactUsComponent } from '../components/footerComponents/contact-us/contact-us.component';
import { ReportFormComponent } from '../components/footerComponents/report-form/report-form.component';
import { SupportedDevicesComponent } from '../components/footerComponents/supported-devices/supported-devices.component';
import { SearchComponent } from './search/search.component';
import { TimeAgoPipe } from '../pipe/time-ago.pipe';
import { CommonChannelComponent } from './channels/common-channel/common-channel.component';
// import { PlansComponent } from './plans/plans.component';
const routes: Routes = [
  {
    path: '', component: UserComponent, children: [
      { path: '', component: UserHomeComponent },
      { path: 'home', component: UserHomeComponent ,canActivate: [authguardGuard] },
      { path: 'favorite', component: FavouritesComponent,canActivate: [authguardGuard] },
      { path: 'playlistschedule', component: PlaylistScheduleComponent,canActivate: [authguardGuard]  },
      { path: 'plan', component: PlansComponent,canActivate: [authguardGuard]  },
      { path: 'schedule-details', component: ScheduleDetailComponent,canActivate: [authguardGuard]  },
      { path: 'user-settings', component: AccountsettingsComponent,canActivate: [authguardGuard]  },
      { path: 'playstream', component: PlaystreamComponent,canActivate: [authguardGuard]  },
      { path: 'downloadapp', component: DownloadappComponent,canActivate: [authguardGuard]  },
      { path: 'connected-device', component: TotalaccountComponent,canActivate: [authguardGuard]  },
      { path: 'subscription-expired', component: SubscriptionExpiredComponent,canActivate: [authguardGuard]  },
      { path: 'payment-subscription', component: SubPaymentComponent,canActivate: [authguardGuard]  },
      { path: 'channel/all', component: AllChannelComponent },
      { path: 'channel/news', component: NewsComponent },
      { path: 'channel/family', component: FamilyComponent },
      { path: 'channel/comedy', component: ComedyComponent },
      { path: 'channels/:genreName',canActivate:[authGuarLoginStatus], component: CommonChannelComponent },
      {path:'help-and-support',component:HelpAndSupportComponent},
      {path:'term-condition',component:TermConditionComponent},
      {path:'privacy-policy',component:PrivacyPolicyComponent},
      {path:'faq',component:FaqComponent},
      {path:'contact-us',component:ContactUsComponent},
      {path:'report',component:ReportFormComponent},
      {path:'supported-devices',component:SupportedDevicesComponent},
      {path:'search',component:SearchComponent},


      

    ]
  }
];


@NgModule({
  declarations: [],
  imports: [
    CommonModule, 
    RouterModule.forChild(routes),
  ]
})
export class UserModule { }
