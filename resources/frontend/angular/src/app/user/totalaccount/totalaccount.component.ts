import { Component } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgClass, NgFor, NgIf } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';
import { TimeAgoPipe } from '../../pipe/time-ago.pipe';
import { SortByDropdownComponent } from "../../components/sort-by-dropdown/sort-by-dropdown.component";

@Component({
  selector: 'app-totalaccount',
  standalone: true,
  imports: [NgFor, NgIf, NgClass, TimeAgoPipe, SortByDropdownComponent],
  templateUrl: './totalaccount.component.html',
  styleUrl: './totalaccount.component.scss',
})
export class TotalaccountComponent {
  response: any;
  connectedDevice!: any;
  access_Token!: any;
  current_device: any;
  numOfDevice: number = 0;
  deviceId: Array<string> = [];
  isLoading: boolean = false;
  isConnectedDeviceShow: boolean = false;
  isHoaMainUser: boolean = false;
  devicePerHouse: Array<number> = [];
  userData: any;
  housesList: Array<any> = [];
  constructor(private apiService: CrudService, private toastr: ToastrService) {
    this.get_all_device();
  }

  ngOnInit() {
    const jsonData = localStorage.getItem('loggedInDeviceDetails');
    const isHoaMain = localStorage.getItem('is_hoa_main_user');
    const userDetails = localStorage.getItem('userDetails');
    if (userDetails !== null) {
      this.userData = JSON.parse(
        localStorage.getItem('userDetails') || ''
      );
      this.devicePerHouse = Array.from(
        { length: Number(this.userData.device_per_house) },
        (_, i) => i + 1
      );


    }
    if (isHoaMain !== null) {
      this.isHoaMainUser = (isHoaMain === 'true');
    }
    if (jsonData !== null) {
      try {
        let data = JSON.parse(
          localStorage.getItem('loggedInDeviceDetails') || ''
        );

        data = data.sort((a: any, b: any) => {
          // If the session ID matches the current user's device ID, move it to the top
          if (a.personaldeviceid === this.current_device) return -1;
          if (b.personaldeviceid === this.current_device) return 1;
          return 0;
        });
        this.response = data;
      } catch (error) {
        console.error('Error parsing JSON data:', error);
      }
    } else console.log('not getting logged device');
    this.connectedDevice = this.response;

    if (this.isHoaMainUser) {
      this.getHouseList();
    }

  }

  get_all_device() {
    this.isLoading = true;
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
        this.apiService
          .getRequest('auth/newconnecteddevice', headers)
          .subscribe((res: any) => {
            if (res.response_code == 200) {
              let data = res.data;
              this.numOfDevice = +res.userDeviceLimit;
              this.isConnectedDeviceShow = res.user_connected_device
              data = data.sort((a: any, b: any) => {
                // If the session ID matches the current user's device ID, move it to the top
                if (a.personaldeviceid === this.current_device) return -1;
                if (b.personaldeviceid === this.current_device) return 1;
                return 0;
              });
              this.connectedDevice = data;
              localStorage.setItem(
                'loggedInDeviceDetails',
                JSON.stringify(data)
              );
              this.isLoading = false;
            } else {
              this.toastr.error(res.message);
              this.isLoading = false;
            }
          });
      } catch (error) {
        console.error('Error parsing JSON data:', error);
        this.isLoading = false;
      }
    }
  }

  getDeviceId(event: any, sessionId: string): void {
    if (event.target.checked) {
      // If the checkbox is checked, add the session ID to the deviceId array
      this.deviceId.push(sessionId);
    } else {
      // If the checkbox is unchecked, remove the session ID from the deviceId array
      this.deviceId = this.deviceId.filter((id) => id !== sessionId);
    }
  }

  mutileLogout() {
    if (this.deviceId.length > 0) {
      this.removedevices(this.deviceId);
    } else {
      this.toastr.error('Choose a device');
    }
  }

  removedevices(id: any[]): void {
    this.isLoading = true;
    const userToken = localStorage.getItem('deviceInfo');
    let headers;

    if (userToken !== null) {
      this.access_Token = JSON.parse(localStorage.getItem('deviceInfo') || '');
      const tokenData = this.access_Token?.access_token;
      this.current_device = this.access_Token?.personaldeviceid;
      headers = new HttpHeaders({
        Authorization: `Bearer ${tokenData}`,
        Accept: 'application/json',
      });
    }

    this.apiService
      .postRequest('auth/multi-session-destroy', { id: id }, headers)
      .subscribe({
        next: (res: any) => {
          if (res.jwterror == 'no error') {
            this.toastr.success('Devices removed successfully');

            // Filter out the removed devices from the connectedDevice array
            id.forEach(sessionId => {
              this.connectedDevice = this.connectedDevice.filter(
                (device: any) => device.session_id !== sessionId
              );
            });

            this.response = this.connectedDevice;
            localStorage.setItem(
              'loggedInDeviceDetails',
              JSON.stringify(this.response)
            );

            this.deviceId = [];
            this.isLoading = false;
          }
        },
        error: (err) => {
          this.toastr.error(err.error?.error);
          this.isLoading = false;
        },
      });
  }



  removedevice(id: any): void {
    this.isLoading = true;
    this.apiService
      .postRequest('auth/same-device-id-logout', { id: id })
      .subscribe({
        next: (res: any) => {
          if (res.response_code == 200) {
            this.toastr.success('Device removed successfully');
            this.connectedDevice = this.connectedDevice.filter(
              (data: any) => data.session_id !== id
            );
            this.response = this.connectedDevice;
            localStorage.setItem(
              'loggedInDeviceDetails',
              JSON.stringify(this.response)
            );
            this.get_all_device();
            this.isLoading = false;
          }
        },
        error: (err) => {
          this.toastr.error(err.error?.error);
          this.isLoading = false;
        },
      });
  }


  getHouseList() {

    this.isLoading = true;
    this.apiService.getRequest("auth/hoa/house-list?page=1").subscribe({
      next: (res: any) => {
        this.isLoading = false;
        // console.log("house list :", res);
        this.housesList = res.data;

      },
      error: (err: any) => {
        this.isLoading = false;
        this.toastr.error(err.error.message);
      }
    })
  }
  showHomeAddPopUp() {
    document.getElementById('btnShowPopDeleteAccount').click();
  }

  onFruitSelected(value: string) {
    console.log("Selected:", value);
  }
}
