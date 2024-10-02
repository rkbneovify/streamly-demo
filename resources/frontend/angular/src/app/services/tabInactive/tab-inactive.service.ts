import { Injectable } from '@angular/core';
import { fromEvent, Observable, Subscription, timer } from 'rxjs';
import { filter, switchMap, takeUntil } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class TabInactivityService {

  private inactivityTime = 6000; // 30 minutes in milliseconds
  private userActivityEvents: Observable<any>[] = [
    fromEvent(window, 'mousemove'),
    fromEvent(window, 'keydown'),
    fromEvent(window, 'scroll'),
    fromEvent(window, 'click')
  ];

  private visibilityChange$: Observable<any> = fromEvent(document, 'visibilitychange');
  private userActivitySub: Subscription | null = null;
  private isTabActive = true;

  constructor() { }

  public startTabInactivityCheck() {
    // Monitor tab visibility change
    this.visibilityChange$
      .pipe(
        filter(() => !document.hidden), // Only trigger when tab becomes visible
        switchMap(() => this.startInactivityTimer()) // Restart timer on tab activation
      )
      .subscribe();

    // Monitor for when the tab becomes inactive (hidden)
    this.visibilityChange$
      .pipe(
        filter(() => document.hidden), // Only trigger when tab becomes hidden
      )
      .subscribe(() => this.resetTimer()); // Reset the timer when the tab is hidden
  }

  private startInactivityTimer(): Observable<any> {
    // Start a 30-minute timer when the tab becomes active
    return timer(this.inactivityTime).pipe(
      takeUntil(fromEvent(document, 'mousemove')), // Reset timer on user activity
      takeUntil(fromEvent(document, 'keydown')),
      takeUntil(fromEvent(document, 'scroll')),
      takeUntil(fromEvent(document, 'click'))
    );
  }

  resetTimer() {
    // Reset the timer
    if (this.userActivitySub) {
      this.userActivitySub.unsubscribe();
    }
  }

  private refreshPage() {
    // Refresh the page
    window.location.reload();
  }
}
