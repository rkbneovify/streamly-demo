import { Injectable } from '@angular/core';
import { Router, NavigationStart } from '@angular/router';
import { SharedStateService } from '../globalState/global-state.service';

@Injectable({
  providedIn: 'root',
})
export class RouteChangeService {
  private currentRoute: string | null = null;
  private popStateListener: () => void;

  // Define the routes for which isMiniPlayer should be false
  private routesToDisableMiniPlayer = ['/user/user-settings', '/user/connected-device', '/user/downloadapp'];

  constructor(
    private router: Router,
    private sharedStateService: SharedStateService
  ) {
    // Listen for popstate events
    this.popStateListener = this.onPopState.bind(this);
    window.addEventListener('popstate', this.popStateListener);

    // Listen for router navigation events
    this.router.events.subscribe((event) => {
      if (event instanceof NavigationStart) {
        // Check if the current route should disable the mini-player
        if (this.routesToDisableMiniPlayer.includes(event.url)) {
          console.log('Disabling mini-player for route:', event.url);
          this.sharedStateService.updateMiniPlayer(false);
        } else if (
          this.currentRoute === '/user/playstream' &&
          event.url !== '/user/playstream'
        ) {
          console.log('Enabling mini-player for route change:', event.url);
          this.sharedStateService.updateMiniPlayer(true);
        }

        // Update the current route
        this.currentRoute = event.url;
      }
    });
  }

  private onPopState(event: PopStateEvent): void {
    console.log('Browser back button clicked', event);

    // Check if the new route should disable the mini-player
    if (this.currentRoute && this.routesToDisableMiniPlayer.includes(this.currentRoute)) {
      this.sharedStateService.updateMiniPlayer(false);
      console.log('Back button clicked - Disabling mini-player!');
    } else {
      this.sharedStateService.updateMiniPlayer(true);
      console.log('Back button clicked - Enabling mini-player!');
    }
  }
}
