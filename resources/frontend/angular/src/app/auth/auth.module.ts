import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { NgSelectModule } from '@ng-select/ng-select';
import { ForgetPasswordComponent } from './forget-password/forget-password.component';
import { RemoveDeviceComponent } from './remove-device/remove-device.component';
import { ChangepasswordComponent } from './changepassword/changepassword.component';
const routes: Routes = [
  {
    path: 'login',
    component: LoginComponent,
  },
  {
    path: 'register',
    component: RegisterComponent,
  },
  {
    path: 'change-password',
    component: ChangepasswordComponent,
  },
  {
    path: 'forget-password',
    component: ForgetPasswordComponent,
  },
  {
    path: 'remove-device',
    component: RemoveDeviceComponent,
  },
];

@NgModule({
  declarations: [],
  imports: [CommonModule, RouterModule.forChild(routes),NgSelectModule],
})
export class AuthModule {}
