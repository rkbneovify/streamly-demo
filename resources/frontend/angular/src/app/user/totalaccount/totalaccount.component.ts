import { Component } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { DatePipe, NgClass, NgFor, NgIf } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';
import { TimeAgoPipe } from '../../pipe/time-ago.pipe';
import { SortByDropdownComponent } from "../../components/sort-by-dropdown/sort-by-dropdown.component";
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators, ɵInternalFormsSharedModule } from '@angular/forms';
import { SweetalertService } from '../../services/SweetAlert/sweetalert.service';
import { FormControl } from '@angular/forms';
import { debounceTime, distinctUntilChanged, switchMap, catchError, of } from 'rxjs';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
@Component({
  selector: 'app-totalaccount',
  standalone: true,
  imports: [NgFor, NgIf, NgClass, TimeAgoPipe, SortByDropdownComponent, ɵInternalFormsSharedModule, ReactiveFormsModule, InfiniteScrollDirective],
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
  addHouseForm: FormGroup;
  isLoadingAddHomeForm: Boolean = false;
  isLoadingItemAction: Boolean = false;
  houseId: number = null;
  activeHouseCount: number = null;
  deactiveHouseCount: number = null;
  deviceLimitPerHouse: number = null;
  page: number = 1;
  status: number | null = null;
  totalHouses: number = 0;
  txtSearch = new FormControl('');
  isSearch: boolean = false;
  isMoreData: Boolean = true;
  constructor(private apiService: CrudService, private toastr: ToastrService, private fb: FormBuilder, private sweetAlert: SweetalertService) {
    this.get_all_device();

  }

  ngOnInit() {
    this.initializeForms();
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
      this.getHouseList(this.page, null, null);
    }

    this.txtSearch.valueChanges.pipe(
      debounceTime(400),
      distinctUntilChanged()
    ).subscribe(txt => {
      this.housesList = [];
      this.page = 1;
      this.isSearch = false;
      if (txt.length > 0) {
        this.isSearch = true;
      }

      this.getHouseList(this.page, null, txt);
    })

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


  getHouseList(page: number = 1, status: number | null, search: string | null) {
    const houseListUrl: string = 'auth/hoa/house-list';

    const params = new URLSearchParams();

    params.set('page', String(page));

    if (status === 0 || status === 1) {
      params.set('status', String(status));

    }
    if (search != null && search.length > 0) {
      this.isLoading = false;
      params.set('q', search);

    }
    if (this.isMoreData) {
      this.isLoading = true;
      this.apiService.getRequest(`${houseListUrl}?${params.toString()}`).subscribe({
        next: (res: any) => {
          this.isLoading = false;

          this.housesList = [...new Set([...this.housesList, ...res.data])];

          this.activeHouseCount = res.active_house_count;
          this.deactiveHouseCount = res.deactive_house_count;
          this.deviceLimitPerHouse = res.device_limit_per_house;
          this.totalHouses = res.total_houses;
          this.isMoreData = res.is_more_data;

        },
        error: (err: any) => {
          this.isLoading = false;
          this.toastr.error(err.error.message);
        }
      })
    }

  }
  onSortChange(status: number | null) {
    this.page = 1;
    this.housesList = [];
    this.status = status;
    this.getHouseList(this.page, this.status, null);
  }

  showHomeAddPopUp() {
    this.isLoadingAddHomeForm = false;
    document.getElementById('btnshowPopAddHouse').click();


  }



  private initializeForms() {


    this.addHouseForm = this.fb.group({

      email: ['', [Validators.required, Validators.email]],
      device_limit: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]+$/)
      ]],
      plan_in_month: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]+$/)
      ]]




    });




  }

  clearTxtSearch() {
    this.txtSearch.reset();
    this.housesList = [];
    this.getHouseList(1, null, null);
  }

  addHomeOnSubmit() {
    this.addHouseForm.markAllAsTouched();

    // console.log("form : ", this.addHouseForm.value)
    if (this.addHouseForm.valid) {
      this.isLoadingAddHomeForm = true;
      const data = {
        ...this.addHouseForm.value
      };
      this.apiService.postRequest("auth/hoa/add-house", data).subscribe({
        next: (res: any) => {

          this.isLoading = false;

          if (res.success == true) {
            this.toastr.success(res.message);
            this.getHouseList(this.page, null, null);
          }
          document.getElementById('btnReset').click();
          document.getElementById('btnshowPopAddHouse').click();
        },
        error: (err: any) => {
          this.isLoading = false;
          this.toastr.error(err.error?.message);
        },
      });
    }
  }

  deleteHouse(houseId: number) {
    this.sweetAlert.confirm("Delete House", "Do you want to delete this house?")
      .then(result => {
        if (result.isConfirmed) {
          this.houseId = houseId;
          this.sweetAlert.loading();
          this.apiService.deleteRequest(`auth/hoa/delete-house/${houseId}`).subscribe({
            next: (res: any) => {
              this.sweetAlert.close();
              if (res.success == true) {
                this.toastr.success(res.message);
                this.getHouseList(this.page, null, null);
              }

            },
            error: (err: any) => {
              this.sweetAlert.close();
              this.toastr.error(err.error?.message);
            }
          })
        }
      });



  }

  deactiveHouse(houseId: number) {
    this.sweetAlert.confirm("Deactive House", "Do you want to deactive this house?")
      .then(result => {
        if (result.isConfirmed) {
          this.houseId = houseId;
          this.sweetAlert.loading();

          this.apiService.putRequest(`auth/hoa/status-change/${houseId}`).subscribe({
            next: (res: any) => {
              this.sweetAlert.close();
              if (res.success == true) {
                this.toastr.success(res.message);
                this.getHouseList(this.page, null, null);
              }

            },
            error: (err: any) => {
              this.sweetAlert.close();
              this.toastr.error(err.error?.message);
            }
          })
        }
      });
  }

  activeHouse(houseId: number) {
    this.sweetAlert.confirm("Active House", "Do you want to active this house?")
      .then(result => {
        if (result.isConfirmed) {
          this.houseId = houseId;

          this.apiService.putRequest(`auth/hoa/status-change/${houseId}`).subscribe({
            next: (res: any) => {
              this.sweetAlert.close();
              if (res.success == true) {
                this.toastr.success(res.message);
                this.getHouseList(this.page, null, null);
              }

            },
            error: (err: any) => {
              this.sweetAlert.close();
              this.toastr.error(err.error?.message);
            }
          })
        }
      });
  }

  resendMail(houseId: number) {
    this.sweetAlert.confirm("Send Mail", "Do you want to send mail?")
      .then(result => {
        if (result.isConfirmed) {
          this.houseId = houseId;

          this.apiService.postRequest(`auth/hoa/resend-verification-mail/${houseId}`).subscribe({
            next: (res: any) => {
              this.sweetAlert.close();
              if (res.success == true) {
                this.toastr.success(res.message);
                this.getHouseList(this.page, null, null);
              }

            },
            error: (err: any) => {
              this.sweetAlert.close();
              this.toastr.error(err.error?.message);
            }
          })
        }
      });
  }

  onScrollDown() {
    this.page++;
    this.getHouseList(this.page, this.status, this.txtSearch.value)
    console.log("Page count", this.page)
  }

  getExpiryText(item: any) {
    if (item.subscription_expire_in < 30 && item.subscription_expire_in > 0) {
      return `${item.subscription_expire_in} Days left`;
    }

    if (!item.subscription) return '-';

    const date = new Date(item.subscription.plan_expires_at);

    // Format manually to dd/MM/yyyy
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
  }

}
