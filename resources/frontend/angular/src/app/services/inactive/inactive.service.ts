import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class InactivityService {
  private inactivityTimer: any;

  constructor(private router: Router) {
    this.setUpInactivityTimer();
  }

  setUpInactivityTimer(timeout = 30 * 60 * 1000) {
    // Set up an inactivity timer that reloads the page after the specified timeout (30 minutes)
    const resetTimer = () => {
      clearTimeout(this.inactivityTimer);
      this.inactivityTimer = setTimeout(() => {
        this.reloadPage();
      }, timeout);
    };

    // Add event listeners for user interactions to reset the inactivity timer
    window.addEventListener('mousemove', resetTimer);
    window.addEventListener('mousedown', resetTimer); // Detects mouse clicks
    window.addEventListener('keypress', resetTimer);  // Detects keyboard input
    window.addEventListener('touchstart', resetTimer); // Detects touch events on mobile devices
    window.addEventListener('scroll', resetTimer);    // Detects scrolling

    // Initialize the inactivity timer
    resetTimer();
  }

  // Function to reload the page
  private reloadPage() {
    window.location.reload();
  }
}
