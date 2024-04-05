import { Component } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgFor } from '@angular/common';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-totalaccount',
  standalone: true,
  imports: [NgFor],
  templateUrl: './totalaccount.component.html',
  styleUrl: './totalaccount.component.scss'
})
export class TotalaccountComponent {
  response: any;
  connectedDevice!: any;
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
    
    this.connectedDevice = this.response;
    // this.apiService.apiResponse$.subscribe((response) => {
    //   console.log('response api--->',response.error.loggedindeviceinfolist.data);      
    //   this.response = response;
    //   this.connectedDevice = response.error.loggedindeviceinfolist.data;   
    // });
  }

  removedevice(id: string): void {
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
