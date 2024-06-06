import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';

export const authguardGuard: CanActivateFn = (route, state) => {
  let user;
  console.log('auth gaurd works');

  const jsonData = localStorage.getItem('deviceInfo');
  if (jsonData !== null) {
    try {
      user = JSON.parse(localStorage.getItem('deviceInfo') || '');
    } catch (error) {
      console.error('Error parsing JSON data:', error);
    }
  }
  if (!!user?.access_token) {
    console.log(user.subscribed, '=======sub====');
    // return true

    if (user?.subscribed) {
      console.log('subscription true, if block executed');
      return true;
    } else {
      // inject(Router).navigateByUrl('user/plan');
      // return false;
      console.log('subscription false, else block executed');
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
        currentRoute?.includes('user/connected-device') ||
        currentRoute?.includes('user/downloadapp') ||
        currentRoute?.includes('user/payment-subscription')
      ) {
        console.log('if current route matches');
        return true;
      } else {
        inject(Router).navigateByUrl('user/subscription-expired');
        console.log('else plans');
        return false;
      }
    }
  } else {
    inject(Router).navigateByUrl('auth/login');
    return false;
  }
};

// checkuserLogin or not
export const authGuarRedirectToUser: CanActivateFn = (route, state) => {
  console.log('auth gaurd works');

  const token = localStorage.getItem('token');
      if (token) {                
        console.log('Heman =====>',token);
        inject(Router).navigateByUrl('/user');
        return false;
      } 
      else {
        console.log('Heman =====>',token);
        return true;
      }
};

// checkuserLogin or not
export const authGuarLoginStatus: CanActivateFn = (route, state) => {

  const token = localStorage.getItem('token');
      if (token) {                
        console.log('Heman =====>',token);
        return true;
      } 
      else {
        console.log('Heman =====>',token);
        inject(Router).navigateByUrl('/auth/login');
        return false;
      }

};


