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
import { authguardGuard } from '../guards/authguard.guard';
import { SubscriptionExpiredComponent } from './subscription-expired/subscription-expired.component';
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

    ]
  }
];


@NgModule({
  declarations: [],
  imports: [
    CommonModule, RouterModule.forChild(routes)
  ]
})
export class UserModule { }
