import { CanActivateFn, Router } from '@angular/router';

export const authguardGuard: CanActivateFn = (route, state) => {
  var user;
  const jsonData = localStorage.getItem('deviceInfo');
  if (jsonData !== null) {
    try {
      user = JSON.parse(localStorage.getItem('deviceInfo') || '');
    } catch (error) {
      console.error('Error parsing JSON data:', error);
    }
  }
  if (user.access_token) {
    return true;
  }
  else {
    
    return false;
  }
};
