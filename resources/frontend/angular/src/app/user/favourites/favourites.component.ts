import { NgClass, NgFor, NgIf } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-favourites',
  standalone: true,
  imports: [NgFor, NgClass, NgIf],
  templateUrl: './favourites.component.html',
  styleUrl: './favourites.component.scss',
})
export class FavouritesComponent implements OnInit {
  favoriteChannels: any;
  constructor(
    private apiService: CrudService,
    private router: Router,
    private ts: ToastrService
  ) {}
  ngOnInit(): void {
    this.getFavoriteListData();
    this.getRecentsListData();
    this.getFavoriteList();
  }

  // Check if a channel is marked as favorite
  isFavorite(channelId: number): boolean {
    return this.favoriteChannels.includes(channelId);
  }
  // Toggle favorite status
  toggleFavorite(channelId: number): void {
    // If channel is already favorite, remove it
    if (this.isFavorite(channelId)) {
      this.removeFavoriteChannel(channelId);
    } else {
      // add it to favorites
      this.addFavoriteChannel(channelId);
    }
  }
  // Add channel to favorites
  addFavoriteChannel(channelId: number): void {
    // Call API to add channel to favorites
    this.apiService
      .postRequest('auth/addtofavorite', { channelid: channelId })
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels.push(channelId); // Update local favorite list
          this.updateFavoriteChannels(); // Update local storage
          this.getFavoriteListData();
          this.ts.success(res.message);
        }
      });
  }
  // Remove channel from favorites
  removeFavoriteChannel(channelId: number): void {
    // Call API to remove channel from favorites
    this.apiService
      .postRequest('auth/unfavorite', { channelid: channelId })
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels = this.favoriteChannels.filter(
            (id) => id !== channelId
          ); // Update local favorite list
          this.getFavoriteListData();

          this.updateFavoriteChannels(); // Update local storage
          this.ts.success(res.message);
        }
      });
  }
  favListData: any;
  // Fetch favorite list
  getFavoriteList(): void {
    console.log('==========================================>');
    const storedFavoriteChannels = localStorage.getItem('favoriteChannels');
    if (storedFavoriteChannels) {
      this.favoriteChannels = JSON.parse(storedFavoriteChannels);
    } else {
      // Fetch from API if not found in local storage
      this.apiService.getRequest('auth/my_favorites').subscribe((res: any) => {
        if (res.response_code === 200) {
          // this.favListData = res;
          this.favoriteChannels = res.data.map((channel: any) => channel.id);
          this.updateFavoriteChannels(); // Update local storage
        }
      });
    }
  }
  // Update local storage with current favorite channels
  updateFavoriteChannels(): void {
    console.log('favoriteChannels', this.favoriteChannels);
    localStorage.setItem(
      'favoriteChannels',
      JSON.stringify(this.favoriteChannels)
    );
  }

  getFavoriteListData(): void {
    const body = {"typeurl" : "mpd"}
    this.apiService.postRequest('auth/favourite-channels',body).subscribe((res: any) => {
      if (res.response_code === 200) {
        this.favListData = res.data;
      }
    });
  }
  
  recentsPlayData: any;
  getRecentsListData(): void {
    // Fetch from API if not found in local storage
    const body = {"typeurl" : "mpd"}
    this.apiService.postRequest('auth/recent-channels',body).subscribe((res: any) => {
      if (res.response_code === 200) {
        this.recentsPlayData = res.data;
      }
    });
  }
  // set player data in local
  setplayerData(url: string,channel_id?:any) {
    localStorage.setItem('playerId', url);
    localStorage.setItem('playerChannelId',channel_id);
    this.router.navigateByUrl('/user/playstream');
  }
}
