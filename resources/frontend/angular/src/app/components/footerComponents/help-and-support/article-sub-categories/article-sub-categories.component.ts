import { Location, NgClass, NgFor } from '@angular/common';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
import { distinctUntilChanged, exhaustMap, Subject, takeUntil } from 'rxjs';
import { CrudService } from '../../../../services/crud.service';
import { SweetalertService } from '../../../../services/SweetAlert/sweetalert.service';
import { CacheService } from '../../../../services/cacheService/cacheservice.service';
@Component({
  selector: 'app-article-sub-categories',
  standalone: true,
  imports: [NgFor, NgClass, InfiniteScrollDirective],
  templateUrl: './article-sub-categories.component.html',
  styleUrl: './article-sub-categories.component.scss'
})
export class ArticleSubCategoriesComponent implements OnInit, OnDestroy {

  redirectData: any = {};
  selectedIndex: number = 0;
  subcategories: any[] = [];
  blogs: any[] = [];
  page: number = 1;
  limit: number = 9;
  hasMore: boolean = true;
  private pageSubject = new Subject<number>();
  private destroy$ = new Subject<void>();

  subscriptionArticles: string[] = [
    "I’ve changed my phone number. How can I access my subscription?",
    "How Do I Cancel My Subscription?",
    "How do I manage my subscriptions via iTunes?",
    "Cancel Subscription (Google Play Users)",
    "How to delete my account",
    "Cancel Subscription (iOS Users)"
  ];

  articles: string[] = [
    "I’ve changed my phone number. How can I access my subscription?",
    "How Do I Cancel My Subscription?",
    "How do I manage my subscriptions via iTunes?",
    "Cancel Subscription (Google Play Users)",
    "How to delete my account",
    "Cancel Subscription (iOS Users)"
  ];
  constructor(
    private router: Router,
    private location: Location,
    private api: CrudService,
    private alert: SweetalertService,
    private myCache: CacheService,
    private sanitizer: DomSanitizer
  ) { }


  ngOnInit(): void {
    this.redirectData = history.state;
    this.loadSubCategories(this.redirectData);
    this.pageSubject.next(this.page);


  }

  onScrollDown() {
    if (!this.hasMore) {
      return;
    }

    this.pageSubject.next(this.page);
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }

  goBack(): void {
    this.location.back();
  }

  getSvg(svg: string): SafeHtml {
    return this.sanitizer.bypassSecurityTrustHtml(svg);
  }

  onSubCategorySelect(item: any, index: number): void {
    this.selectedIndex = index;
    this.blogs = item.blog;
    console.log('Selected item:', item);
  }

  loadSubCategories(stateData: any) {
    this.pageSubject.pipe(
      distinctUntilChanged(),
      exhaustMap(page => {
        const params = new URLSearchParams();
        params.set('limit', String(this.limit));
        params.set('page', String(page));
        this.alert.loading();
        return this.api
          .getRequest(`blogs/${stateData.title}/${stateData.sub_category}/list?${params.toString()}`);

      }),

      takeUntil(this.destroy$)
    )
      .subscribe({
        next: (res: any) => {
          this.alert.close();
          this.subcategories = this.myCache.removeDuplicates([
            ...this.subcategories,
            ...res.data
          ]);

          this.blogs = this.subcategories[0].blog;
          this.hasMore = res.has_more;


          if (this.hasMore) {
            this.page += 1;
          }
        },

        error: (err: any) => {
          this.alert.close();
          this.alert.error(err.error.message)
        }
      });



  }

  onArticleSelect(item: any) {
    const data = {
      blogId: item.id,
      category: item.category,
      subCategory: item.sub_category
    };
    console.log(item);
    console.log(data);
    this.router.navigate(['/user/article', data.category, data.subCategory, item.slug], {
      state: data
    });
  }



}
