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
}

// Get All The Plans
getAllThePlans(): void {
  this.crud.getRequest('stripe/plans').subscribe({
    next: (res) => {
      this.all_Plans = res[0]; // Access the inner array
      console.log(this.all_Plans, "======all plans", res);

      // Initialize basic_plans as an empty array
      this.basic_plans = [];

      // Iterate over the plans and categorize them
      this.all_Plans.forEach((plan: any) => {
        console.log('inside for loop ', plan);
        if (plan.type === 'per_unit') {
          console.log(plan, '=====plan in per');
          this.basic_plans.push(plan); // Push plan to basic_plans array
        } else if (plan.type === 'tiered') {
          this.Tiered_plans = plan; // Assign plan to Tiered_plans (assuming there's only one tiered plan)
        }
      });
    },
    error: (err) => console.log('Plans error --> ', err),
  });
}

}
