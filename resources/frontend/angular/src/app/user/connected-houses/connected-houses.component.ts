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
import { LocalDatePipe } from '../../pipe/localDate/local-date.pipe';

@Component({
  selector: 'app-connected-houses',
  standalone: true,
  imports: [NgFor, NgIf, NgClass, TimeAgoPipe, LocalDatePipe, SortByDropdownComponent, ɵInternalFormsSharedModule, ReactiveFormsModule, InfiniteScrollDirective],
  templateUrl: './connected-houses.component.html',
  styleUrl: './connected-houses.component.scss'
})
export class ConnectedHousesComponent {

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
  isCopied: Boolean = false;
  constructor(private apiService: CrudService, private toastr: ToastrService, private fb: FormBuilder, private sweetAlert: SweetalertService) {


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
          this.isMoreData = res.is_more_data;


          this.housesList = [...new Set([...this.housesList, ...res.data])];
          if (page === 1) {
            this.housesList = res.data;

          }

          console.log('House List : ', this.housesList)

        },
        error: (err: any) => {
          this.sweetAlert.close();

          this.toastr.error(err.error.message);
        }
      })
    }

  }
  onSortChange(shortBy: number | string | null) {
    this.getHouseList(1, shortBy, null);
  }

  showHomeAddPopUp() {
    this.isLoadingAddHomeForm = false;
    document.getElementById('btnshowPopAddHouse').click();


  }



  private initializeForms() {


    this.addHouseForm = this.fb.group({

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




  }

  clearTxtSearch() {

    this.getHouseList(1, null, null);
    this.txtSearch.reset();
    this.isSearch = false;

  }
  modelClose() {
    document.getElementById('btnReset').click();

    setTimeout(() => {
      document.getElementById('btnshowPopAddHouse').click();
    }, 500); // 500 ms delay
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

  resendMail(houseId: number) {
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

  onScrollDown() {
    this.page++;
    this.getHouseList(this.page, this.status, this.txtSearch.value)
    // console.log("Page count", this.page)
  }

  copyText(event: any, houseId: number) {
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

    return `${day}/${month}/${year}`;
  }

}
