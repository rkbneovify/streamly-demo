import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { CrudService } from '../../../services/crud.service';
import { NgClass, NgFor, NgIf } from '@angular/common';
import { HttpHeaders } from '@angular/common/http';
import { ToastrService } from 'ngx-toastr';
import { DurationISOFormatPipe, TruncatePipe } from '../../../pipe/duration-format.pipe';

@Component({
  selector: 'app-common-channel',
  standalone: true,
  imports: [NgClass, NgFor, NgIf,DurationISOFormatPipe,TruncatePipe],
  templateUrl: './common-channel.component.html',
  styleUrl: './common-channel.component.scss',
})
export class CommonChannelComponent implements OnInit {
  keyword: string;
  isLoading: boolean = false;
  body: any = {
    typeurl: 'mpd',
    zipcode: '',
  };
  data: any;
  Bearer_token: any;
  favChannelList: any;
  favoriteChannels: number[] = [];
  constructor(
    private router: Router,
    private route: ActivatedRoute,
    private apiService: CrudService,
    private ts: ToastrService
  ) {}

  ngOnInit(): void {
    this.getChannelData();
  }

  getChannelData() {
    this.isLoading = true;
    this.route.url.subscribe((urlSegments) => {
      this.keyword = urlSegments[urlSegments.length - 1].path;

      if (this.keyword == 'recents' || this.keyword == 'favourites') {
        if (this.keyword == 'favourites') {
          this.body
          this.apiService
            .postRequest('auth/favourite-channels', { typeurl: 'mpd' })
            .subscribe((res: any) => {
              if (res.response_code === 200) {
                console.log('fav ===>', res.data);
                this.data = this.chunkArray(res.data, 6);
                this.isLoading = false;
                this.getFavoriteList();
                return;
              }
            });
        }

        if (this.keyword == 'recents') {
          const body = { typeurl: 'mpd' };
          this.apiService
            .postRequest('auth/recent-channels', body)
            .subscribe((res: any) => {
              if (res.response_code === 200) {
                this.data = this.chunkArray(res.data, 6);
                this.isLoading = false;
                this.getFavoriteList();
              }
              return;
            });
        }
      } else {
        if (this.keyword) {
          const body = {
            typeurl: 'mpd',
            genre: this.keyword,
          };
          this.apiService.filterChannelResponse(body).subscribe({
            next: (res: any) => {
              this.data = this.chunkArray(res.data, 6);
              this.getFavoriteList();
              this.isLoading = false;
            },
            error:(err)=>{
              this.isLoading = false;
              this.ts.error(err.error.message)
            }
          });
        }
        console.log(this.keyword);
      }
    });
  }

  getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(
      localStorage.getItem('deviceInfo') || ''
    ).access_token;
    // this.Bearer_token = tokenData;
    return new HttpHeaders({
      Authorization: `Bearer ${tokenData}`,
      Accept: 'application/json',
    });
  }

  //
  chunkArray(array: any, chunkSize: any): any {
    const result: any = [];
    for (let i = 0; i < array.length; i += chunkSize) {
      result.push(array.slice(i, i + chunkSize));
    }
    return result;
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
    this.isLoading = true;
    // Call API to add channel to favorites
    this.apiService
      .postRequest(
        'auth/addtofavorite',
        { channelid: channelId },
        this.getAuthHeaders()
      )
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels.push(channelId); // Update local favorite list
          this.updateFavoriteChannels(); // Update local storage
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
      .postRequest(
        'auth/unfavorite',
        { channelid: channelId },
        this.getAuthHeaders()
      )
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels = this.favoriteChannels.filter(
            (id) => id !== channelId
          ); // Update local favorite list
          this.updateFavoriteChannels(); // Update local storage
          this.ts.success(res.message);
        }
        this.isLoading = false;
      });
  }

  // Fetch favorite list
  getFavoriteList(): void {
    const storedFavoriteChannels = localStorage.getItem('favoriteChannels');
    if (storedFavoriteChannels) {
      this.favoriteChannels = JSON.parse(storedFavoriteChannels);
    } else {
      this.isLoading = true;
      // Fetch from API if not found in local storage
      this.apiService
        .getRequest('auth/my_favorites', this.getAuthHeaders())
        .subscribe((res: any) => {
          if (res.response_code === 200) {
            this.favoriteChannels = res.data.map((channel: any) => channel.id);
            this.updateFavoriteChannels(); // Update local storage
          }
          this.isLoading = false;
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

  // set player data in local
  // setplayerData(id: string) {
  //   localStorage.setItem('playerId', id);
  //   this.router.navigateByUrl('/user/playstream');
  // }

    // set player data in local
    setplayerData(channelUrl: string, playerChannelId?: any) {
      localStorage.setItem('playerId', channelUrl);
      localStorage.setItem('playerChannelId', playerChannelId);
      this.router.navigateByUrl('/user/playstream');
      // this.searchShow = !this.searchShow;
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
