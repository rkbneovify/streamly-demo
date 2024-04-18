import { Injectable, inject } from '@angular/core';
import { CanActivateFn, Route, Router } from '@angular/router';

@Injectable()
class checkUserAccess{
  token:any ;
  constructor(private router:Router){
    debugger
    console.log(this.token,'======af=====>')
    this.token = JSON.parse(localStorage.getItem('deviceInfo') || '')?.access_Token;
    console.log(this.token,'===========>')
  }
  tokenAccess():boolean{
    if(this.token){
      return true
    }else{
      this.router.navigateByUrl('auth/login');
      return false
    }
  }
}


export const authguardGuard: CanActivateFn = (route, state) => {
  return inject(checkUserAccess).tokenAccess();
};
