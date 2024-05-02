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
      console.log('current route=>',currentRoute,'====>',currentRoute?.includes('user-settings') ,'state = >',state,'route=>',route);
      if (currentRoute?.includes('user-settings') || currentRoute?.includes('user/plan') || currentRoute?.includes('user/subscription-expired')) {
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


