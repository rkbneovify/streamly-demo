
import { HttpInterceptorFn, HttpRequest, HttpHandler, HttpEvent, HttpErrorResponse } from '@angular/common/http';
import { inject } from '@angular/core';
import { AuthService } from '../services/authService/authservice.service';
import { catchError } from 'rxjs/operators';
import { throwError } from 'rxjs';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { UtilsService } from '../services/utils/utils.service';
import { DataSharingService } from '../services/data-sharing.service';



export const authInterceptor: HttpInterceptorFn = (req: HttpRequest<any>, next) => {
  const authService = inject(AuthService);
  const authToken = authService.getAuthorizationToken();
  const toast = inject(ToastrService);
  const route = inject(Router);
  const setLocal = inject(UtilsService);
  const sendData = inject(DataSharingService);

  let authReq = req;
  if (authToken) {
    authReq = req.clone({
      headers: req.headers.set('Authorization', `Bearer ${authToken}`)
    });
  }

  return next(authReq).pipe(
    catchError((err: any) => {
      if (err instanceof HttpErrorResponse) {
        if (err.status === 0) {
          localStorage.clear();
          route.navigateByUrl('/auth/login');
        }
        // Handle HTTP errors
        else if (err.error.response_code === 401 && err.error.message == 'Token is Invalid') {
          localStorage.clear();
          route.navigateByUrl('/auth/login');
          toast.success('You are logged out');
          console.error('HTTP error:', err);
          // You might trigger a re-authentication flow or redirect the user here
        } else if (err.error.response_code === 410) {
          const deviceInfoData = JSON.parse(localStorage.getItem('deviceInfo') || '');
          if (deviceInfoData !== null) {
            try {
              if (err.error.hoa_sub_user == true) {
                toast.error(err.error.message);


              }

              else {
                // setLocal.setLocalStorage('cancelMessage',err.error.message);
                sendData.changeData(err.error.message);
                route.navigateByUrl('user/subscription-cancel');
                // with due bill url

                deviceInfoData.subscribed = false;
                localStorage.setItem('deviceInfo', JSON.stringify(deviceInfoData));
              }
            } catch (error) {
              console.error('Error parsing JSON data:', error);
            }
          }
        }
      } else {
        return throwError(() => err);
      }

      // Re-throw the error to propagate it further
      return throwError(() => err);
    })
  );
};
