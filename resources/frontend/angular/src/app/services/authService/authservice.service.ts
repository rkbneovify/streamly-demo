import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private tokenKey = 'token';
  token:string = '';
  constructor() { }

  getAuthorizationToken(): string {
    this.token = localStorage.getItem(this.tokenKey) || ''
    return this.token
  }

  setAuthorizationToken(token: string) {
    localStorage.setItem(this.tokenKey, token);
    this.token = token;
  }
}
