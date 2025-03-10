import { Component, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';
import { DataSharingService } from '../../services/data-sharing.service';
import { AuthService } from '../../services/authService/authservice.service';

@Component({
  selector: 'app-subscription-expired',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './subscription-expired.component.html',
  styleUrl: './subscription-expired.component.scss'
})
export class SubscriptionExpiredComponent implements OnInit {

  access_Token: any;


  constructor(private route:Router,private crud: CrudService, private currentData:DataSharingService,private setLocalData:AuthService,){
  }
  
  ngOnInit(): void {
    this.loginSliders();
  }

  loginSliders() {
    this.crud.getRequest('auth/channel-sliders')
    .subscribe({
      next:()=>{
        let info = JSON.parse(localStorage.getItem('deviceInfo'));
        // console.log(info);
        info.subscribed = true;
        localStorage.setItem('deviceInfo', JSON.stringify(info));
        setTimeout(() => {
          this.route.navigateByUrl('/user');
        }, 1000);
      }
    });
  }
}
