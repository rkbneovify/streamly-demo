import { CommonModule, NgClass, NgIf } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';

@Component({
  selector: 'app-plans',
  standalone: true,
  imports: [NgIf,NgClass,CommonModule],
  templateUrl: './plans.component.html',
  styleUrl: './plans.component.scss'
})
export class PlansComponent implements OnInit {
selectTab:string ='otherPlans';
all_Plans:any;
constructor(private crud:CrudService){}

ngOnInit(): void {
  this.getAllThePlans();
}

// Get All The Plans
getAllThePlans(): void {
  this.crud.getRequest('stripe/plans').subscribe({
    next: (res) => {
      this.all_Plans = res;
      if (this.all_Plans.length > 0) {
        console.log('Plans data --> ', this.all_Plans);
      }
    },
    error: (err) => console.log('Plans error --> ', err),
  });
}
}
