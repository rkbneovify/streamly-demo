import { NgClass, NgFor, NgIf, ViewportScroller } from '@angular/common';
import { Component, HostListener, OnInit } from '@angular/core';
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

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [NgClass, NgFor, NgIf, RouterOutlet, RouterLink, SearchComponent],
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
    this.searchSubject.pipe(
      debounceTime(300), 
      distinctUntilChanged()
    ).subscribe((searchTerm: string) => {
      this.performSearch(searchTerm);
    });
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
              localStorage.removeItem('favoriteChannels');
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
      this.searchResults = [];
      return;
    }

    this.crud
      .getRequest(`your-api-endpoint?query=${searchTerm}`)
      .subscribe({
        next: (res: any) => {
          this.searchResults = res.data;
        },
        error: (err: any) => {
          console.error('Search API error: ', err);
          this.ts.error('Search failed. Please try again.');
        },
      });
  }
}
