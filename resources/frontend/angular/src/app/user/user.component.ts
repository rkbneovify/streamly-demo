import { NgClass, NgFor, NgIf } from '@angular/common';
import { Component } from '@angular/core';
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
  constructor( private crud: CrudService){}
  sideBarAnimation (){
    console.log('hello');
    
  }
  logoutUser(){
    
    this.crud.postRequest(`auth/logout`,``).subscribe((res:any)=>{

    })
  }
}
