import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgFor, NgIf } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-remove-device',
  standalone: true,
  imports: [NgFor,NgIf,RouterLink],
  templateUrl: './remove-device.component.html',
  styleUrl: './remove-device.component.scss',
})
export class RemoveDeviceComponent implements OnInit {
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
  }

  removedevice(id: string): void {
    console.log('---device id ',id);
    this.apiService.postRequest('auth/same-device-id-logout', { 'id': id }).subscribe({
      next: (res: any) => {
        if (res.response_code == 200) {
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
}
