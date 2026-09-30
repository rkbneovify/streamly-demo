import { Component, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { DataSharingService } from '../../services/data-sharing.service';
import { CommonModule, NgIf } from '@angular/common';
import { CrudService } from '../../services/crud.service';
import { AuthService } from '../../services/authService/authservice.service';
import { SweetalertService } from '../../services/SweetAlert/sweetalert.service';
import { finalize } from 'rxjs';

@Component({
  selector: 'app-subscription-cancel',
  standalone: true,
  imports: [RouterLink, NgIf, CommonModule],
  templateUrl: './subscription-cancel.component.html',
  styleUrl: './subscription-cancel.component.scss'
})
export class SubscriptionCancelComponent implements OnInit {
  receivedData: string;
  access_Token: any;
  error: any;

  constructor(private route: Router,
    private currentData: DataSharingService,
    private crud: CrudService,
    private setLocalData: AuthService,
    private sweetAlert: SweetalertService
  ) {
  }

  // setData
  ngOnInit() {
    this.error = history.state.error;
    // console.log('subscription Errorxxxx : ', history.state.error)
    this.currentData.currentData.subscribe(data => {
      this.receivedData = data;
    });
    this.loginSliders();
  }



  loginSliders() {
    this.sweetAlert.loading();
    this.crud.getRequest('auth/channel-sliders')
      .subscribe({
        next: () => {
          this.sweetAlert.close();
          let info = JSON.parse(localStorage.getItem('deviceInfo'));
          info.subscribed = true;
          localStorage.setItem('deviceInfo', JSON.stringify(info));
          setTimeout(() => {
            this.route.navigateByUrl('/user');
          }, 1000);
        },
        error: (err) => {
          this.sweetAlert.close();
          this.error = err.error;
          console.log('subscription Error : ', err.error)

        }
      });
  }

  handleBtnPlanRequest() {
    document.getElementById('btnShowPopPlan').click();
    // alert('ok');
  }

  subscriptionTerminate() {

    this.sweetAlert.confirm("Subscription", "Do you want to cancel the subscription?").then((result) => {
      if (result.isConfirmed) {
        this.sweetAlert.loading();
        this.crud.getRequest('auth/subscription/terminate')
          .pipe(finalize(() => {
            this.sweetAlert.close();
          }))
          .subscribe({
            next: () => {

              this.route.navigateByUrl('/user');
            },
            error: (err) => {

              this.error = err.error;
              this.sweetAlert.toast(err.error.message, "error");
              console.log('subscription Error : ', err.error)

            }
          });
      }
    });
  }


}

