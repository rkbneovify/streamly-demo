import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';

export const authguardGuard: CanActivateFn = (route, state) => {
  let user;
  const jsonData = localStorage.getItem('deviceInfo');
  if (jsonData !== null) {
    try {
      user = JSON.parse(localStorage.getItem('deviceInfo') || '');
    } catch (error) {
      console.error('Error parsing JSON data:', error);
    }
  }
  if (!!user?.access_token) {
    return true;
    // if ('subscription') {
    //   return true;
    // } else {
    //   if ('currentRoute'.includes('accoutsetting')) {
    //     return true;
    //   } else if ('currentRoute'.includes('profileSetting')) {
    //     return true;
    //   } else {
    //     inject(Router).navigateByUrl('user/buySubscription');
    //     return false;
    //   }
    // }
  } else {
    inject(Router).navigateByUrl('auth/login');
    return false;
  }
};
