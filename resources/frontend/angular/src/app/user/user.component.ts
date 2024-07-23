import { NgClass, NgFor, NgIf, ViewportScroller } from '@angular/common';
import { Component, HostListener, NgModule, OnInit } from '@angular/core';
import {
  NavigationEnd,
  Router,
  RouterLink,
  RouterOutlet,
} from '@angular/router';
import { CrudService } from '../services/crud.service';
import { HttpHeaders } from '@angular/common/http';
import { ToastrService } from 'ngx-toastr';
import { SearchComponent } from './search/search.component';
import { Subject, debounceTime, distinctUntilChanged } from 'rxjs';
import { DurationISOFormatPipe, TruncatePipe } from '../pipe/duration-format.pipe';
import { ScheduleFormatterPipe } from '../pipe/schedule-formatter.pipe';
import { FormsModule } from '@angular/forms';


@Component({
  selector: 'app-user',
  standalone: true,
  imports: [NgClass, NgFor , FormsModule , ScheduleFormatterPipe, NgIf, RouterOutlet, RouterLink, SearchComponent,DurationISOFormatPipe,TruncatePipe],
  templateUrl: './user.component.html',
  styleUrl: './user.component.scss',
})
export class UserComponent implements OnInit {
  submenu: boolean = false;
  activeTab: string = 'home';
  access_Token!: any;
  isExpanded: boolean = false;
  loading: boolean = false;
  showSubMenu: boolean = false;
  searchShow: boolean = false;
  searchResults: any[] = [];
  favChannelList: any;
  favoriteChannels: number[] = [];
  searchTerm:any='';

  private searchSubject: Subject<string> = new Subject();
  constructor(
    private crud: CrudService,
    private router: Router,
    private ts: ToastrService,
    // private search:SearchComponent
    private viewportScroller: ViewportScroller
  ) {
    this.router.events.subscribe((event) => {
      if (event instanceof NavigationEnd) {
        this.viewportScroller.scrollToPosition([0, 0]);
      }
    });
  }

  ngOnInit(): void {
    // this.getRecentsListData();
    this.setActiveTabBasedOnUrl();
    this.searchSubject
      .pipe(debounceTime(300), distinctUntilChanged())
      .subscribe((searchTerm: string) => {
        this.performSearch(searchTerm);
      });

      // get the fav. list 
     this.getFavoriteList();
  }

  sideBarAnimation() {
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
        console.log(tokenData, '--------token');
        this.crud
          .getRequest('auth/channel-sliders', headers)
          .subscribe((res: any) => {
            console.log('get all channel-sliders ->>', res);
          });
      } catch (error) {
        console.error('Error parsing JSON data:--->>>', error);
      }
    }
    // console.log('hello');
  }

  @HostListener('document:click', ['$event'])
  onClick(event: MouseEvent) {
    const target = event.target as HTMLElement;
    const isInsideMenu =
      target.closest('.profileUserMenu') ||
      target.classList.contains('schedule_profile') ||
      target.classList.contains('userProfileImage') ||
      target.classList.contains('nologout');
    if (!isInsideMenu) {
      console.log('helllo');
      this.submenu = false;
    }
  }

  toggleSidebar(): void {
    this.isExpanded = !this.isExpanded;
  }

  logoutUser() {
    this.loading = true;
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
        this.access_Token = JSON.parse(
          localStorage.getItem('deviceInfo') || ''
        );
        const tokenData = this.access_Token?.access_token;
        const headers1 = new HttpHeaders({
          Authorization: `Bearer ${tokenData}`,
          Accept: 'application/json',
        });
        console.log(tokenData);
        this.crud.postRequest(`auth/logout`, {}, headers1).subscribe({
          next: (res: any) => {
            console.log('Log Out', res);
            if (res.response_code == 200) {
              localStorage.clear();
              localStorage.removeItem('userDetails');
              localStorage.removeItem('loggedInDeviceDetail');
              localStorage.removeItem('loggedInDeviceDetails');
              localStorage.removeItem('allFavoriteChannels');
              localStorage.removeItem('token');

              this.ts.success(res.message);
              this.loading = false;
              this.router.navigate(['/']);
            } else {
              console.log('hello ji');
              this.loading = false;
            }
          },
          error: (err: any) => {
            this.loading = false;
          },
        });
      } catch (error) {
        this.loading = false;
        console.error('Error parsing JSON data:--->>>', error);
      }
    } else {
      this.loading = false;
    }
  }

  changeTab() {
    this.activeTab = 'allChannel';
    this.showSubMenu = true;
  }

  backTohome() {
    this.showSubMenu = false;
    this.router.navigateByUrl('/user');
    this.activeTab = 'home';
  }

  setActiveTabBasedOnUrl(): void {
    const url = this.router.url;
    if (url.includes('/user/home')) {
      this.activeTab = 'home';
    } else if (url.includes('/user/my-stuff')) {
      this.activeTab = 'fav';
    } else if (url.includes('/user/playlistschedule')) {
      this.activeTab = 'playsh';
    } else if (url.includes('/user/channel/all')) {
      this.activeTab = 'allChannel';
    }
  }

  onSearch(searchTerm: any): void {
    console.log(searchTerm?.value);
    this.searchSubject.next(searchTerm.value);
  }

  performSearch(searchTerm: string): void {
    if (!searchTerm) {
      this.allChannelDataList = [];
      return;
    }

    const term = {
      search: searchTerm,
      "typeurl": "mpd"
    };

    this.crud.postRequest('auth/new-search', term).subscribe({
      next: (res: any) => {
        this.allChannelDataList = this.chunkArray(res.data, 6);
        this.ts.success(res.message);
      },
      error: (err: any) => {
        console.error('Search API error: ', err);
        this.ts.error('Search failed. Please try again.');
      },
    });
  }

  allChannelDataList: any = [];

  // get all channels
  get_All_Channels() {
    const body = {
      typeurl: 'mpd',
    };
    this.crud.postRequest('auth/all-channel-list', body).subscribe({
      next: (res: any) => {
        this.allChannelDataList = this.chunkArray(res.data, 6);
        console.log(this.chunkArray(res.data, 6));
        this.searchShow = !this.searchShow;
        console.log(res);
      },
      error: (error: any) => {
        console.log(error);
      },
    });
  }

  chunkArray(array: any, chunkSize: any): any {
    const result: any = [];
    for (let i = 0; i < array.length; i += chunkSize) {
      result.push(array.slice(i, i + chunkSize));
    }
    return result;
  }

  // searchShow=!searchShow
  checkData() {
    // console.log(this.allChannelDataList,'dfghjhgvb');
    if (!this.allChannelDataList.length) {
      this.get_All_Channels();
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

  // Fetch favorite list
getFavoriteList(): void {
  console.log('==========================================>')
    const storedFavoriteChannels = localStorage.getItem('favoriteChannels');
    if (storedFavoriteChannels) {
        this.favoriteChannels = JSON.parse(storedFavoriteChannels);
    } else {
        // Fetch from API if not found in local storage
        this.crud.getRequest('auth/my_favorites').subscribe((res: any) => {
            if (res.response_code === 200) {
                this.favoriteChannels = res.data.map((channel: any) => channel.id);
                this.updateFavoriteChannels(); // Update local storage
            }
        });
    }
}

  // Add channel to favorites
  addFavoriteChannel(channelId: number): void {
    // Call API to add channel to favorites
    this.crud
      .postRequest('auth/addtofavorite', { channelid: channelId })
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels.push(channelId); // Update local favorite list
          this.updateFavoriteChannels(); // Update local storage
          this.ts.success(res.message);
        }
      });
  }

  // Remove channel from favorites
  removeFavoriteChannel(channelId: number): void {
    // Call API to remove channel from favorites
    this.crud
      .postRequest('auth/unfavorite', { channelid: channelId })
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels = this.favoriteChannels.filter(
            (id) => id !== channelId
          ); // Update local favorite list
          this.updateFavoriteChannels(); // Update local storage
          this.ts.success(res.message);
        }
      });
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
  setplayerData(channelUrl: string, playerChannelId?: any) {
    localStorage.setItem('playerId', channelUrl);
    localStorage.setItem('playerChannelId',playerChannelId);
    this.router.navigateByUrl('/user/playstream');
    this.searchShow = !this.searchShow;
  }

  closeSearchData() {
    this.searchShow = !this.searchShow;
    this.allChannelDataList = [];
    this.searchTerm = ''

  }


  playlist_details_show:boolean=false;


  showPlaylist(){
    this.playlist_details_show= !this.playlist_details_show;
  }


    // program-details data 
    programDetailData:any;
    getProgramDetailData(tmsId?: string) {
      const body = {
        "TMSId": tmsId
      };
      this.crud.postRequest('auth/program-details', body).subscribe({
        next: (res: any) => {
          console.log(res);
          this.showPlaylist()
          this.programDetailData = res.data;
          console.log(res);
        },
        error: (err: any) => {
          console.error(err);
        },
      });
    }
}
