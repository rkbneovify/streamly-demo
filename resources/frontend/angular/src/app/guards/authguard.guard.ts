import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from '../services/authService/authservice.service';

export const authguardGuard: CanActivateFn = (route, state) => {
  let user: any = null;

  const router = inject(Router);
  const getlocalData = inject(AuthService);

  const jsonData = getlocalData.getlocalData('deviceInfo');
  const token_access = getlocalData.getAuthorizationToken();

  if (jsonData) {
    try {
      user = JSON.parse(jsonData);
    } catch (error) {
      console.error('Error parsing JSON data:', error);
    }
  }

  if (token_access) {
    if (user?.subscribed) {
      return true;
    }

    const currentRoute = state?.url;

    if (
      currentRoute?.includes('user-settings') ||
      currentRoute?.includes('user/plan') ||
      currentRoute?.includes('user/subscription-expired') ||
      currentRoute?.includes('user/subscription-cancel') ||
      currentRoute?.includes('user/connected-device') ||
      currentRoute?.includes('user/connected-houses') ||
      currentRoute?.includes('user/downloadapp') ||
      currentRoute?.includes('user/payment-subscription')
    ) {
      return true;
    }

    return router.createUrlTree(['/user/subscription-expired']);
  }

  localStorage.clear();

  return router.createUrlTree(['/auth/login'], {
    queryParams: route.queryParams
  });
};

export const authGuarRedirectToUser: CanActivateFn = (route, state) => {
  const router = inject(Router);
  const getlocalData = inject(AuthService);

  const token = getlocalData.getAuthorizationToken();

  if (token) {
    if (state.url !== '/user') {
      return router.createUrlTree(['/user/home']);
    }
    return true;
  }

  return true;
};

export const authGuarLoginStatus: CanActivateFn = (route, state) => {
  const router = inject(Router);
  const getlocalData = inject(AuthService);
  const token = getlocalData.getAuthorizationToken();

  if (token) {
    if (state.url === '/user') {
      return true;
    }

    return router.createUrlTree(['/user']);
  }

  if (state.url === '/auth/login') {
    return true;
  }

  if (
    state?.url?.includes('register') ||
    state?.url?.includes('change-password') ||
    state?.url?.includes('forget-password') ||
    state?.url?.includes('remove-device')
  ) {
    return true;
  }

  return router.createUrlTree(['/auth/login'], {
    queryParams: route.queryParams
  });
};