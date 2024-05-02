import { Component } from '@angular/core';
import { Router, RouterLink } from '@angular/router';

@Component({
  selector: 'app-subscription-expired',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './subscription-expired.component.html',
  styleUrl: './subscription-expired.component.scss'
})
export class SubscriptionExpiredComponent {

  constructor(private route:Router){}


}
