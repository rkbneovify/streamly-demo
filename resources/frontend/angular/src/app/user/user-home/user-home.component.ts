import { CommonModule, NgClass } from '@angular/common';
import {
  Component,
  ElementRef,
  HostListener,
  OnInit,
  ViewChild,
} from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';
import { LoaderComponent } from '../../components/others/loader/loader.component';
import {
  DurationISOFormatPipe,
  TruncatePipe,
} from '../../pipe/duration-format.pipe';
import { ScheduleFormatterPipe } from '../../pipe/schedule-formatter.pipe';

@Component({
  selector: 'app-user-home',
  standalone: true,
  imports: [
    CommonModule,
    NgClass,
    RouterLink,
    LoaderComponent,
    TruncatePipe,
    DurationISOFormatPipe,
    ScheduleFormatterPipe,
  ],
  templateUrl: './user-home.component.html',
  styleUrl: './user-home.component.scss',
})
export class UserHomeComponent implements OnInit {
  access_Token: any;
  sldierData: any;
  favChannelList: any;
  favoriteChannels: number[] = [];
  isLoading: boolean = false;
  @ViewChild('nextBtn') sliderNexrButton!: ElementRef;
  currentProgramDetail: boolean = false;
  programDetailData: any;

  constructor(
    private crud: CrudService,
    private router: Router,
    private ts: ToastrService
  ) {}
  ngOnInit(): void {
    this.loginSliders();
    this.getFavoriteList();
    this.getRecentsListData();
    this.channelsWithGenre();
  }

  ngAfterViewInit(): void {
    
    // setTimeout(() => {
    //   if (this.sliderNexrButton) {
    //     console.log('start slider')
    //     this.sliderNexrButton.nativeElement.click();
    //   }
    // }, 0);
  }

  loginSliders() {
    this.isLoading = true;
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
        this.access_Token = JSON.parse(
          localStorage.getItem('deviceInfo') || ''
        );
        const tokenData = this.access_Token?.access_token;
        const headers = new HttpHeaders({
          Authorization: `Bearer ${tokenData}`,
          Accept: 'application/json',
        });
        this.crud
          .getRequest('auth/channel-sliders', headers)
          .subscribe((res: any) => {
            if (res.response_code == 200) {
              this.sldierData = res.data;
              this.isLoading = false;
            }
          });
      } catch (error) {
        console.error(error);
        this.isLoading = false;
      }
    }
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

  //
  getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(
      localStorage.getItem('deviceInfo') || ''
    ).access_token;
    return new HttpHeaders({
      Authorization: `Bearer ${tokenData}`,
      Accept: 'application/json',
    });
  }

  // Add channel to favorites
  addFavoriteChannel(channelId: number): void {
    // Call API to add channel to favorites
    this.isLoading = true;
    this.crud
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
          this.isLoading = false;
        }
      });
  }

  // Remove channel from favorites
  removeFavoriteChannel(channelId: number): void {
    this.isLoading = true;
    // Call API to remove channel from favorites
    this.crud
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
          this.isLoading = false;
        }
      });
  }

  // Fetch favorite list
  getFavoriteList(): void {
    this.isLoading = true;
    const storedFavoriteChannels = localStorage.getItem('favoriteChannels');
    if (storedFavoriteChannels) {
      this.favoriteChannels = JSON.parse(storedFavoriteChannels);
    } else {
      // Fetch from API if not found in local storage
      this.crud
        .getRequest('auth/my_favorites', this.getAuthHeaders())
        .subscribe((res: any) => {
          if (res.response_code === 200) {
            this.favoriteChannels = res.data.map((channel: any) => channel.id);
            this.updateFavoriteChannels(); // Update local storage
            this.isLoading = false;
          }
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

  // set player data in local
  setplayerData(id: string, channel_id?: any) {
    localStorage.setItem('playerId', id);
    localStorage.setItem('playerChannelId', channel_id);
    this.router.navigateByUrl('/user/playstream');
  }

  genre_Data: any;
  // get genre channel
  channelsWithGenre() {
    this.isLoading = true;
    const body = {
      typeurl: 'mpd',
      zipcode: '',
    };
    this.crud
      .postRequest('auth/channel-list', body, this.getAuthHeaders())
      .subscribe({
        next: (res: any) => {
          // const list = res.data;
          // localStorage.setItem('allChannel',JSON.stringify(list));
          // this.data = JSON.parse(localStorage.getItem('allChannel'));
          this.genre_Data = res.data;
          this.isLoading = false;
        },
        error: (err: any) => {
          console.log(err);
          this.isLoading = false;
        },
      });
  }

  recentsPlayData: any;
  getRecentsListData(): void {
    // Fetch from API if not found in local storage
    this.isLoading = true;
    const body = { typeurl: 'mpd' };
    this.crud
      .postRequest('auth/recent-channels', body)
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.recentsPlayData = res.data;
          this.isLoading = false;
        }
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

  submenu: boolean = false;
  // change the tab when click
  @HostListener('document:click', ['$event'])
  onClick(event: MouseEvent) {
    const target = event.target as HTMLElement;
    const isInsideMenu =
      target.classList.contains('schedule_profile') ||
      target.classList.contains('userProfileImage');
    console.log('envet');
    if (isInsideMenu) {
      console.log('helllo true');
      this.submenu = true;
      return;
    }
  }

  programChannelId: string;
  programChannelUrl: string;

  // getCurrentProgram(){
  //   this.currentProgramDetail = !this.currentProgramDetail;
  // }

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
    this.crud.postRequest('auth/player-details', body).subscribe({
      next: (res: any) => {
        this.currentProgramDetail = !this.currentProgramDetail;
        this.programDetailData = res.data.program_details;
        this.isLoading = false;
      },
      error: (err: any) => {
        console.error(err);
        this.isLoading = false;
      },
    });
  }
}
