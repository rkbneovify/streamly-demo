import { Injectable, HostListener } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ScreenSizeService {
  // BehaviorSubject to track the width, initial value is the current screen width
  private screenWidth = new BehaviorSubject<number>(window.innerWidth);

  constructor() {
    // Listen for screen resize events
    this.listenToResize();
  }

  // Function to get the current screen width as an observable
  getScreenWidth() {
    return this.screenWidth.asObservable();
  }

  // Function to manually return the current width as a number
  getCurrentWidth(): number {
    return this.screenWidth.value;
  }

  // Listen to window resize and update the BehaviorSubject
  private listenToResize() {
    window.addEventListener('resize', () => {
      this.screenWidth.next(window.innerWidth);
    });
  }
}
