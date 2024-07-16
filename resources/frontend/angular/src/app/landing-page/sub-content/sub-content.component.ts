import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgClass, NgFor, NgIf } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-sub-content',
  standalone: true,
  imports: [NgIf,NgFor,NgClass,RouterLink],
  templateUrl: './sub-content.component.html',
  styleUrl: './sub-content.component.scss'
})
export class SubContentComponent implements OnInit{

  categoryList:any = ['Movies','News','Kids','Comedy','Family','Others'];
  activeCategoryTab = 'Movies';

  constructor(private apiService:CrudService){}

  ngOnInit(): void {
    this.getAllCategories("Movies");
  }


  changeActiveTab(category:string){
    this.activeCategoryTab = category;
    this.getChannelsByGenre(category);
    console.log(this.activeCategoryTab);
  }

  categories: any;
  categoriesData:any;
  // get category list
  getAllCategories(genre:string){
    this.apiService.postRequest('auth/channel-list-before-login',{
      "typeurl" : "mpd"
  }).subscribe({
      next:(res:any)=>{
        console.log(res);
        this.categoriesData = res;
        if(this.categoriesData){
          this.getChannelsByGenre(genre);
        }
      },
      error:(err:any)=>{
        console.log(err);
      }
    })
  }


  getChannelsByGenre(genreName:any) {
    const data = this.categoriesData;
    if(this.categoriesData){
      for (let genre of data.data) {
        if (genre.genre_name === genreName) {
          this.categories = genre.channels.filter((data)=>data.image);
          console.log(genreName,"===>",this.categories);
        }
      }
    }
    }
}
