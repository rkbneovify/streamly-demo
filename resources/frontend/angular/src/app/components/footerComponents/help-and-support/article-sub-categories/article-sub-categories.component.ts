import { Location, NgClass, NgFor } from '@angular/common';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
import { debounceTime, distinctUntilChanged, exhaustMap, Subject, takeUntil } from 'rxjs';
import { CrudService } from '../../../../services/crud.service';
import { SweetalertService } from '../../../../services/SweetAlert/sweetalert.service';
import { CacheService } from '../../../../services/cacheService/cacheservice.service';
import { ArticleSearchComponent } from '../article-search/article-search.component';
import { FormControl, FormsModule } from '@angular/forms';
@Component({
  selector: 'app-article-sub-categories',
  standalone: true,
  imports: [RouterLink, NgFor, FormsModule, NgClass, ArticleSearchComponent, InfiniteScrollDirective],
  templateUrl: './article-sub-categories.component.html',
  styleUrl: './article-sub-categories.component.scss'
})
export class ArticleSubCategoriesComponent implements OnInit, OnDestroy {

  redirectData: any = {};
  selectedIndex: number = 0;
  selectedCategory: string | null = null;
  categoryDetails: any = {};
  subcategory: string | null = null;
  subcategories: any[] = [];
  blogs: any[] = [];
  page: number = 1;
  limit: number = 9;
  hasMore: boolean = true;
  private pageSubject = new Subject<number>();
  private destroy$ = new Subject<void>();
  txtSearch = new FormControl('');


  constructor(
    private router: Router,
    private location: Location,
    private activatedRoute: ActivatedRoute,
    private api: CrudService,
    private alert: SweetalertService,
    private myCache: CacheService,
    private sanitizer: DomSanitizer
  ) { }


  ngOnInit(): void {
    this.redirectData = history.state;




    this.activatedRoute.paramMap.subscribe(params => {
      this.selectedCategory = params.get('category');
      this.subcategory = params.get('subcategory');
      this.loadCategory(this.selectedCategory);

      if (this.selectedCategory != null) {
        const data = {
          title: this.selectedCategory,
          sub_category: this.subcategory
        }
        this.loadSubCategories(data);
        this.pageSubject.next(this.page);


      } else {
        this.loadSubCategories(this.redirectData);
        this.pageSubject.next(this.page);
      }


    });




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

  loadCategory(category: string) {
    this.alert.loading();
    this.api.getRequest(`blogs/categories/${category}`).subscribe({
      next: (res: any) => {
        this.alert.close();
        this.categoryDetails = res.data;
        console.log('category : ', res.data);
      },
      error: (err: any) => {
        this.alert.close();
        this.alert.error(err.error.message)

      }
    })
  }

  loadSubCategories(stateData: any) {
    this.pageSubject.pipe(
      distinctUntilChanged(),
      exhaustMap(page => {
        const params = new URLSearchParams();
        params.set('limit', String(this.limit));
        params.set('page', String(page));
        params.set('search', this.txtSearch.value);
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
    // const data = {
    //   blogId: item.id,
    //   category: item.category,
    //   subCategory: item.sub_category
    // };
    // console.log(item);
    // console.log(data);
    this.router.navigate(['/user/article', item.category, item.sub_category, item.id, item.slug], {
      // state: data
    });
  }



}
