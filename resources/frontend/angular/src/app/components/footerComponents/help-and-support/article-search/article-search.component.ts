import { CommonModule, NgFor, NgIf } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CrudService } from '../../../../services/crud.service';
import { Router } from '@angular/router';
import { SweetalertService } from '../../../../services/SweetAlert/sweetalert.service';

@Component({
  selector: 'app-article-search',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule, NgIf, NgFor],
  templateUrl: './article-search.component.html',
  styleUrl: './article-search.component.scss'
})
export class ArticleSearchComponent implements OnInit {

  constructor(
    private router: Router,
    private api: CrudService,
    private alert: SweetalertService
  ) {

  }
  searchText: string = '';
  blogs: any[] = [];
  isLoading: boolean = false;
  ngOnInit(): void {

  }
  searchItems(): void {
    const search = this.searchText.toLowerCase().trim();

    if (search.length > 3) {
      this.searchBlog(search);
    }


  }


  clearSearch(): void {
    this.searchText = '';
  }

  searchBlog(search: string) {
    this.isLoading = true;
    this.api.getRequest(`blogs?search=${search}`)
      .subscribe({
        next: (res: any) => {
          this.isLoading = false;
          this.blogs = res.data;

        },
        error: (err) => {
          this.isLoading = false;
          // console.log("err : ", err.error);
          this.alert.error(err.error.message, 'error');
        }
      })
  }

  onBlogSelect(item: any): void {

    this.clearSearch();
    this.router.navigate(['/user/article', item.category, item.sub_category, item.id, item.slug], {

    });
  }

}
