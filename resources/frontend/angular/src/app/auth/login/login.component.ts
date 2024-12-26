import { Component, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { NgClass, NgIf } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { AuthService } from '../../services/authService/authservice.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [NgIf, ReactiveFormsModule, NgClass,RouterLink],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss',
})
export class LoginComponent implements OnInit {
  loginFormWithEmail!: FormGroup;
  loginPasscodeForm!: FormGroup;
  selectedTab: string = 'loginwithpassword';
  showPassword = false;
  showPasscode = false;
  userDeviceID!: string;
  loading = false;

  constructor(
    private fb: FormBuilder,
    private crudService: CrudService,
    private router: Router,
    private toastr: ToastrService,
    private authService: AuthService
  ) {
    this.initializeForms();
  }

  ngOnInit(): void {
    this.checkStoredDeviceId();
  }

  private initializeForms() {
    this.loginFormWithEmail = this.fb.group({
      email: [
        '',
        [
          Validators.required,
          Validators.email,
          Validators.pattern(/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i),
        ],
      ],
      password: ['', [Validators.required, Validators.minLength(6)]],
    });

    this.loginPasscodeForm = this.fb.group({
      passcode: ['', Validators.required],
    });
  }

  private checkStoredDeviceId() {
    const storedDeviceId = localStorage.getItem('deviceId');
    if (storedDeviceId) {
      this.userDeviceID = storedDeviceId;
      const tokenId = localStorage.getItem('access_token');
      if (tokenId) this.router.navigate(['/']);
    } else {
      this.generateDeviceId();
    }
  }

  onSubmit() {
    if (this.loginFormWithEmail.valid) {
      const formData = { ...this.loginFormWithEmail.value, deviceid: this.userDeviceID };
      this.authenticateUser('auth/login', formData);
    }
  }

  onSubmitpasscode() {
    if (this.loginPasscodeForm.valid) {
      const formData = { ...this.loginPasscodeForm.value, deviceid: this.userDeviceID };
      this.authenticateUser('auth/login-with-passcode', formData);
    }
  }

  private authenticateUser(apiEndpoint: string, formData: any) {
    this.loading = true;

    this.crudService.postRequest(apiEndpoint, formData).subscribe({
      next: (res: any) => this.handleSuccessResponse(res),
      error: (err: any) => this.handleErrorResponse(err, formData),
    });
  }

  private handleSuccessResponse(res: any) {
    if (res.response_code === 200) {
      if (res.user.forced_change === 1) {
        this.loading = false;
        localStorage.setItem('changeToken', res.access_token);
        this.router.navigateByUrl('auth/change-password');
      } else {
        this.storeUserData(res);
        this.toastr.success(res.message);
        this.router.navigateByUrl('/user');
      }
    }
  }

  private handleErrorResponse(err: any, formData: any) {
    this.loading = false;

    if (err.status === 479) {
      this.handleDeviceLimitError(err, formData);
    } else if (err.status === 429) {
      this.router.navigate(['user']);
    } else {
      this.toastr.error(err.error.message);
    }
  }

  private handleDeviceLimitError(err: any, formData: any) {
    localStorage.setItem('loggedInDeviceDetail', JSON.stringify(err.error.loggedindeviceinfolist.data));
    localStorage.setItem('numberOfDevices', JSON.stringify(err.error.loggedindeviceinfolist.numofdevice));
    localStorage.setItem('loginDetails', JSON.stringify(formData));
    this.router.navigate(['auth/remove-device']);
    this.toastr.error(err.error.message, null, {
      closeButton: true,
      tapToDismiss: false,
      timeOut: 3000,
    });
  }

  private storeUserData(res: any) {
    const token = res.access_token;
    const deviceInfo = {
      access_token: res.access_token,
      device_info: res.device_info,
      personaldeviceid: res.personaldeviceid,
      sessionid: res.sessionid,
      timezone: res.timezone,
      token_type: res.token_type,
      userrole: res.userrole,
      subscribed: res.subscribed,
    };

    localStorage.setItem('deviceInfo', JSON.stringify(deviceInfo));
    this.authService.setAuthorizationToken(token);
    localStorage.setItem('userDetails', JSON.stringify(res.user));
  }

  private generateDeviceId() {
    this.crudService.getRequest('auth/devicedetail').subscribe({
      next: (res: any) => {
        this.userDeviceID = res.deviceid;
        localStorage.setItem('deviceId', this.userDeviceID);
      },
      error: (err) => console.error('Error generating device ID:', err),
    });
  }
}
