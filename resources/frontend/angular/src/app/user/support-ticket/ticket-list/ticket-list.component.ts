import { Component, ElementRef, HostListener, OnDestroy, OnInit } from '@angular/core';
import { CommonModule, NgForOf, NgIf } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CrudService } from '../../../services/crud.service';
import { SweetalertService } from '../../../services/SweetAlert/sweetalert.service';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
import { distinctUntilChanged, exhaustMap, Subject, takeUntil } from 'rxjs';
import { Router } from '@angular/router';




@Component({
  selector: 'app-ticket-list',
  standalone: true,
  imports: [NgIf, NgForOf, CommonModule,
    FormsModule, InfiniteScrollDirective],
  templateUrl: './ticket-list.component.html',
  styleUrl: './ticket-list.component.scss'
})
export class TicketListComponent implements OnInit, OnDestroy {

  searchText = '';
  sortColumn = '';
  sortDirection: 'asc' | 'desc' = 'asc';
  selectedTickets: number[] = [];
  isLoading: boolean = false;
  priorities = ['All', 'Low', 'Medium', 'High', 'Critical'];
  showPriorityDropdown = false;
  selectedPriority: string | null = null;
  createdAtSortOrder: 'asc' | 'desc' | null = null;

  showStatusDropdown = false;
  statuses: string[] = [
    'All',
    'Under Review',
    'In Progress',
    'On Hold',
    'Completed',
    'Cancelled',
    'Closed'
  ];

  selectedStatus: string | null = null;
  supportTicketList: any[] = [];
  page: number = 1;
  limit: number = 10;
  hasMore: boolean = false;
  private pageSubject = new Subject<number>();
  private destroy$ = new Subject<void>();


  constructor(
    private api: CrudService,
    private alert: SweetalertService,
    private router: Router
  ) { }


  ngOnInit(): void {
    this.getSupportTicketList();
    this.pageSubject.next(this.page);
  }
  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }


  onSearchClear() {
    if (!this.searchText) {
      this.searchText = '';
      this.reloadSupportTickets();
    }
  }

  getSupportTicketList() {


    this.pageSubject.pipe(
      distinctUntilChanged(),
      exhaustMap(page => {
        const params = new URLSearchParams();
        params.set('limit', String(this.limit));
        params.set('page', String(page));
        if (this.selectedPriority) {
          if (this.selectedPriority == 'All') {
            params.delete('status')
          } else {
            params.set('priority', this.selectedPriority);
          }

        }
        if (this.selectedStatus) {
          if (this.selectedStatus == 'All') {
            params.delete('status')
          } else {
            params.set('status', this.selectedStatus);
          }

        }
        if (this.searchText != '') {
          params.set('txt', this.searchText);
        } else {
          params.delete('txt');
        }
        if (this.createdAtSortOrder != null) {
          params.set('date', this.createdAtSortOrder);
        } else {
          params.delete('date');
        }
        this.alert.loading();
        this.isLoading = this.alert.loadingState;
        return this.api
          .getRequest(`support-ticket?${params.toString()}`);

      }),

      takeUntil(this.destroy$)
    )
      .subscribe({
        next: (res: any) => {
          this.alert.close();
          this.isLoading = this.alert.loadingState;


          this.hasMore = res.has_more;
          this.supportTicketList = [...this.supportTicketList, ...res.data];

          if (this.hasMore) {
            this.page += 1;
          }
        },

        error: (err: any) => {
          this.alert.close();
          this.isLoading = this.alert.loadingState;

          this.alert.error(err.error.message);
        }
      });



  }

  onScrollDown() {
    if (!this.hasMore) {
      return;
    }

    this.pageSubject.next(this.page);

    // console.log("Page count", this.page)
  }

  reloadSupportTickets() {
    this.supportTicketList = [];
    this.destroy$.next();
    this.destroy$.complete();
    this.page = 1;
    this.getSupportTicketList();
    this.pageSubject.next(this.page);
  }



  togglePriorityDropdown(event: MouseEvent): void {
    event.stopPropagation();
    this.showStatusDropdown = false;
    this.showPriorityDropdown = !this.showPriorityDropdown;
  }

  selectPriority(priority: string): void {
    this.selectedPriority = priority;
    console.log('Selected priority:', priority);
    this.showPriorityDropdown = false;
    this.reloadSupportTickets();

  }

  closeDropdown() {
    this.showPriorityDropdown = false;
    this.showStatusDropdown = false;
  }

  toggleStatusDropdown(event: MouseEvent): void {
    event.stopPropagation();
    this.showPriorityDropdown = false;
    this.showStatusDropdown = !this.showStatusDropdown;
  }

  selectStatus(status: string): void {
    this.selectedStatus = status;
    this.showStatusDropdown = false;
    this.reloadSupportTickets();
    console.log('Selected status:', status);
  }

  statusClassName(status: string): string {
    const classMap: Record<string, string> = {
      'All': 'all',
      'Under Review': 'under-review',
      'In Progress': 'inprogress',
      'On Hold': 'on-hold',
      'Completed': 'completed',
      'Cancelled': 'cancelled',
      'Closed': 'closed'
    };

    return classMap[status] ?? '';
  }


  onSearch() {
    if (this.searchText.length > 0) {
      this.reloadSupportTickets();
    } else {
      this.alert.toast('Please enter ticket no. or platform', 'error');
    }

  }






  sortByCreatedAt(): void {
    if (this.createdAtSortOrder == null) {
      this.createdAtSortOrder = 'asc';
      this.reloadSupportTickets();
    } else {
      this.createdAtSortOrder = this.createdAtSortOrder === 'asc' ? 'desc' : 'asc';
      this.reloadSupportTickets();
    }

  }

  priorityClass(priority: string): string {
    return priority.toLowerCase();
  }

  statusClass(status: string): string {
    return status.toLowerCase().replace(/\s+/g, '-');
  }

  onClickTicketNo(ticketNo: string) {
    this.router.navigateByUrl(`/user/support-ticket/chat/${ticketNo}`);
  }


}
