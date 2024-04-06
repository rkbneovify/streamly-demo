import { Component } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgFor, NgIf } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-totalaccount',
  standalone: true,
  imports: [NgFor,NgIf],
  templateUrl: './totalaccount.component.html',
  styleUrl: './totalaccount.component.scss'
})
export class TotalaccountComponent {
  response: any;
  connectedDevice!: any;
  access_Token!:any;
  current_device:any;
  deviceId:Array<string>=[];
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
    console.log(this.response.length,'respose');
    
    this.connectedDevice = this.response;
    console.log(this.response.length,'----------data');
    // this.apiService.apiResponse$.subscribe((response) => {
    //   console.log('response api--->',response.error.loggedindeviceinfolist.data);      
    //   this.response = response;
    //   this.connectedDevice = response.error.loggedindeviceinfolist.data;   
    // });

   
  }


  get_all_device(){
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
        this.access_Token = JSON.parse(
          localStorage.getItem('deviceInfo') || ''
        );
        const tokenData = this.access_Token?.access_token;
        this.current_device = this.access_Token?.personaldeviceid;
        const headers = new HttpHeaders({
          Authorization: `Bearer ${tokenData}`,
          Accept: 'application/json',
        });
        console.log('token', headers);

        // if (this.updated_data) {
        let body = {
          
        };
        console.log('updated values -->>>', body);
        this.apiService
          .postRequest('auth/', body, headers)
          .subscribe({
            next:(res)=>console.log(res),
            error:(err)=>console.log(err)
          });
        // }
      } catch (error) {
        console.error('Error parsing JSON data:--->>>', error);
      }
    }
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
  this.apiService.postRequest('auth/multi-session-destroy', { 'id': id }).subscribe({
    next: (res: any) => {
      if (res.response_code == 200) {
        this.toastr.success('Device removed successfully')
        this.connectedDevice = this.connectedDevice.filter((data: any) => data.session_id !== id)
        this.response = this.connectedDevice;
        localStorage.setItem('loggedInDeviceDetail', JSON.stringify(this.response));
      }
    },
    error: err => this.toastr.error(err.error?.error)

  });
}

  removedevice(id: any): void {
    this.apiService.postRequest('auth/same-device-id-logout', { 'id': id }).subscribe({
      next: (res: any) => {
        if (res.response_code == 200) {
          this.toastr.success('Device removed successfully')
          this.connectedDevice = this.connectedDevice.filter((data: any) => data.session_id !== id)
          this.response = this.connectedDevice;
          localStorage.setItem('loggedInDeviceDetail', JSON.stringify(this.response));
        }
      },
      error: err => this.toastr.error(err.error?.error)

    });
  }
}
