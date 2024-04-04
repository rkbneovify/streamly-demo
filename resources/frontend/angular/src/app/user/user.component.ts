import { NgClass, NgFor, NgIf } from '@angular/common';
import { Component, HostListener } from '@angular/core';
import { RouterLink, RouterOutlet } from '@angular/router';
import { CrudService } from '../services/crud.service';
import { HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [NgClass,NgFor,NgIf,RouterOutlet,RouterLink],
  templateUrl: './user.component.html',
  styleUrl: './user.component.scss'
})
export class UserComponent {

  submenu:boolean=false;
  activeTab:string='home';
  access_Token!:any;
  constructor( private crud: CrudService){}

  sideBarAnimation (){
    console.log('hello');
  }

  @HostListener('document:click', ['$event'])
  onClick(event: MouseEvent) {
    const target = event.target as HTMLElement;
    const isInsideMenu = target.closest('.profileUserMenu') || target.classList.contains('schedule_profile') || target.classList.contains('userProfileImage');
    if (!isInsideMenu) {
      console.log('helllo')
      this.submenu = false; // Close the menu if the click is outside the menu
    }
  }





  logoutUser(){
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
        this.access_Token = JSON.parse(localStorage.getItem('deviceInfo') || '');
        const tokenData = this.access_Token?.access_token;
        const headers1 = new HttpHeaders({
          'Authorization': `Bearer ${tokenData}`, // Replace 'your_token_here' with your actual token
          'Accept': 'application/json'
        });
        console.log(tokenData);
        this.crud.postRequest(`auth/logout`, {}, { headers: headers1 }).subscribe((res: any) => {
          console.log('Log Out');
        });
      } catch (error) {
          console.error('Error parsing JSON data:', error);
      }
  }
  }
}
