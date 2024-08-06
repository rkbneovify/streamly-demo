import { Location, NgClass, NgFor, NgIf } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../../services/crud.service';
@Component({
  selector: 'app-privacy-policy',
  standalone: true,
  imports: [NgFor,NgIf,NgClass],
  templateUrl: './privacy-policy.component.html',
  styleUrl: './privacy-policy.component.scss'
})
export class PrivacyPolicyComponent implements OnInit{
  privacyPolicyData:any;
  title:string;
  showbackbtn:boolean = true;

  constructor(private location:Location,private apiService:CrudService){}
  goBack(): void {
    this.location.back();
  }

  ngOnInit(): void {
    this.getPrivacyP();
    if(localStorage.getItem('token')){
      this.showbackbtn = false;
    }else{
      this.showbackbtn = true
    }
  }


  getPrivacyP(){
    this.apiService.getRequest('auth/privacypol').subscribe({
      next:(res:any)=>{
        const data = res?.data.split('\n');
        this.privacyPolicyData = data.slice(1);
        this.title = data[0];
        console.log(res);
      },
      error:(err:any)=>{
        console.log(err);
      }
    })
  }
}
