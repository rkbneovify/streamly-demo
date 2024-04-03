import { NgClass, NgFor, NgIf } from '@angular/common';
import { Component, HostListener } from '@angular/core';
import { RouterLink, RouterOutlet } from '@angular/router';
import { CrudService } from '../services/crud.service';

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
    this.crud.postRequest(`auth/logout`,``).subscribe((res:any)=>{
    })
  }
}
