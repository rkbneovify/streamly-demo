import { CommonModule, NgClass, NgFor, NgIf } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';

@Component({
  selector: 'app-plans',
  standalone: true,
  imports: [NgIf,NgClass,CommonModule,NgFor],
  templateUrl: './plans.component.html',
  styleUrl: './plans.component.scss'
})
export class PlansComponent implements OnInit {
selectTab:string ='basicPlans';
all_Plans:any;
basic_plans:any;
Tiered_plans:any; 
constructor(private crud:CrudService){}

ngOnInit(): void {
  this.getAllThePlans();
  if(this.all_Plans){
    this.all_Plans.forEach((plan:any,index:number) => { 
      console.log('inside for loop ',plan);
      if(plan?.type == 'per_unit'){
        console.log(plan,'=====pla in per');
        this.basic_plans[index]=plan;
      }else{
        if(plan?.type == 'tiered'){
          this.basic_plans=plan;
        }
      }
    });
  }
  // console.log(this.basic_plans,'=======pal',);
}

// Get All The Plans
getAllThePlans(): void {
  this.crud.getRequest('stripe/plans').subscribe({
    next: (res) => {
      this.all_Plans = res;
      console.log(this.all_Plans,"======all plans",res);
    },
    error: (err) => console.log('Plans error --> ', err),
  });
}
}
