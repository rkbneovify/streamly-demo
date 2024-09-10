import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';

export const authguardGuard: CanActivateFn = (route, state) => {
  let user;
  const router = inject(Router); // Inject Router

  console.log('auth guard works');

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
    console.log(user.subscribed, '=======sub====');
    if (user?.subscribed) {
      console.log('subscription true, if block executed');
      return true;
    } else {
      const currentRoute = state?.url;
      console.log(
        'current route=>',
        currentRoute,
        '====>',
        currentRoute?.includes('user-settings'),
        'state = >',
        state,
        'route=>',
        route
      );
      if (
        currentRoute?.includes('user-settings') ||
        currentRoute?.includes('user/plan') ||
        currentRoute?.includes('user/subscription-expired') ||
        currentRoute?.includes('user/subscription-cancel') ||
        currentRoute?.includes('user/connected-device') ||
        currentRoute?.includes('user/downloadapp') ||
        currentRoute?.includes('user/payment-subscription')
      ) {
        console.log('if current route matches');
        return true;
      } else {
        router.navigateByUrl('user/subscription-expired');
        console.log('else plans');
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
  console.log('auth guard works');

  const router = inject(Router); // Inject Router
  const token = localStorage.getItem('token');

  if (token) {
    if (state.url !== '/user') {
      console.log('Heman3 =====>', token);
      router.navigateByUrl('/user/home');
      return false; // Block navigation to the requested route
    }
    return true; // Already on /user, allow navigation
  } else {
    console.log('Heman1 =====>', token);
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
    console.log('Heman2 =====>', token);
    router.navigateByUrl('/user');
    return false; // Prevent navigation to the requested route
  } else {
    if (state.url === '/auth/login') {
      return true; // Already on the target route, allow navigation
    }
    console.log('Heman4 =====>', token);
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
