import { Component } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import {  NgClass, NgFor, NgIf } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';
import { TimeAgoPipe } from '../../pipe/time-ago.pipe'

@Component({
  selector: 'app-totalaccount',
  standalone: true,
  imports: [NgFor,NgIf,NgClass, TimeAgoPipe],
  templateUrl: './totalaccount.component.html',
  styleUrl: './totalaccount.component.scss'
})
export class TotalaccountComponent {
  response: any;
  connectedDevice!: any;
  access_Token!:any;
  current_device:any;
  numOfDevice:number=0
  deviceId:Array<string>=[];
  constructor(private apiService: CrudService, private toastr: ToastrService ) { 
    this.get_all_device();
  }

  ngOnInit() {
    const jsonData = localStorage.getItem('loggedInDeviceDetails');
    if (jsonData !== null) {
      try {
        let data = JSON.parse(localStorage.getItem('loggedInDeviceDetails') || '');
        console.log(data,'===============before ==============> ',this.current_device);

        data = data.sort((a: any, b: any) => {
          // If the session ID matches the current user's device ID, move it to the top
          if (a.personaldeviceid === this.current_device) return -1;
          if (b.personaldeviceid === this.current_device) return 1;
          return 0;
        });
        console.log(data,'===============after Data ==============>',this.current_device);
        this.response = data;
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

// get all the devices---
  // get_all_device(){
  //   const userToken = localStorage.getItem('deviceInfo');
  //   if (userToken !== null) {
  //     try {
  //       this.access_Token = JSON.parse(
  //         localStorage.getItem('deviceInfo') || ''
  //       );
  //       const tokenData = this.access_Token?.access_token;
  //       this.current_device = this.access_Token?.personaldeviceid;
  //       const headers = new HttpHeaders({
  //         Authorization: `Bearer ${tokenData}`,
  //         Accept: 'application/json',
  //       });
  //       console.log('token', headers);

  //       // if (this.updated_data) {
  //       let body = {
          
  //       };
  //       console.log('updated values -->>>', body);
  //       this.apiService
  //         .postRequest('auth/', body, headers)
  //         .subscribe({
  //           next:(res)=>console.log(res),
  //           error:(err)=>console.log(err)
  //         });
  //       // }
  //     } catch (error) {
  //       console.error('Error parsing JSON data:--->>>', error);
  //     }
  //   }
  // }


  get_all_device (){
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
        this.access_Token = JSON.parse(localStorage.getItem('deviceInfo') || '');
        const tokenData = this.access_Token?.access_token;
        this.current_device = this.access_Token?.personaldeviceid;
        const headers = new HttpHeaders({
          'Authorization': `Bearer ${tokenData}`,
          'Accept': 'application/json'
        });
        console.log(tokenData,'--------token');
        this.apiService.getRequest('auth/connecteddevice', headers).subscribe((res: any) => {
          console.log('get all device ->>',res);
          if(res.response_code == 200){
            let data = res.data;
            this.numOfDevice = +res.numofdevice;
            // Sort the connected devices array based on the current user's device ID
            data = data.sort((a: any, b: any) => {
              // If the session ID matches the current user's device ID, move it to the top
              if (a.personaldeviceid === this.current_device) return -1;
              if (b.personaldeviceid === this.current_device) return 1;
              return 0;
            });
            this.connectedDevice = data;
            localStorage.setItem('loggedInDeviceDetails',JSON.stringify(data));
          }else{
            this.toastr.error(res.message);
          }
        });
      } catch (error) {
          console.error('Error parsing JSON data:--->>>', error);
      }
  }
    // console.log('hello');
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
  }else{
    this.toastr.error('Choose a device');
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

  removedevice(id: any): void {
    this.apiService.postRequest('auth/same-device-id-logout', { 'id': id }).subscribe({
      next: (res: any) => {
        if (res.response_code == 200) {
          this.toastr.success('Device removed successfully')
          this.connectedDevice = this.connectedDevice.filter((data: any) => data.session_id !== id)
          this.response = this.connectedDevice;
          localStorage.setItem('loggedInDeviceDetails', JSON.stringify(this.response));
          this.get_all_device ()
        }
      },
      error: err => this.toastr.error(err.error?.error)

    });
  }
}
