import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgClass, NgFor, NgIf } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { Router, RouterLink } from '@angular/router';
import { HttpHeaders } from '@angular/common/http';
import { TimeAgoPipe } from '../../pipe/time-ago.pipe';
import { AuthService } from '../../services/authService/authservice.service';


@Component({
  selector: 'app-remove-device',
  standalone: true,
  imports: [NgFor,NgIf,RouterLink,NgClass,TimeAgoPipe],
  templateUrl: './remove-device.component.html',
  styleUrl: './remove-device.component.scss',
})
export class RemoveDeviceComponent implements OnInit {
  response: any;
  connectedDevice!: any;
  access_Token!:any;
  current_device:any;
  deviceId:Array<string>=[];
  loading: { [key: string]: boolean } = {};
  
  constructor(private apiService: CrudService, private toastr: ToastrService, private router: Router,private setToken:AuthService) { }

  ngOnInit() {
    const jsonData = localStorage.getItem('loggedInDeviceDetail');
    if (jsonData !== null) {
      try {
        this.response = JSON.parse(localStorage.getItem('loggedInDeviceDetail') || '');
      } catch (error) {
        console.error('Error parsing JSON data:', error);
      }
    }
    else
    console.log('not getting logged device');
    
    // this.response = JSON.parse(localStorage.getItem('loggedInDeviceDetail') || '');
    console.log(this.response,'respose');
    
    this.current_device = localStorage.getItem('deviceId');
    this.connectedDevice = this.response;
  }

  removedevice(id: string): void {
    console.log('---device id loading',this.loading);
    this.loading[id] = true;
    this.apiService.postRequest('auth/same-device-id-logout', { 'id': id }).subscribe({
      next: (res: any) => {
        if (res.response_code == 200) {
          // this.loading[id] = false;
          this.toastr.success('Device removed successfully')
          this.connectedDevice = this.connectedDevice.filter((data: any) => data.session_id !== id);
          this.response = this.connectedDevice;
          const userDetials = JSON.parse(localStorage.getItem('loginDetails'));
          if(userDetials){
            localStorage.removeItem('loggedInDeviceDetail');
            this.onSubmit(userDetials);
          }
          localStorage.setItem('loggedInDeviceDetail', JSON.stringify(this.response));
        }
      },
      error: err => this.toastr.error(err.error?.error)

    });
  }

  getDeviceId(event: any, sessionId: string): void {
    if (event.target.checked) {
      // If the checkbox is checked, add the session ID to the deviceId array
      this.deviceId.push(sessionId);
    } else {
      // If the checkbox is unchecked, remove the session ID from the deviceId array
      this.deviceId = this.deviceId.filter(id => id !== sessionId);
    }
    console.log('Device IDs:', this.deviceId);
  }


  mutileLogout(){
    if(this.deviceId.length >0){
      this.removedevices(this.deviceId);
    }
  }

  onSubmit(data:any) {
      const formData = data;
      this.apiService.postRequest('auth/login', formData).subscribe({
        next: (res: any) => {
          console.log(res,'response of login api');
          if (res.response_code == 200) {
            if(res.user.forced_change == 1){
              localStorage.setItem('changeToken',res?.access_token);
              this.router.navigateByUrl(`auth/change-password`);
            }
          else{
            console.log('login resp-->',res);
            this.toastr.success(res?.message)
            const token = res?.access_token
            let deviceInfo = {
              access_token : res?.access_token,
              device_info: res?.device_info,
              personaldeviceid: res?.personaldeviceid,
              sessionid: res?.sessionid,
              timezone: res?.timezone,
              token_type: res?.token_type,
              userrole: res?.userrole,
              subscribed:res?.subscribed
            }
            localStorage.setItem('deviceInfo', JSON.stringify(deviceInfo));
            this.setToken.setAuthorizationToken(token);
            localStorage.setItem('userDetails', JSON.stringify(res?.user));
            this.router.navigateByUrl('/user');
          }
          }
        },
        error: (err: any) => {
          if (err.status == 479) {
            localStorage.setItem('loggedInDeviceDetail', JSON.stringify(err.error.loggedindeviceinfolist.data));
            console.log('479 ===>' ,formData);
            localStorage.setItem('loginDetails',JSON.stringify(formData));
            this.router.navigate(['auth/remove-device']);
            this.toastr.error(err.error.message,null, {
              closeButton: true, // Enable close button
              tapToDismiss: false, // Disable dismiss on click
              timeOut:4000
            }
              );
          } else {
            this.toastr.error(err.error.message);
          }

          if(err.status == 429){
            this.router.navigate(['user']);

          }
        }
      });
  }
  
  removedevices(id: any): void {
    const userToken = localStorage.getItem('deviceInfo');
    let headers;
    if (userToken !== null) {
        this.access_Token = JSON.parse(localStorage.getItem('deviceInfo') || '');
        const tokenData = this.access_Token?.access_token;
        this.current_device = this.access_Token?.personaldeviceid;
        headers = new HttpHeaders({
          'Authorization': `Bearer ${tokenData}`,
          'Accept': 'application/json'
        });
      }
    this.apiService.postRequest('auth/multi-session-destroy', { 'id': id },headers).subscribe({
      next: (res: any) => {
        if (res.jwterror == 'no error') {
          this.toastr.success('Devices removed successfully')
          this.connectedDevice = this.connectedDevice.filter((data: any) => data.session_id !== id)
          this.response = this.connectedDevice;
          localStorage.setItem('loggedInDeviceDetails', JSON.stringify(this.response));
        }
      },
      error: err =>{ this.toastr.error(err.error?.error); console.log(err)},
  
    });
  }
}
