import { Component, ElementRef, HostListener, OnDestroy, OnInit } from '@angular/core';
import { CommonModule, NgForOf, NgIf } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CrudService } from '../../../services/crud.service';
import { SweetalertService } from '../../../services/SweetAlert/sweetalert.service';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
import { distinctUntilChanged, exhaustMap, Subject, takeUntil } from 'rxjs';

interface SupportTicket {
  id: number;
  ticketNumber: string;
  platform: string;
  subject: string;
  priority: string;
  status: string;
  createdAt: string;
}


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
  tickets: SupportTicket[] = [

  ];

  constructor(
    private api: CrudService,
    private alert: SweetalertService,
    private elementRef: ElementRef
  ) { }


  ngOnInit(): void {
    this.getSupportTicketList();
    this.pageSubject.next(this.page);
  }
  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
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


          this.hasMore = res.has_more;
          this.supportTicketList = [...this.supportTicketList, ...res.data];


          if (this.hasMore) {
            this.page += 1;
          }
        },

        error: (err: any) => {
          this.alert.close();
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
    this.reloadSupportTickets();
  }


  get filteredTickets(): SupportTicket[] {
    const search = this.searchText.trim().toLowerCase();

    let result = this.tickets.filter(ticket =>
      [
        ticket.ticketNumber,
        ticket.platform,
        ticket.subject,
        ticket.priority,
        ticket.status,
        ticket.createdAt
      ].some(value => value.toLowerCase().includes(search))
    );

    if (this.sortColumn) {
      const column = this.sortColumn as keyof SupportTicket;
      const direction = this.sortDirection === 'asc' ? 1 : -1;

      result = [...result].sort((a, b) => {
        const valueA = a[column];
        const valueB = b[column];

        if (typeof valueA === 'number' && typeof valueB === 'number') {
          return (valueA - valueB) * direction;
        }

        return String(valueA).localeCompare(String(valueB)) * direction;
      });
    }

    return result;
  }

  get allSelected(): boolean {
    return this.filteredTickets.length > 0 &&
      this.filteredTickets.every(ticket =>
        this.selectedTickets.includes(ticket.id)
      );
  }

  toggleAll(checked: boolean): void {
    const visibleIds = this.filteredTickets.map(ticket => ticket.id);

    if (checked) {
      this.selectedTickets = [
        ...new Set([...this.selectedTickets, ...visibleIds])
      ];
    } else {
      this.selectedTickets = this.selectedTickets.filter(
        id => !visibleIds.includes(id)
      );
    }
  }

  toggleTicket(id: number, checked: boolean): void {
    if (checked) {
      if (!this.selectedTickets.includes(id)) {
        this.selectedTickets = [...this.selectedTickets, id];
      }
    } else {
      this.selectedTickets = this.selectedTickets.filter(
        ticketId => ticketId !== id
      );
    }
  }

  sortBy(column: string): void {
    if (this.sortColumn === column) {
      this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
      this.sortColumn = column;
      this.sortDirection = 'asc';
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

  trackById(_: number, ticket: SupportTicket): number {
    return ticket.id;
  }
}
