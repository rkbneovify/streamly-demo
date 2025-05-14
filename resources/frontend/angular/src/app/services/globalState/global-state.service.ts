
import { Injectable, signal, effect } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class SharedStateService {
  private readonly LOCAL_STORAGE_KEY = 'isMiniPlayer';

  // Initialize the signal from localStorage or use a default value
  private stateSignal = signal<boolean>(
    this.getStateFromLocalStorage() ?? false // Ensure fallback to boolean
  );

  constructor() {
    // Automatically update localStorage whenever the signal changes
    effect(() => {
      const state = this.stateSignal();
      console.log('Service state updated:', state);

      // Only update localStorage if the value has actually changed
      if (state !== this.getStateFromLocalStorage()) {
        this.updateLocalStorage(state);
      }
    });
  }

  // Expose the signal as readonly
  get isMiniPlayer() {
    return this.stateSignal.asReadonly();
  }

  // Method to update the signal
  updateMiniPlayer(newState: boolean): void {
    console.log('Updating state to:', newState);
    this.stateSignal.set(newState);
  }

  // Helper to get the state from localStorage
  private getStateFromLocalStorage(): boolean | null {
    const storedState = localStorage.getItem(this.LOCAL_STORAGE_KEY);
    try {
      return storedState ? JSON.parse(storedState) : false;
    } catch (error) {
      console.error('Failed to parse localStorage value:', error);
      return false;
    }
  }

  // Helper to update localStorage
  private updateLocalStorage(state: boolean): void {
    localStorage.setItem(this.LOCAL_STORAGE_KEY, JSON.stringify(state));
  }
}
