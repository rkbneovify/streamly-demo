import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgClass, NgFor, NgIf } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { RouterLink } from '@angular/router';
import { HttpHeaders } from '@angular/common/http';
import { TimeAgoPipe } from '../../pipe/time-ago.pipe';


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
  constructor(private apiService: CrudService, private toastr: ToastrService) { }

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
          this.connectedDevice = this.connectedDevice.filter((data: any) => data.session_id !== id)
          this.response = this.connectedDevice;
          // console.log('res===> ',t)
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
