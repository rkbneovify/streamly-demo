import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { DatePipe, NgClass, NgFor, NgIf, TitleCasePipe } from '@angular/common';
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
import { NotificationService } from '../../services/utils/notification.service';

@Component({
  selector: 'app-notifications',
  standalone: true,
  imports: [NgFor, NgIf, NgSelectModule, FormsModule, TimeAgoPipe, TitleCasePipe, SortByDropdownComponent, ɵInternalFormsSharedModule, ReactiveFormsModule, InfiniteScrollDirective],
  templateUrl: './notifications.component.html',
  styleUrl: './notifications.component.scss'
})
export class NotificationsComponent implements OnInit {


  data: Array<any> = [];
  page: number = 1;
  isMoreData: Boolean = true;
  isLoading: boolean = false;

  constructor(private http: HttpClient,
    private apiService: CrudService,
    private toastr: ToastrService,
    private fb: FormBuilder,
    private sweetAlert: SweetalertService,
    private notificationService: NotificationService
  ) {


  }

  ngOnInit() {

    this.getList(1);
    this.notificationService.fetchCount();


  }







  getList(page: number = 1) {

    const params = new URLSearchParams();
    this.page = page;
    params.set('page', String(page));


    if (page == 1) {
      this.isMoreData = true;
    }
    if (this.isMoreData) {
      this.sweetAlert.loading();
      this.isLoading = true;
      this.apiService.getRequest(`auth/notification?${params.toString()}`).subscribe({
        next: (res: any) => {
          this.isLoading = false;
          this.sweetAlert.close();




          this.isMoreData = res.is_more_data;


          this.data = [...new Set([...this.data, ...res.data])];
          if (page === 1) {
            this.data = res.data;

          }


        },
        error: (err: any) => {
          this.isLoading = false;
          this.sweetAlert.close();

          this.toastr.error(err.error.message);
        }
      })
    }

  }









  onScrollDown() {
    this.page++;
    this.getList(this.page)
    // console.log("Page count", this.page)
  }


}
