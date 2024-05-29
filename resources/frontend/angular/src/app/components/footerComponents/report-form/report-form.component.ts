import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../../services/crud.service';
import { Location, NgFor, NgIf } from '@angular/common';

@Component({
  selector: 'app-report-form',
  standalone: true,
  imports: [NgFor,NgIf],
  templateUrl: './report-form.component.html',
  styleUrl: './report-form.component.scss'
})
export class ReportFormComponent implements OnInit{
  privacyPolicyData:any;
  title:string;
  constructor(private location:Location,private apiService:CrudService){}
  goBack(): void {
    this.location.back();
  }

  ngOnInit(): void {
    this.getPrivacyP();
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