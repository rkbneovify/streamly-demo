import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../../services/crud.service';
import { Location, NgFor, NgIf ,NgClass} from '@angular/common';

@Component({
  selector: 'app-term-condition',
  standalone: true,
  imports: [NgFor,NgIf,NgClass],
  templateUrl: './term-condition.component.html',
  styleUrl: './term-condition.component.scss'
})
export class TermConditionComponent implements OnInit{
  termsOfUseData:any;
  title:string;
  showbackbtn:boolean = true;
  constructor(private location:Location,private apiService:CrudService){}
  goBack(): void {
    this.location.back();
  }

  ngOnInit(): void {
    this.getTerms();

    if(localStorage.getItem('token')){
      this.showbackbtn = false;
    }else{
      this.showbackbtn = true
    }
  }


  getTerms(){
    this.apiService.getRequest('auth/termofuse').subscribe({
      next:(res:any)=>{
        const data = res?.data.split('\n');
        this.termsOfUseData = data.slice(2);
        this.title = data[0];
        console.log(res,'===>',this.termsOfUseData);
      },
      error:(err:any)=>{
        console.log(err);
      }
    })
  }
}