import { Location, NgClass, NgFor } from '@angular/common';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
import { distinctUntilChanged, exhaustMap, Subject, takeUntil } from 'rxjs';
import { CrudService } from '../../../../services/crud.service';
import { SweetalertService } from '../../../../services/SweetAlert/sweetalert.service';
import { CacheService } from '../../../../services/cacheService/cacheservice.service';
import { ArticleSearchComponent } from '../article-search/article-search.component';

@Component({
  selector: 'app-article',
  standalone: true,
  imports: [RouterLink, NgFor, NgClass, ArticleSearchComponent, InfiniteScrollDirective],
  templateUrl: './article.component.html',
  styleUrl: './article.component.scss'
})
export class ArticleComponent implements OnInit, OnDestroy {
  redirectData: any = {};
  selectedIndex: number = 0;
  blog: any = {};
  relatedBlog: any[] = [];
  category: string;
  subcategory: string;
  slug: string;
  blogId!: number;
  blogContent!: string;





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

    this.activatedRoute.paramMap.subscribe(params => {

      this.category = params.get('category')
      this.subcategory = params.get('subcategory');
      this.slug = params.get('slug');
      this.blogId = Number(params.get('id'));
      this.getBlog(this.blogId);
    });






  }



  ngOnDestroy(): void {
    this.blog = {};
    this.relatedBlog = [];
  }

  goBack(): void {
    this.location.back();
  }

  getSvg(svg: string): SafeHtml {
    return this.sanitizer.bypassSecurityTrustHtml(svg);
  }

  onRelatedBlogSelect(item: any): void {
    // this.blogId = item.id;
    // const data = {
    //   blogId: item.id,
    //   category: item.category,
    //   subCategory: item.sub_category
    // };

    this.router.navigate(['/user/article', item.category, item.sub_category, item.id, item.slug], {

    });

  }

  getBlog(blogId: number) {

    this.alert.loading();
    this.api
      .getRequest(`blogs/${blogId}`)
      .subscribe({
        next: (res: any) => {
          this.alert.close();
          this.blog = res.data.blog;
          this.relatedBlog = res.data.related;
          this.formateBlogContent(res.data.blog.content);
        },

        error: (err: any) => {
          this.alert.close();
          this.alert.error(err.error.message)
        }
      });



  }

  formateBlogContent(content: string) {
    this.blogContent = content.replace(/<span class="ql-cursor">[\s\S]*?<\/span>/g, '');
  }



}
