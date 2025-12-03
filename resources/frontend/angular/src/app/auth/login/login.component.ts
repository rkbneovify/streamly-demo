import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { NgClass, NgIf } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { AuthService } from '../../services/authService/authservice.service';

interface QrCode {
  key: string;
  msg: string;
  status: number;
}

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [NgIf, ReactiveFormsModule, NgClass, RouterLink],
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
  queryTab: string | null = null;
  qrCodeImage: string = null;

  constructor(
    private fb: FormBuilder,
    private crudService: CrudService,
    private router: Router,
    private toastr: ToastrService,
    private authService: AuthService,
    private activatedRoute: ActivatedRoute
  ) {
    this.initializeForms();
  }

  ngOnInit(): void {
    this.checkStoredDeviceId();
    this.createQRCode();
    this.queryTab = this.activatedRoute.snapshot.queryParamMap.get('tab');

    switch (this.queryTab) {
      case "passcode":
        this.selectedTab = "loginwithpasscode";
        break;

      case "qr":
        this.selectedTab = "loginwithqr";
        break;


      default:
        this.selectedTab = "loginwithpassword";
        break;
    }
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
    this.checkLocationAccess(() => {
      if (this.loginFormWithEmail.valid) {
        const formData = { ...this.loginFormWithEmail.value, deviceid: this.userDeviceID };
        this.authenticateUser('auth/login', formData);
      }
    });
  }

  onSubmitpasscode() {
    this.checkLocationAccess(() => {
      if (this.loginPasscodeForm.valid) {
        const formData = { ...this.loginPasscodeForm.value, deviceid: this.userDeviceID };
        this.authenticateUser('auth/login-with-passcode', formData);
      }
    });
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
      if (res.user.forced_change === 1 && res.user.is_custom_user === 0) {

        this.loading = false;
        localStorage.setItem('changeToken', res.access_token);
        this.router.navigateByUrl('auth/change-password');
      } else {
        this.storeUserData(res);
        this.toastr.success(res.message);
        this.router.navigateByUrl('/user/playlistschedule');
      }
    }
  }

  private checkLocationAccess(callback: () => void) {
    const locationData = localStorage.getItem('userLocation');
    const locationDenied = localStorage.getItem('locationDenied');

    if (locationData) {
      callback(); // Location is already available, proceed with login
    } else if (locationDenied) {
      // User previously denied location, prompt them to enable it
      this.toastr.warning('Location access is disabled. Please enable it in your browser settings.', null, {
        closeButton: true, // Show close button
        timeOut: 0, // Prevent auto-close
        tapToDismiss: false, // Disable closing on click anywhere
        extendedTimeOut: 0, // Prevent closing after some delay
      });

    } else {
      // First time asking for location
      this.requestLocationPermission(callback);
    }
  }


  // private checkLocationAccess(callback: () => void) {
  //   const storedLocation = localStorage.getItem('userLocation');

  //   if (storedLocation) {
  //     callback(); // Location exists, proceed with login
  //   } else {
  //     this.requestLocationPermission(callback);
  //   }
  // }

  private async getZipcodeFromCoordinates(latitude: number, longitude: number): Promise<string> {
    const url = `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      return data.address.postcode || 'Unknown';
    } catch (error) {
      console.error('Error fetching zipcode:', error);
      throw error;
    }
  }

  private requestLocationPermission(callback: () => void) {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        async (position) => {
          const { latitude, longitude } = position.coords;
          try {
            const zipcode = await this.getZipcodeFromCoordinates(latitude, longitude);
            const locationData = { latitude, longitude, zipcode };

            localStorage.setItem('userLocation', JSON.stringify(locationData));
            localStorage.removeItem('locationDenied'); // Clear flag if granted
            callback(); // Proceed with login
          } catch (error) {
            this.toastr.error('Failed to fetch location details. Please try again.');
          }
        },
        (error) => {
          if (error.code === error.PERMISSION_DENIED) {
            localStorage.setItem('locationDenied', 'true'); // Store denial flag
            this.toastr.error('Location access is required. Enable it in browser settings.', 'Error', {
              closeButton: true, // Show close button
              timeOut: 0, // Prevent auto-close
              tapToDismiss: false, // Disable closing on click anywhere
              extendedTimeOut: 0,
            });
          } else {
            this.toastr.error('Unable to retrieve location. Please try again.');
          }
        }
      );
    } else {
      this.toastr.error('Geolocation is not supported by your browser.');
    }
  }



  // private requestLocationPermission(callback: () => void) {
  //   if (navigator.geolocation) {
  //     navigator.geolocation.getCurrentPosition(
  //       async (position) => {
  //         const { latitude, longitude } = position.coords;

  //         try {
  //           const zipcode = await this.getZipcodeFromCoordinates(latitude, longitude);
  //           const locationData = { latitude, longitude, zipcode };

  //           localStorage.setItem('userLocation', JSON.stringify(locationData));
  //           // this.toastr.success(`Location access granted. Zipcode: ${zipcode}`);
  //           callback(); // Proceed with login after location is obtained
  //         } catch (error) {
  //           this.toastr.error('Failed to fetch location details. Please try again.');
  //         }
  //       },
  //       (error) => {
  //         if (error.code === error.PERMISSION_DENIED) {
  //           this.toastr.error('Location access is required to log in. Please enable it in browser settings.');
  //         } else {
  //           this.toastr.error('Unable to retrieve location. Please try again.');
  //         }
  //       }
  //     );
  //   } else {
  //     this.toastr.error('Geolocation is not supported by your browser.');
  //   }
  // }



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
    localStorage.setItem('is_hoa_main_user', JSON.stringify(res.is_hoa_main_user));
    localStorage.setItem('hoa_user', JSON.stringify(res.hoa_user));

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

  createQRCode() {
    this.crudService.postRequest('login/create/qrcode', {}).subscribe({
      next: (res: QrCode) => {
        this.qrCodeImage = res.msg;
      },
      error: (err) => {
        console.log(err);
      }
    })
  }
}
