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
  plans: any[] = [];
  selectedPlan: number = 0;
  customSubscriptionExpired: null | boolean = null;
  customSubscriptionExpiredAt: null | Date = null;
  deviceLimit: null | number = null;
  customPlanRequest: null | any = null;
  stripeSubscription: null | any = null;
  constructor(private route: Router,
    private currentData: DataSharingService,
    private crud: CrudService,
    private setLocalData: AuthService,
    private sweetAlert: SweetalertService
  ) {
  }

  // setData
  ngOnInit() {
    this.loadCustomSubscriptionPlans();
    this.subscriptionStatusCheck();
    // this.error = history.state.error;
    // // console.log('subscription Errorxxxx : ', history.state.error)
    // this.currentData.currentData.subscribe(data => {
    //   this.receivedData = data;
    // });
    // this.loginSliders();

  }


  subscriptionStatusCheck() {
    this.sweetAlert.loading();
    this.crud.postRequest('auth/subscription/check')
      .subscribe({
        next: (res: any) => {
          this.sweetAlert.close();
          // console.log('Response : ', res.data);



        },
        error: (err: any) => {
          this.sweetAlert.close();
          // this.sweetAlert.toast(err.error.message, "error");
          // console.log('Errorx : ', err.error.subscription);
          this.customPlanRequest = err.error.plan_request;
          this.deviceLimit = err.error.device_limit;
          this.customSubscriptionExpired = err.error.custom_subscription_expired;
          this.customSubscriptionExpiredAt = new Date(err.error.custom_subscription_expired_at.replace(' ', 'T'));
          if (this.customPlanRequest != null) {
            this.selectedPlan = this.customPlanRequest.plan_id;
          }
        }
      });
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

  loadCustomSubscriptionPlans() {
    this.sweetAlert.loading();
    this.crud.getRequest('auth/custom/subscription/plan')
      .subscribe({
        next: (res: any) => {
          this.sweetAlert.close();
          // console.log('Response : ', res.data);
          this.plans = res.data;
          if (this.plans.length > 0) {
            this.selectedPlan = this.plans[0].id;
          }


        },
        error: (err: any) => {
          this.sweetAlert.close();
          this.sweetAlert.toast(err.error.message, "error");

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
            error: (err: any) => {


              this.sweetAlert.toast(err.error.message, "error");


            }
          });
      }
    });
  }

  selectPlan(plan: any): void {
    this.selectedPlan = plan.id;
  }

  planRequestByUser(): void {
    this.sweetAlert.confirm("Plant Request", "Do you want to request this plan?").then((result) => {
      if (result.isConfirmed) {
        this.sweetAlert.loading();
        const data = {
          'plan_id': String(this.selectedPlan)
        };
        this.crud.postRequest('auth/subscription/plan-request', data)
          .subscribe({
            next: (res: any) => {
              this.sweetAlert.close();
              this.sweetAlert.toast(res.message, "success");
            },
            error: (err: any) => {
              this.sweetAlert.close();
              this.sweetAlert.toast(err.error.message, "error");
            }
          });
      }
    });
  }

  signOut(): void {
    this.sweetAlert.confirm("Logout", "Do you want to logout?").then((result) => {
      if (result.isConfirmed) {
        this.sweetAlert.loading();
        this.crud.postRequest('auth/logout')
          .subscribe({
            next: () => {
              this.sweetAlert.close();
              this.route.navigateByUrl('/user');
            },
            error: (err: any) => {
              this.sweetAlert.close();

              // here protecting from some unknon error
              if (err.status !== 0) {
                this.sweetAlert.toast(err.error.message, "error");
              }




            }
          });
      }
    });
  }


}

