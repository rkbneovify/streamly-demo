import { NgClass } from '@angular/common';
import { Component, OnInit, inject } from '@angular/core';
import { Router, RouterLink, RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-landing-page',
  standalone: true,
  imports: [RouterLink,RouterOutlet,NgClass],
  templateUrl: './landing-page.component.html',
  styleUrl: './landing-page.component.scss',
})
export class LandingPageComponent implements OnInit{
  // footer-rd
  stateR:boolean = false;
  router = inject(Router);

  ngOnInit(): void {
    console.log(this.router.url,'url');
    const url = this.router.url;
    if(url == '/'){
      // this.stateR = !this.stateR;
    }else{
      // this.stateR = false;
    }
    

// if(localStorage.getItem('token')){
//   this.router.navigateByUrl('/user');
// }
  }
}
