import { NgClass, NgFor, NgIf } from '@angular/common';
import { Component, HostListener } from '@angular/core';
import { Router, RouterLink, RouterOutlet } from '@angular/router';
import { CrudService } from '../services/crud.service';
import { HttpHeaders } from '@angular/common/http';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [NgClass, NgFor, NgIf, RouterOutlet, RouterLink],
  templateUrl: './user.component.html',
  styleUrl: './user.component.scss',
})
export class UserComponent {
  submenu: boolean = false;
  activeTab: string = 'home';
  access_Token!: any;
  isExpanded: boolean = false;
  loading: boolean = false;
  showSubMenu:boolean=false;

  constructor(
    private crud: CrudService,
    private router: Router,
    private ts: ToastrService
  ) {}

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
      target.classList.contains('nologout') 
      ;
    if (!isInsideMenu) {
      console.log('helllo');
      this.submenu = false; // Close the menu if the click is outside the menu
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
        this.crud
          .postRequest(`auth/logout`, {}, headers1)
          .subscribe({
            next:(res: any) => {
            console.log('Log Out', res);
            if (res.response_code == 200) {
              localStorage.removeItem('deviceInfo');
              localStorage.removeItem('userDetails');
              localStorage.removeItem('loggedInDeviceDetail');
              localStorage.removeItem('loggedInDeviceDetails');
              localStorage.removeItem('favoriteChannels');
              this.ts.success(res.message);
              this.loading = false;
              this.router.navigate(['/']);
            }else{
              console.log('hello ji')
              this.loading = false;
            }
          },
          error:(err:any)=>{
            this.loading = false;
          }
        }
          );
      } catch (error) {
      this.loading = false;
        console.error('Error parsing JSON data:--->>>', error);
      }
    }else{
      this.loading = false;
    }
  }

  changeTab(){
    this.activeTab='allChannel';
    this.showSubMenu = true;
  }

  backTohome(){
    this.showSubMenu = false;
    this.router.navigateByUrl('/user')
    this.activeTab='home';
  }
}
