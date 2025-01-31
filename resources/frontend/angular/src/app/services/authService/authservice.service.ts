import { DOCUMENT, isPlatformBrowser } from '@angular/common';
import { Injectable, inject, Inject, PLATFORM_ID } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  // deafulat to localstorege
  private local_Storage: Storage;
  private session_Storage: Storage;

  private tokenKey = 'token';
  private loaction = 'userLocation'
  token: string = '';
  constructor(
    @Inject(DOCUMENT) private document: Document,
    @Inject(PLATFORM_ID) private platformId: any
  ) {
    if (isPlatformBrowser(this.platformId)) {
      this.local_Storage = localStorage;
      this.session_Storage = sessionStorage;
    } else {
      // mocked or empty implements for SSR
      this.local_Storage = this.createMockStorage();
      this.session_Storage = this.createMockStorage();
    }
  }

  private createMockStorage(): Storage {
    return {
      setItem: (key: string, value: string) => {},
      getItem: (key: string) => null,
      removeItem: (key: string) => {},
      clear: () => {},
      key: (index: number) => null,
      length: 0,
    };
  }

  getAuthorizationToken(): string {
    this.token = this.local_Storage.getItem(this.tokenKey) || '';
    return this.token;
  }

  getLocarionAccess(): string {
    this.token = this.local_Storage.getItem(this.loaction) || '';
    return this.token;
  }

  setAuthorizationToken(token: string) {
    this.local_Storage.setItem(this.tokenKey, token);
    // localStorage.setItem(this.tokenKey, token);
  }

  getlocalData(key:string) {
    const localData = this.local_Storage.getItem(key);
    return localData;
  }
}
