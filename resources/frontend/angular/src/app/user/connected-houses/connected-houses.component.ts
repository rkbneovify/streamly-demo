import { Component } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { DatePipe, NgClass, NgFor, NgIf } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { TimeAgoPipe } from '../../pipe/time-ago.pipe';
import { SortByDropdownComponent } from "../../components/sort-by-dropdown/sort-by-dropdown.component";
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators, ɵInternalFormsSharedModule } from '@angular/forms';
import { SweetalertService } from '../../services/SweetAlert/sweetalert.service';
import { FormControl } from '@angular/forms';
import { debounceTime, distinctUntilChanged, switchMap, catchError, of } from 'rxjs';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
import { LocalDatePipe } from '../../pipe/localDate/local-date.pipe';
import { NgSelectModule } from '@ng-select/ng-select';

@Component({
  selector: 'app-connected-houses',
  standalone: true,
  imports: [NgFor, NgIf, NgClass, NgSelectModule, FormsModule, DatePipe, SortByDropdownComponent, ɵInternalFormsSharedModule, ReactiveFormsModule, InfiniteScrollDirective],
  templateUrl: './connected-houses.component.html',
  styleUrl: './connected-houses.component.scss'
})
export class ConnectedHousesComponent {

  isHoaMainUser: boolean = false;
  devicePerHouse: Array<number> = [];
  userData: any;
  housesList: Array<any> = [];
  addHouseForm: FormGroup;
  editHouseForm: FormGroup;
  addPlanForm: FormGroup;
  isLoadingAddPlanForm: Boolean = false;
  isLoadingAddHomeForm: Boolean = false;
  isLoadingEditHomeForm: Boolean = false;
  isLoadingItemAction: Boolean = false;
  houseId: number = null;
  activeHouseCount: number = null;
  deactiveHouseCount: number = null;
  deviceLimitPerHouse: number = null;
  totalDevice: number = null;
  allotedDeviceCount: number = null;
  page: number = 1;
  status: number | string | null = null;
  totalHouses: number = 0;
  txtSearch = new FormControl('');
  isSearch: boolean = false;
  isMoreData: Boolean = true;
  isCopied: Boolean = false;
  isAllSelected: Boolean = false;
  selectedCount: number | null = null;
  shortBy: number | string | null = null;
  zipcodeLoading: boolean = false;
  zipcodes: any[] = [];
  emailEdit: boolean = false;
  constructor(private http: HttpClient, private apiService: CrudService, private toastr: ToastrService, private fb: FormBuilder, private sweetAlert: SweetalertService) {


  }

  ngOnInit() {



    this.initializeForms();

    const isHoaMain = JSON.parse(localStorage.getItem('is_hoa_main_user'));
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
      this.isHoaMainUser = isHoaMain;
    }


    if (this.isHoaMainUser) {
      this.getHouseList(1, null, null);
    }

    this.txtSearch.valueChanges.pipe(
      debounceTime(500),
      distinctUntilChanged()
    ).subscribe(txt => {
      if (txt !== "" && txt.length > 0) {
        this.isSearch = true;
        this.getHouseList(1, null, txt);
      }


    })

  }







  getHouseList(page: number = 1, sortBy: number | string | null, search: string | null) {
    const houseListUrl: string = 'auth/hoa/house-list';

    const params = new URLSearchParams();
    this.page = page;
    params.set('page', String(page));

    if (sortBy !== null) {

      if (typeof sortBy === 'string') {
        params.set('sort', String(sortBy));
      } else if (typeof sortBy === 'number') {
        params.set('status', String(sortBy));
        this.status = sortBy;
      }

    }
    if (search !== null && search.length > 0) {

      params.set('q', search);

    }
    if (page == 1) {
      this.isMoreData = true;
    }
    if (this.isMoreData) {
      this.sweetAlert.loading();
      this.apiService.getRequest(`${houseListUrl}?${params.toString()}`).subscribe({
        next: (res: any) => {
          this.sweetAlert.close();



          this.activeHouseCount = res.active_house_count;
          this.deactiveHouseCount = res.deactive_house_count;
          this.deviceLimitPerHouse = res.device_limit_per_house;
          this.totalHouses = res.total_houses;
          this.totalDevice = res.total_devices;
          this.allotedDeviceCount = res.alloted_device_count;
          this.isMoreData = res.is_more_data;


          this.housesList = [...new Set([...this.housesList, ...res.data])];
          if (page === 1) {
            this.housesList = res.data;

          }

          // console.log('House List : ', this.housesList)
          this.updateHouseCount();
        },
        error: (err: any) => {
          this.sweetAlert.close();

          this.toastr.error(err.error.message);
        }
      })
    }

  }
  onSortChange(shortBy: number | string | null) {
    this.shortBy = shortBy;
    this.getHouseList(1, shortBy, null);
  }

  showHomeAddPopUp() {
    this.devicePerHouse = Array.from(
      { length: Number(this.deviceLimitPerHouse) },
      (_, i) => i + 1
    );
    this.isLoadingAddHomeForm = false;
    document.getElementById('btnshowPopAddHouse').click();
    document
      .getElementById('btnReset')
      .closest('form')
      .reset();

  }

  showAddPlanPopUp(houseId: number, event: any) {
    this.houseId = houseId;
    document.getElementById('btnshowPopAddPlan').click();
    document
      .getElementById('btnAddPlanReset')
      .closest('form')
      .reset();
  }

  showHomeEditPopUp(houseId: number, email: string | null, deviceLimit: number | null, palnInMonth: string | null, event: any) {
    this.houseId = houseId;
    this.emailEdit = email !== null ? true : false;
    document.getElementById('btnshowPopEditHouse').click();
    document
      .getElementById('btnEditReset')
      .closest('form')
      .reset();
    this.editHouseForm.patchValue({
      email: email,
      device_limit: deviceLimit,
      plan_in_month: [parseInt(palnInMonth).toString()]
    });
  }




  private initializeForms() {


    this.addHouseForm = new FormBuilder().group({

      email: ['', [Validators.email]],
      device_limit: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]+$/)
      ]],
      plan_in_month: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]+$/)
      ]]




    });

    this.editHouseForm = new FormBuilder().group({

      email: ['', [Validators.email]],
      device_limit: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]+$/)
      ]],
      plan_in_month: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]+$/)
      ]]
    });


    this.addPlanForm = new FormBuilder().group({
      plan_in_month: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]+$/)
      ]],
      device_limit: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]+$/)
      ]],
    });




  }
  handleEditHouseForm() {

    this.editHouseForm.markAllAsTouched();
    if (this.editHouseForm.valid) {
      // console.log("validation error : ", this.editHouseForm.value)
      this.isLoadingEditHomeForm = true;

      const data = {
        house_id: this.houseId,
        ...this.editHouseForm.value,
        plan_in_month: String(this.editHouseForm.value.plan_in_month)
      };
      this.apiService.postRequest("auth/hoa/edit-house", data).subscribe({
        next: (res: any) => {

          this.isLoadingEditHomeForm = false;

          if (res.success == true) {
            this.toastr.success(res.message);
            this.getHouseList(1, null, null);
          }
          document.getElementById('btnEditReset').click();
          document.getElementById('btnshowPopEditHouse').click();
        },
        error: (err: any) => {
          this.isLoadingEditHomeForm = false;
          document.getElementById('btnEditReset').click();
          document.getElementById('btnshowPopEditHouse').click();
          // console.log("Error : ", err)
          this.toastr.error(err.error?.message);
        },
      });

    }

  }
  handleAddPlanForm() {
    this.addPlanForm.markAllAsTouched();
    if (this.addPlanForm.valid) {
      this.isLoadingAddHomeForm = true;

      const data = {
        house_id: this.houseId,
        ...this.addPlanForm.value
      };
      this.apiService.postRequest("auth/hoa/add-plan", data).subscribe({
        next: (res: any) => {

          this.isLoadingAddHomeForm = false;

          if (res.success == true) {
            this.toastr.success(res.message);
            this.getHouseList(1, null, null);
          }
          document.getElementById('btnAddPlanReset').click();
          document.getElementById('btnshowPopAddPlan').click();
        },
        error: (err: any) => {
          this.isLoadingAddHomeForm = false;
          document.getElementById('btnAddPlanReset').click();
          document.getElementById('btnshowPopAddPlan').click();
          this.toastr.error(err.error?.message);
        },
      });

    }
  }

  clearTxtSearch() {

    this.getHouseList(1, null, null);
    this.txtSearch.reset();
    this.isSearch = false;

  }
  modelClose() {
    document.getElementById('btnshowPopAddHouse').click();

  }
  modelCloseAddPlan() {
    document.getElementById('btnshowPopAddPlan').click();
  }
  modelCloseHouseEdit() {
    document.getElementById('btnshowPopEditHouse').click();
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



          if (res.success == true) {
            this.toastr.success(res.message);
            this.getHouseList(1, null, null);
          }
          document.getElementById('btnReset').click();
          document.getElementById('btnshowPopAddHouse').click();
        },
        error: (err: any) => {
          document.getElementById('btnReset').click();
          document.getElementById('btnshowPopAddHouse').click();
          this.toastr.error(err.error?.message);
        },
      });
    }
  }

  removeHouseById(id: number) {
    this.housesList = this.housesList.filter(item => item.id !== id);
  }
  deleteHouse(houseId: number, event: any) {
    event.stopPropagation();
    this.sweetAlert.confirm("Delete House", "Do you want to delete this house?")
      .then(result => {
        if (result.isConfirmed) {
          this.houseId = houseId;
          this.sweetAlert.loading();
          this.apiService.deleteRequest(`auth/hoa/delete-house/${houseId}`).subscribe({
            next: (res: any) => {
              this.sweetAlert.close();

              if (res.success == true) {
                this.getHouseList(1, null, null);
                this.toastr.success(res.message);
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

  deactiveHouse(houseId: number, event: any) {
    event.stopPropagation();
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
                this.getHouseList(1, null, null);
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

  activeHouse(houseId: number, event: any) {
    event.stopPropagation();
    this.sweetAlert.confirm("Active House", "Do you want to active this house?")
      .then(result => {
        if (result.isConfirmed) {
          this.houseId = houseId;

          this.apiService.putRequest(`auth/hoa/status-change/${houseId}`).subscribe({
            next: (res: any) => {
              this.sweetAlert.close();
              if (res.success == true) {
                this.toastr.success(res.message);
                this.getHouseList(1, null, null);
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

  resendMail(houseId: number, event: any) {
    event.stopPropagation();
    this.sweetAlert.confirm("Send Mail", "Do you want to send mail?")
      .then(result => {
        if (result.isConfirmed) {
          this.houseId = houseId;
          this.sweetAlert.loading();
          this.apiService.postRequest(`auth/hoa/resend-verification-mail/${houseId}`).subscribe({
            next: (res: any) => {
              this.sweetAlert.close();
              if (res.success == true) {
                this.toastr.success(res.message);

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

  resendEditVerificationMail(houseId: number, event: any) {
    event.stopPropagation();
    this.sweetAlert.confirm("Send Mail", "Do you want to send mail?")
      .then(result => {
        if (result.isConfirmed) {
          this.houseId = houseId;
          this.sweetAlert.loading();
          this.apiService.postRequest(`auth/hoa/resend-edit-verify/${houseId}`).subscribe({
            next: (res: any) => {
              this.sweetAlert.close();
              if (res.success == true) {
                this.toastr.success(res.message);

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
    // console.log("Page count", this.page)
  }
  getSelectedHouseIds(): number[] {
    return this.housesList
      .filter(house => house.isSelected)
      .map(house => house.id);
  }
  updateHouseCount() {
    this.selectedCount = this.housesList
      .filter(house => house.isSelected)
      .map(house => house.id).length;
  }
  deleteAll() {
    const houseIds = this.getSelectedHouseIds();
    if (houseIds.length <= 0) {
      this.toastr.error("Please select any one");
      return;
    }
    this.sweetAlert.confirm("Delete All", "Do you want to delete all houses?")
      .then(result => {
        if (result.isConfirmed) {
          this.sweetAlert.loading();
          // console.log("Selected Houses id:", this.getSelectedHouseIds());
          this.apiService.postRequest(`auth/hoa/delete-all-houses`, {
            house_ids: houseIds
          }).subscribe({
            next: (res: any) => {

              this.sweetAlert.close();
              if (res.success == true) {
                this.toastr.success(res.message);
                this.getHouseList(1, null, null);

              }

            },
            error: (err: any) => {
              this.sweetAlert.close();
              this.toastr.error(err.error?.message);
            }
          })
        }
      })

  }

  exportIntoExcel() {

    const params = new URLSearchParams();

    const search: string = this.txtSearch.value;
    if (this.shortBy !== null) {

      if (typeof this.shortBy === 'string') {
        params.set('sort', String(this.shortBy));
      } else if (typeof this.shortBy === 'number') {
        params.set('status', String(this.shortBy));
      }

    }
    if (search !== null && search.length > 0) {

      params.set('q', search);

    }
    this.sweetAlert.confirm("Export", "Do you want to export data into excel?")
      .then(result => {
        if (result.isConfirmed) {
          this.sweetAlert.loading();

          this.apiService.exportIntoExcel(`auth/hoa/export-into-excel` + `?${params.toString()}`).subscribe({
            next: (data: Blob) => {
              this.sweetAlert.close();
              const blob = new Blob([data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
              });

              const url = window.URL.createObjectURL(blob);
              const a = document.createElement('a');
              a.href = url;
              a.download = 'hoa-house-list.xlsx';
              a.click();

              window.URL.revokeObjectURL(url);
            },
            error: (err: any) => {
              this.sweetAlert.close();
              this.toastr.error(err.error?.message);
            }
          });
        }
      });
  }





  // houseSelected(houseId: number, event: any) {
  //   this.housesList = this.housesList.map(house =>
  //     house.id === houseId
  //       ? { ...house, isSelected: !house.isSelected }
  //       : house
  //   );

  // }

  selectAll(event: Event) {
    this.isAllSelected = !this.isAllSelected;

    this.housesList = this.housesList.map(house => ({
      ...house,
      isSelected: this.isAllSelected
    }));
    this.updateHouseCount();
  }

  houseSelected(houseId: number, event: Event) {
    const checked = (event.target as HTMLInputElement).checked;
    if (checked) {
      this.housesList = this.housesList.map(house =>
        house.id === houseId
          ? { ...house, isSelected: true }
          : house
      );
    } else {
      this.housesList = this.housesList.map(house =>
        house.id === houseId
          ? { ...house, isSelected: false }
          : house
      );
    }
    this.updateHouseCount();
  }




  zipcodeOnSearch(term: any) {

    const query: string = term.term;
    if (!query || query.length < 2) {
      this.zipcodes = [];
      return;
    }
    this.zipcodeLoading = !this.zipcodeLoading;

    const data = {
      search: query
    }
    this.apiService.postRequestWithDetails("/api/auth/getzipcodes", data).subscribe({
      next: (res: any) => {
        this.zipcodeLoading = !this.zipcodeLoading;
        console.log("respons", res)
        this.zipcodes = [...res.body];

      },
      error: (err: any) => {
        console.log("respons", err)
        this.zipcodeLoading = !this.zipcodeLoading;
        this.toastr.error(err.error.message)
      }
    });
  }


  zipcodeOnSelected(event: any) {
    console.log("selected value:", event);
  }

  copyText(event: any, houseId: number) {
    event.stopPropagation();
    this.houseId = houseId;
    const text = event.target.innerText;
    navigator.clipboard.writeText(text)
      .then(() => {
        // this.sweetAlert.toast('Copied', 'success')
        this.isCopied = true;
        setTimeout(() => this.isCopied = false, 1500);
      })
      .catch(err => {
        this.sweetAlert.toast('Failed to copy', 'success')
      });
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

    return `${day} / ${month} / ${year}`;
  }

}
