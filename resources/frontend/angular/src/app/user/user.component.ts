import { NgClass, NgFor, NgIf, ViewportScroller } from '@angular/common';
import {
  AfterViewInit,
  ChangeDetectorRef,
  Component,
  effect,
  HostListener,
  NgModule,
  OnDestroy,
  OnInit,
} from '@angular/core';
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
import {
  Subject,
  Subscription,
  debounceTime,
  distinctUntilChanged,
  filter,
} from 'rxjs';
import {
  DurationISOFormatPipe,
  TruncatePipe,
} from '../pipe/duration-format.pipe';
import { ScheduleFormatterPipe } from '../pipe/schedule-formatter.pipe';
import { FormsModule } from '@angular/forms';
import { ScreenSizeService } from '../services/screenSize/screen-size.service';
import { animate, style, transition, trigger } from '@angular/animations';
import { environment } from '../../environments/environment';
import { PlaystreamComponent } from './playstream/playstream.component';
import { SharedStateService } from '../services/globalState/global-state.service';
import { UtilsService } from '../services/utils/utils.service';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [
    NgClass,
    NgFor,
    FormsModule,
    ScheduleFormatterPipe,
    NgIf,
    RouterOutlet,
    RouterLink,
    SearchComponent,
    DurationISOFormatPipe,
    TruncatePipe,
    PlaystreamComponent,
  ],
  templateUrl: './user.component.html',
  styleUrl: './user.component.scss',
  animations: [
    trigger('slideDown', [
      transition(':enter', [
        style({ top: '-100%', opacity: 0 }),
        animate('300ms ease-in', style({ top: '0', opacity: 1 })),
      ]),
      transition(':leave', [
        animate('300ms ease-out', style({ top: '-100%', opacity: 0 })),
      ]),
    ]),
  ],
})
export class UserComponent implements OnInit, AfterViewInit, OnDestroy {
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
  searchTerm: any = '';
  subscribed: boolean;
  private screenSizeSubscription!: Subscription;
  currentWidth: number = 0;
  private isAdmin!: string;
  navOpen: boolean = false;
  setActivePlanId!: string;
  expandedCardIndex: number | null = null;
  isMiniPlayer: boolean = null;
  zipcode: any;
  dash_board_link: string = environment.baseUrl;
  isConnectedDeviceShow: boolean = false;
  isHoaMainUser: boolean = false;
  urlList: Array<String> = [];
  isSearchComponent: boolean = true;
  private searchSubject: Subject<string> = new Subject();
  notificationCount: number = 0;
  constructor(
    private crud: CrudService,
    private router: Router,
    private ts: ToastrService,
    private viewportScroller: ViewportScroller,
    private screenSizeService: ScreenSizeService,
    private cdr: ChangeDetectorRef,
    private sharedStateService: SharedStateService,
    private utilsService: UtilsService
  ) {

    this.router.events.subscribe((event) => {
      if (event instanceof NavigationEnd) {
        this.viewportScroller.scrollToPosition([0, 0]);
      }
    });

    this.isAdmin = JSON.parse(
      localStorage.getItem('userDetails')
    ).roles[0].name;

    console.log('shre1 ==>', this.sharedStateService.isMiniPlayer());
    effect(() => {
      this.isMiniPlayer = this.sharedStateService.isMiniPlayer();
      console.log('shre ==>', this.sharedStateService.isMiniPlayer());
    })

    this.urlList = [
      '/user/connected-device',
      '/user/connected-houses',
      '/user/user-settings',
      '/user/faq',
      '/user/privacy-policy',
      '/user/term-condition',
      '/user/contact-us',
      '/user/plan',
      '/user/supported-devices',
      '/user/report',
      '/user/downloadapp',
      '/user/notifications'

    ]

  }

  checkIsAdmin(): string {
    return this.isAdmin;
  }

  // Function to expand the last card
  expandLastCard(index: number) {
    console.log('IsExpand', index);
    this.expandedCardIndex = index;
  }

  // Function to close the expanded card when another card is clicked
  closeExpandedCard() {
    console.log('IsClose');

    this.expandedCardIndex = null;
  }

  ngOnInit(): void {
    this.getNotificationCount();
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe((event: NavigationEnd) => {
        if (this.urlList.includes(event.urlAfterRedirects)) {
          this.isSearchComponent = false;
        } else {
          this.isSearchComponent = true;
        }
        console.log("Current url :", event.urlAfterRedirects);
      });
    this.utilsService.get_all_device().subscribe(() => {
      this.isConnectedDeviceShow = this.utilsService.isCustomUser();
      // Update UI or logic here based on new value
    });
    console.log('isconn', this.isConnectedDeviceShow)
    this.zipcode =
      JSON.parse(localStorage.getItem('userLocation'))?.zipcode || '';
    // Listen for route changes
    if (!this.zipcode) return;
    this.setActiveTabBasedOnUrl();
    this.router.events
      .pipe(filter((event) => event instanceof NavigationEnd))
      .subscribe((event: NavigationEnd) => {
        // Call your function here
        this.setActiveTabBasedOnUrl();
      });

    this.searchSubject
      .pipe(debounceTime(300), distinctUntilChanged())
      .subscribe((searchTerm: string) => {
        this.performSearch(searchTerm);
      });

    this.getFavoriteList();
    this.subscribed = JSON.parse(localStorage.getItem('deviceInfo')).subscribed;
    // Subscribe to screen width changes
    this.screenSizeSubscription = this.screenSizeService
      .getScreenWidth()
      .subscribe((width) => {
        this.currentWidth = width;

        // Recalculate chunk size based on screen width
        if (this.allChannelDataList) {
          const chunkSize = Math.floor(this.currentWidth / 190);
          this.allChannelDataList = this.chunkArray(
            this.allChannelDataList.flat(),
            chunkSize
          );
        }
      });

    this.setActivePlanId = localStorage.getItem('setActivePlanId');

    if (!this.setActivePlanId) {
      this.getPlans();
    }

    const isDataForHoaMain = JSON.parse(localStorage.getItem('is_hoa_main_user'));
    if (isDataForHoaMain != null) {
      this.isHoaMainUser = Boolean(isDataForHoaMain);
    }
  }

  ngAfterViewInit(): void {
    this.getFavoriteList();

  }

  ngOnDestroy(): void {
    // Unsubscribe when the component is destroyed to avoid memory leaks
    if (this.screenSizeSubscription) {
      this.screenSizeSubscription.unsubscribe();
    }
  }

  // menu animation
  mobileMenuOpen: boolean = false;
  toggleNav() {
    this.navOpen = !this.navOpen;
  }

  closeNav() {
    this.navOpen = false;
  }

  toggleMobileMenu() {
    this.mobileMenuOpen = !this.mobileMenuOpen;
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
        this.crud
          .getRequest('auth/channel-sliders', headers)
          .subscribe((res: any) => { });
      } catch (error) {
        console.error(error);
      }
    }
  }

  // change the tab when click
  @HostListener('document:click', ['$event'])
  onClick(event: MouseEvent) {
    const target = event.target as HTMLElement;
    const isInsideMenu =
      target.closest('.profileUserMenu') ||
      target.classList.contains('schedule_profile') ||
      target.classList.contains('userProfileImage') ||
      target.classList.contains('nologout');

    const isExpandable = target.classList.contains('isExpandable');

    const isInsideSearch =
      !target.closest('.card_page') &&
      !target.closest('.close-search img') &&
      !target.closest('.search_Bar') &&
      !target.closest('.schedule_details_container') &&
      !target.closest('.schedule_details_container1');

    if (!isExpandable) {
      this.isExpanded = false;
    }

    // If clicked outside of search container and it's not the close button, hide search modal
    if (isInsideSearch) {
      this.searchShow = false;

      this.searchTerm = '';
      this.allChannelDataList = [];
    }

    if (!isInsideMenu) {
      this.submenu = false;
      return;
    }
  }

  @HostListener('mouseover', ['$event'])
  onMouseOver(event: MouseEvent) {
    const target = event.target as HTMLElement; // Ensure it's an HTMLElement
    const parentDiv = target.closest('.onHoverShowMenu') as HTMLElement;

    if (parentDiv) {
      this.isExpanded = true;
    } else {
      this.isExpanded = false;
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
        this.crud.postRequest(`auth/logout`, {}, headers1).subscribe({
          next: (res: any) => {
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
              this.loading = false;
            }
          },
          error: (err: any) => {
            this.loading = false;
          },
        });
      } catch (error) {
        this.loading = false;
        console.error(error);
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
    } else {
      this.activeTab = 'null';
    }
  }

  onSearch(searchTerm: any): void {
    if (this.subscribed) {
      this.searchSubject.next(searchTerm.value);
      if (searchTerm) {
        this.searchShow = true;
      }
      //  else {
      //   this.searchShow = false;
      // }
    }
  }

  performSearch(searchTerm: string): void {
    if (!searchTerm) {
      // this.allChannelDataList = [];
      this.get_All_Channels();
      return;
    }

    const term = {
      search: searchTerm,
      typeurl: 'mpd',
      zipcode: this.zipcode,
    };

    this.crud.postRequest('auth/new-search', term).subscribe({
      next: (res: any) => {
        this.allChannelDataList = this.chunkArray(
          res.data,
          this.currentWidth / 190
        );

        if (res.data.length == 0) {
          this.ts.error('No result found.');
        } else {
          this.ts.success(res.message);
        }
      },
      error: (err: any) => {
        console.error(err);
        this.ts.error('Search failed. Please try again.');
      },
    });
  }

  allChannelDataList: any = [];

  // get all channels
  get_All_Channels() {
    this.isLoading = true;
    const body = {
      typeurl: 'mpd',
      zipcode: this.zipcode,
    };
    this.crud.postRequest('auth/all-channel-list', body).subscribe({
      next: (res: any) => {
        this.allChannelDataList = this.chunkArray(
          res.data,
          this.currentWidth / 190
        );
        this.searchShow = true;
        this.isLoading = false;
      },
      error: (error: any) => {
        console.log(error);
        this.isLoading = false;
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
    if (
      this.subscribed &&
      !this.searchTerm &&
      !this.allChannelDataList.length
    ) {
      this.get_All_Channels();
      this.getFavoriteList();
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
          this.cdr.detectChanges();
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
          this.cdr.detectChanges();
        }
      });
  }

  // Update local storage with current favorite channels
  updateFavoriteChannels(): void {
    localStorage.setItem(
      'favoriteChannels',
      JSON.stringify(this.favoriteChannels)
    );
  }

  // set player data in local
  setplayerData(channelUrl: string, playerChannelId?: any) {
    localStorage.setItem('playerId', channelUrl);
    localStorage.setItem('playerChannelId', playerChannelId);

    if (this.searchTerm) {
      this.searchTerm = '';
    }

    this.router.navigateByUrl('/user/playstream');
    this.searchShow = !this.searchShow;
    this.currentProgramDetail = false;
    this.playlist_details_show = false;


  }

  closeSearchData() {
    this.searchShow = !this.searchShow;
    this.allChannelDataList = [];
    this.searchTerm = '';
  }

  playlist_details_show: boolean = false;

  showPlaylist() {
    this.playlist_details_show = !this.playlist_details_show;
  }

  // program-details data
  programDetailData: any;
  getProgramDetailData(tmsId?: string, channelId?: any) {
    this.isLoading = true;
    const body = {
      TMSId: tmsId,
      channel_id: channelId,
      timezone: this.getUserTimezone(),
    };
    this.crud.postRequest('auth/program-details', body).subscribe({
      next: (res: any) => {
        this.showPlaylist();
        this.programDetailData = res.data;
        this.isLoading = false;
      },
      error: (err: any) => {
        console.error(err);
        this.isLoading = false;
      },
    });
  }

  getUserTimezone(): string {
    return Intl.DateTimeFormat().resolvedOptions().timeZone;
  }

  changeSideBarTab(type: string) {
    if (type === 'home') {
      this.activeTab = type;
    } else if (type === 'fav') {
      this.activeTab = type;
    } else if (type === 'playsh') {
      this.activeTab = type;
    } else if (type === 'allChannel') {
      this.activeTab = type;
    } else if (type === 'fav') {
      this.activeTab = type;
    }
  }

  // current Programs
  currentProgramDetail: boolean = false;
  programChannelId: string;
  programChannelUrl: string;
  isLoading: boolean = false;
  currentprogramDetailData: any;

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
    const body = {
      channel_id: id,
      current_datetime: this.getCurrentFormattedDateTime(),
    };
    this.crud.postRequest('auth/player-details', body).subscribe({
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

  getPlans() {
    this.crud.getRequest('auth/setting-details').subscribe({
      next: (res: any) => {
        try {
          this.setActivePlanId = JSON.stringify(res.plan?.id);
          localStorage.setItem('setActivePlanId', this.setActivePlanId);
        } catch (error) {
          console.log(error);
        }
      },
      error: (err) => {
        console.log(err);
      },
    });
  }

  // change the tab when click
  // @HostListener('document:click', ['$event'])
  // onClick1(event: MouseEvent) {
  //   const target = event.target as HTMLElement;
  //   const isInsideMenu = target.classList.contains('isExpandable');
  //   console.log('Hello', target, isInsideMenu);

  //   if (!target) {
  //     this.isExpanded = false;
  //     console.log('Hello', target, isInsideMenu, this.isExpanded);
  //   }
  // }


  getNotificationCount() {

    this.crud.getRequest('auth/hoa/notification/count').subscribe({
      next: (res: any) => {
        this.notificationCount = res.count;
      },
      error: (error: any) => {

      },
    });
  }
}
