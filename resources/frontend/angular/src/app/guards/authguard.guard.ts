import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';

export const authguardGuard: CanActivateFn = (route, state) => {
  let user;
  const router = inject(Router); // Inject Router
  const jsonData = localStorage.getItem('deviceInfo');
  const token_access = localStorage.getItem('token');
  if (jsonData !== null) {
    try {
      user = JSON.parse(jsonData);
    } catch (error) {
      console.error('Error parsing JSON data:', error);
    }
  }

  if (token_access) {
    if (user?.subscribed) {
      return true;
    } else {
      const currentRoute = state?.url;
      if (
        currentRoute?.includes('user-settings') ||
        currentRoute?.includes('user/plan') ||
        currentRoute?.includes('user/subscription-expired') ||
        currentRoute?.includes('user/subscription-cancel') ||
        currentRoute?.includes('user/connected-device') ||
        currentRoute?.includes('user/downloadapp') ||
        currentRoute?.includes('user/payment-subscription')
      ) {
        return true;
      } else {
        router.navigateByUrl('user/subscription-expired');
        return false;
      }
    }
  } else {
    localStorage.clear();
    router.navigateByUrl('auth/login');
    return false;
  }
};

export const authGuarRedirectToUser: CanActivateFn = (route, state) => {
  const router = inject(Router); // Inject Router
  const token = localStorage.getItem('token');

  if (token) {
    if (state.url !== '/user') {
      router.navigateByUrl('/user/home');
      return false; // Block navigation to the requested route
    }
    return true; // Already on /user, allow navigation
  } else {
    return true; // Allow navigation to the requested route
  }
};

// Check user login status
export const authGuarLoginStatus: CanActivateFn = (route, state) => {
  const router = inject(Router); // Inject Router
  const token = localStorage.getItem('token');

  if (token) {
    if (state.url === '/user') {
      return true; // Already on the target route, allow navigation
    }
    router.navigateByUrl('/user');
    return false; // Prevent navigation to the requested route
  } else {
    if (state.url === '/auth/login') {
      return true; // Already on the target route, allow navigation
    }
    if(
      state?.url?.includes('register') ||
      state?.url?.includes('change-password') ||
      state?.url?.includes('forget-password') ||
      state?.url?.includes('remove-device')
    ){
      // console.log('state =>',state,'route =>',route);
      return true;
    }
    router.navigateByUrl('/auth/login');
    return false; // Prevent navigation to the requested route
  }
};
