import { Component, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { NgClass, NgIf } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [RouterLink, NgIf, ReactiveFormsModule, NgClass],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss',
})
export class LoginComponent implements OnInit {
  loginFormWithEmail!: FormGroup;
  selectedTab: string = 'loginwithpassword';
  showPassword: boolean = false;
  showPasscode: boolean = false;
  userDeviceID!: string;
  userToken!: string;
  constructor(private fb: FormBuilder, private crudService: CrudService, private router: Router, private ts: ToastrService) {
    this.loginFormWithEmail = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
    });
  }

  ngOnInit(): void {
    // Check if device ID is present in local storage
    const storedDeviceId = localStorage.getItem('deviceId');
    console.log('storedDeviceId--->>', storedDeviceId);
    if (storedDeviceId) {
      console.log(storedDeviceId);
      this.userDeviceID = storedDeviceId;
      const tokenId = localStorage.getItem('access_token');
      if (tokenId) {
        this.router.navigate(['/']);
      }
    } else {
      this.generateDeviceId();

    }
  }

  showtos() {
    this.ts.success('hello whow are you')
  }

  onSubmit() {
    if (this.loginFormWithEmail.valid) {
      const formData = this.loginFormWithEmail.value;
      formData.deviceid = this.userDeviceID;// Call the function to generate Device ID
      console.log(formData);
      this.crudService.postRequest('auth/login', formData).subscribe({
        next: (res: any) => {
          if (res.status === 200) {
            this.ts.success(res.message)
            let deviceInfo = {
              access_token : res.access_token,
              device_info: res.device_info,
              personaldeviceid: res.personaldeviceid,
              sessionid: res.sessionid,
              timezone: res.timezone,
              token_type: res.token_type,
              userrole: res.userrole 
            }
            localStorage.setItem('deviceInfo', JSON.stringify(deviceInfo));
            localStorage.setItem('userDetails', JSON.stringify(res.user));
            this.router.navigateByUrl('/user/home');
          }
        },
        error: (err: any) => {
          if (err.status == 479) {
            localStorage.setItem('loggedInDeviceDetail', JSON.stringify(err.error.loggedindeviceinfolist.data));
            this.router.navigate(['auth/remove-device']);

            // this.crudService.sendResponse(err.error.loggedindeviceinfolist.data);
            // this.router.navigate(['auth/remove-device']);
          } else {
            this.ts.error(err.error.message);
          }
        }
      });
    }
  }

  private generateDeviceId() {
    // this.userDeviceID = 'f5sd6f5sd5f4s65f4ew98fwe8fwe95dfd5f6def8'; localStorage.setItem('deviceId', this.userDeviceID);
    this.crudService.getRequest('auth/devicedetail').subscribe({
      next: (res: any) => { console.log('hey'); this.userDeviceID = res?.deviceid; localStorage.setItem('deviceId', this.userDeviceID); },
      error: err => console.log(err)
    });
  }


}
