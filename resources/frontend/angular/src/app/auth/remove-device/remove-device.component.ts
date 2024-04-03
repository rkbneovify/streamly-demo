import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgFor } from '@angular/common';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-remove-device',
  standalone: true,
  imports: [NgFor],
  templateUrl: './remove-device.component.html',
  styleUrl: './remove-device.component.scss',
})
export class RemoveDeviceComponent implements OnInit {
  response: any;
  connectedDevice!: any;
  constructor(private apiService: CrudService, private toastr: ToastrService) { }

  ngOnInit() {
    this.response = JSON.parse(localStorage.getItem('permissions') || '');
    this.connectedDevice = this.response.error.loggedindeviceinfolist.data;
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
          this.response.error.loggedindeviceinfolist.data = this.response;
          localStorage.setItem('loggedInDeviceDetail', JSON.stringify(this.response));
        }
      },
      error: err => this.toastr.error(err.error?.error)

    });
  }
}
