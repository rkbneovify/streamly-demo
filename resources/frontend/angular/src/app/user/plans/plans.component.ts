import { CommonModule, NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-plans',
  standalone: true,
  imports: [NgIf,NgClass,CommonModule],
  templateUrl: './plans.component.html',
  styleUrl: './plans.component.scss'
})
export class PlansComponent {
selectTab:string ='otherPlans';

constructor(){}
}
