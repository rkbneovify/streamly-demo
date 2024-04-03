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
const routes: Routes = [
  {
    path: '', component: UserComponent, children: [
      { path: '', component: UserHomeComponent },
      { path: 'home', component: UserHomeComponent },
      { path: 'favorite', component: FavouritesComponent },
      { path: 'playlistschedule', component: PlaylistScheduleComponent },
      { path: 'schedule-details', component: ScheduleDetailComponent },
      { path: 'user-settings', component: AccountsettingsComponent },
      { path: 'playstream', component: PlaystreamComponent },
      { path: 'downloadapp', component: DownloadappComponent },
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
