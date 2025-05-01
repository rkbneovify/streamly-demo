import { NgClass } from '@angular/common';
import { Component, OnInit, inject } from '@angular/core';
import { Router, RouterLink, RouterOutlet } from '@angular/router';
import { ChatbotComponent } from "../components/feature/chatbot/chatbot.component";

@Component({
  selector: 'app-landing-page',
  standalone: true,
  imports: [RouterLink, RouterOutlet, NgClass, ChatbotComponent],
  templateUrl: './landing-page.component.html',
  styleUrl: './landing-page.component.scss',
})
export class LandingPageComponent implements OnInit{
  // footer-rd
  stateR:boolean = false;
  router = inject(Router);

  ngOnInit(): void {
    const url = this.router.url;
    if(url == '/'){
      // this.stateR = !this.stateR;
    }else{
      // this.stateR = false;
    }
  }
}
