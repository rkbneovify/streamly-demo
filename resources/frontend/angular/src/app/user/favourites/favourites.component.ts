import { NgClass, NgFor, NgIf } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { Router, RouterLink } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { DurationISOFormatPipe, TruncatePipe } from '../../pipe/duration-format.pipe';

@Component({
  selector: 'app-favourites',
  standalone: true,
  imports: [NgFor, NgClass, NgIf, RouterLink,DurationISOFormatPipe,TruncatePipe],
  templateUrl: './favourites.component.html',
  styleUrl: './favourites.component.scss',
})
export class FavouritesComponent implements OnInit {
  favoriteChannels: any;
  isLoading: boolean = false;
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
    this.isLoading = true;
    this.apiService
      .postRequest('auth/addtofavorite', { channelid: channelId })
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels.push(channelId); // Update local favorite list
          this.updateFavoriteChannels(); // Update local storage
          this.getFavoriteListData();
          this.ts.success(res.message);
        }
        this.isLoading = false;
      });
  }
  // Remove channel from favorites
  removeFavoriteChannel(channelId: number): void {
    // Call API to remove channel from favorites
    this.isLoading = true;
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
        this.isLoading = false;
      });
  }
  favListData: any;
  // Fetch favorite list
  getFavoriteList(): void {
    this.isLoading = true;
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
        this.isLoading = false;
      });
    }
  }
  // Update local storage with current favorite channels
  updateFavoriteChannels(): void {
    localStorage.setItem(
      'favoriteChannels',
      JSON.stringify(this.favoriteChannels)
    );
  }

  getFavoriteListData(): void {
    this.isLoading = true;
    const body = { typeurl: 'mpd' };
    this.apiService
      .postRequest('auth/favourite-channels', body)
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favListData = res.data;
        }
        this.isLoading = false;
      });
  }

  recentsPlayData: any;
  getRecentsListData(): void {
    this.isLoading = true;
    // Fetch from API if not found in local storage
    const body = { typeurl: 'mpd' };
    this.apiService
      .postRequest('auth/recent-channels', body)
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.recentsPlayData = res.data;
        }
        this.isLoading = false;
      });
  }
  // set player data in local
  setplayerData(url: string, channel_id?: any) {
    localStorage.setItem('playerId', url);
    localStorage.setItem('playerChannelId', channel_id);
    this.router.navigateByUrl('/user/playstream');
  }


  // current Programs
  currentProgramDetail: boolean = false;
  programChannelId: string;
  programChannelUrl: string;
  currentprogramDetailData:any;

  formatTime(timeStr: any): string {
    const currentDate = new Date();
    const dateString = `${currentDate.getUTCFullYear()}-${(
      currentDate.getUTCMonth() + 1
    )
      .toString()
      .padStart(2, '0')}-${currentDate
      .getUTCDate()
      .toString()
      .padStart(2, '0')} ${timeStr} +0000`;
    const localDate = this.convertTZ(dateString);
    const hours = localDate.getHours();
    const minutes = localDate.getMinutes();
    const formattedDate = new Date();
    formattedDate.setHours(hours, minutes);
    return formattedDate.toLocaleTimeString('en-US', {
      hour: '2-digit',
      minute: '2-digit',
    });
  }

  convertTZ(
    date: any,
    tzString = Intl.DateTimeFormat().resolvedOptions().timeZone
  ): Date {
    return new Date(
      new Date(date).toLocaleString('en-US', { timeZone: tzString })
    );
  }

  getCurrentFormattedDateTime() {
    const now = new Date();
    const year = now.getUTCFullYear();
    const month = String(now.getUTCMonth() + 1).padStart(2, '0');
    const day = String(now.getUTCDate()).padStart(2, '0');
    const hours = String(now.getUTCHours()).padStart(2, '0');
    const minutes = String(now.getUTCMinutes()).padStart(2, '0');
    const seconds = String(now.getUTCSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
  }

  getCurrentProgram(url: string, id: string) {
    this.isLoading = true;
    this.programChannelId = id;
    this.programChannelUrl = url;
    console.log('Url ==>', url);
    console.log('id ==>', id);
    const body = {
      channel_id: id,
      current_datetime: this.getCurrentFormattedDateTime(),
    };
    this.apiService.postRequest('auth/player-details', body).subscribe({
      next: (res: any) => {
        this.currentProgramDetail = !this.currentProgramDetail;
        this.currentprogramDetailData = res.data;
        this.isLoading = false;
      },
      error: (err: any) => {
        console.error(err);
        this.isLoading = false;
      },
    });
  }

}
