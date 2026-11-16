import { CommonModule, NgFor, NgIf } from '@angular/common';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { RouterLink, Router } from '@angular/router';
import { CrudService } from '../../../services/crud.service';
import {
  debounceTime,
  distinctUntilChanged,
  exhaustMap,
  finalize,
  Subject,
  switchMap,
  takeUntil
} from 'rxjs';
import { SweetalertService } from '../../../services/SweetAlert/sweetalert.service';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
import { FormControl, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { CacheService } from '../../../services/cacheService/cacheservice.service';

interface Category {
  title: string;
  description: string;
  icon: string;
}

interface Actions {
  title: string;
  icon: string;
  url: string;
}

@Component({
  selector: 'app-help-and-support',
  standalone: true,
  imports: [
    RouterLink,
    NgFor,
    NgIf,
    InfiniteScrollDirective,
    CommonModule,
    FormsModule,
    ReactiveFormsModule
  ],
  templateUrl: './help-and-support.component.html',
  styleUrl: './help-and-support.component.scss'
})
export class HelpAndSupportComponent implements OnInit, OnDestroy {
  constructor(
    private router: Router,
    private api: CrudService,
    private alert: SweetalertService,
    private myCache: CacheService
  ) { }

  categories: any[] = [];
  limit: number = 12;
  page: number = 1;
  hasMore: boolean = true;
  txtSearch = new FormControl('');
  hasMoreKey: string = 'blog_categories_has_More';
  dataCacheKey: string = '';
  private pageSubject = new Subject<number>();
  private destroy$ = new Subject<void>();

  // categories: Category[] = [
  //   {
  //     title: 'Reports & Dashboards',
  //     description: 'Get insights and learn how to use reports.',
  //     icon: 'assets/icons/article.svg'
  //   },
  //   {
  //     title: 'How to get help',
  //     description: 'Contact our support system & find answers',
  //     icon: 'assets/icons/article.svg'
  //   },
  //   {
  //     title: 'Notification',
  //     description: 'Manage alerts & notification settings',
  //     icon: 'assets/icons/article.svg'
  //   },
  //   {
  //     title: 'Video Data',
  //     description: 'Learn about video data & usage.',
  //     icon: 'assets/icons/article.svg'
  //   },
  //   {
  //     title: 'Sports Data',
  //     description: 'Explore sports data options & formats.',
  //     icon: 'assets/icons/article.svg'
  //   },
  //   {
  //     title: 'Audio Data',
  //     description: 'Understand audio data & integrations.',
  //     icon: 'assets/icons/article.svg'
  //   },
  //   {
  //     title: 'Automotive Data',
  //     description: 'Discover automotive data solutions.',
  //     icon: 'assets/icons/article.svg'
  //   },
  //   {
  //     title: 'Studio System',
  //     description: 'Learn about studio system & setup',
  //     icon: 'assets/icons/article.svg'
  //   }
  // ];

  actions: Actions[] = [
    {
      title: 'Support Ticket',
      icon: 'assets/icons/support-ticket.svg',
      url: '/user/support-ticket'
    },
    {
      title: 'Report A BUG',
      icon: 'assets/icons/bug.svg',
      url: '/user/report'
    },
    {
      title: 'FAQs',
      icon: 'assets/icons/faq.svg',
      url: '/user/faq'
    },
    {
      title: 'Privacy Policy',
      icon: 'assets/icons/policy.svg',
      url: '/user/privacy-policy'
    }
  ];

  getCategories(search: string = '') {
    this.dataCacheKey =
      'blog_categories_' +
      JSON.stringify({
        page: this.page,
        limit: this.limit,
        search: this.txtSearch.value
      });
    const cachedData = this.myCache.get(this.dataCacheKey);
    if (cachedData) {
      this.hasMore = this.myCache.get(this.hasMoreKey);
      this.categories = [...cachedData];
      return;
    }

    this.pageSubject
      .pipe(
        distinctUntilChanged(),
        exhaustMap(page => {
          const params = new URLSearchParams();
          params.set('limit', String(this.limit));
          params.set('page', String(page));
          params.set('search', this.txtSearch.value);

          this.alert.loading();

          return this.api
            .getRequest(`blogs/categories/list?${params.toString()}`)
            .pipe(
              finalize(() => {
                this.alert.close();
              })
            );
        }),

        takeUntil(this.destroy$)
      )
      .subscribe({
        next: (res: any) => {
          this.categories = this.myCache.removeDuplicates([
            ...this.categories,
            ...res.data
          ]);

          this.hasMore = res.has_more;
          this.myCache.set(this.hasMoreKey, this.hasMore);
          this.myCache.set(this.dataCacheKey, this.categories);
          if (this.hasMore) {
            this.page += 1;
          }
        },

        error: (err: any) => {
          console.log('Error:', err);
        }
      });
  }

  ngOnInit(): void {
    this.getCategories();
    this.pageSubject.next(this.page);
    this.txtSearch.valueChanges
      .pipe(debounceTime(500), distinctUntilChanged())
      .subscribe(txt => {
        if (txt !== '' && txt.length > 2) {
          this.myCache.remove(this.dataCacheKey);
          this.categories = [];
          this.page = 1;
          this.hasMore = true;

          this.pageSubject.next(this.page);
        }
      });
  }
  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }

  // generateTicket(): void {
  //   this.router.navigate(['/user/support-ticket']);
  // }

  onScrollDown() {
    if (!this.hasMore) {
      return;
    }

    this.pageSubject.next(this.page);
  }

  resetSearch() {
    this.txtSearch.setValue('');
    this.page = 1;
    this.hasMore = true;
    this.myCache.remove(this.dataCacheKey);
    this.categories = [];
    this.getCategories();
    this.pageSubject.next(this.page);
  }

  openCategory(category: Category): void {
    console.log('Category:', category);
  }

  openAction(action: Actions): void {
    this.router.navigate([action.url]);
    console.log(action.url);
  }
}
