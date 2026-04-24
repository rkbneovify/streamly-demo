import { CommonModule, KeyValuePipe, NgClass, NgFor, NgIf } from '@angular/common';
import { Component, OnInit, ViewChild } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { SweetalertService } from '../../services/SweetAlert/sweetalert.service';
import { OtpModelComponent } from "../../components/otp-model/otp-model.component";
import { finalize } from 'rxjs';

@Component({
  selector: 'app-plans',
  standalone: true,
  imports: [NgIf, NgClass, CommonModule, NgFor, KeyValuePipe, OtpModelComponent],
  templateUrl: './plans.component.html',
  styleUrl: './plans.component.scss'
})
export class PlansComponent implements OnInit {
  selectTab: string = 'basicPlans';
  all_Plans: any;
  basic_plans: any;
  Tiered_plans: any = [];
  setActivePlanId!: string;
  isSubscribe!: boolean;
  showMessage: boolean = false;
  isLoading: boolean = false;
  constructor(private crud: CrudService, private router: Router, private toaster: ToastrService, private sweetAlert: SweetalertService) { }

  @ViewChild(OtpModelComponent) otpModelChild!: OtpModelComponent;
  ngOnInit(): void {
    this.getAllThePlans();
    this.setActivePlanId = JSON.parse(localStorage.getItem('setActivePlanId'));
    this.isSubscribe = JSON.parse(localStorage.getItem('deviceInfo'))?.subscribed;
  }

  // Get All The Plans
  getAllThePlans(): void {
    this.isLoading = true;
    this.crud.getRequest('stripe/plans').subscribe({
      next: (res) => {
        this.all_Plans = res[0]; // Access the inner array
        // Initialize basic_plans as an empty array
        this.basic_plans = [];

        // Iterate over the plans and categorize them
        this.all_Plans.forEach((plan: any) => {
          if (plan.type === 'per_unit') {
            this.basic_plans.push(plan); // Push plan to basic_plans array

          } else if (plan.type === 'tiered') {
            this.Tiered_plans = [plan]; // Assign plan to Tiered_plans (assuming there's only one tiered plan)
          }
        });

        this.isLoading = false;

      },
      error: (err) => {
        console.log(err);
        this.isLoading = false;

      },

    });
  }
  selectedPlanType: string = '';

  showPopCancelSubc() {
    document.getElementById('btnShowPopCancelSubsc').click();
  }
  selectedplanData(planId: any, planType: any) {
    if (this.isSubscribe) {

      // console.log('Start..')
      // this.showPopCancelSubc()

      this.otpModelChild.action = "cancel_subscription";
      this.otpModelChild.displayText = "Terminate your current subscription";
      this.otpModelChild.resendOtp();
      localStorage.setItem('planType', planType);
      localStorage.setItem('planId', planId);

      return;
    }
    if (planId && planType) {
      localStorage.setItem('planType', planType);
      localStorage.setItem('planId', planId);
      this.router.navigate(['/user/payment-subscription'])
    }
  }


  otherPlan() {
    if (this.Tiered_plans.length == 0) {
      this.toaster.info("No other plans");
      return
    }
    this.selectTab = 'otherPlans';

  }

  onOtpVerified(event: any) {
    if (event.isOtpVeriefied) {
      this.sweetAlert.confirm("Subscription", `
        To switch plans, 
        fully cancel your current subscription first.
         Note that remaining days will be lost.
        `).then((res) => {

        if (res.isConfirmed) {
          this.subscriptionTerminate();
        }
      })

    }
  }



  subscriptionTerminate() {

    this.isLoading = true;
    this.crud.postRequest('auth/subscription/terminate').pipe(finalize(() => {
      this.isLoading = false;
    })).subscribe({
      next: (res: any) => {
        this.toaster.success(res.message)
        this.router.navigate(['/user/payment-subscription']);

      },
      error: (err: any) => {
        this.toaster.success(err.error.message)

      },
    });


  }


}
