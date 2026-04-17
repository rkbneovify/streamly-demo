import { DatePipe, NgClass, NgFor, NgIf } from '@angular/common';
import { HttpHeaders } from '@angular/common/http';
import { Component, ElementRef, OnDestroy, OnInit, QueryList, Renderer2, ViewChildren } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { Router, ɵEmptyOutletComponent, RouterLink } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { CrudService } from '../../services/crud.service';
import { noWhitespaceValidator } from '../../services/validators/no-whitespace.validator';
import { UtilsService } from '../../services/utils/utils.service';
import { LocalDatePipe } from '../../pipe/localDate/local-date.pipe';
import { NotificationService } from '../../services/utils/notification.service';
import { finalize } from 'rxjs';
import { SweetalertService } from '../../services/SweetAlert/sweetalert.service';
// import { NgbModal } from '@ng-bootstrap/ng-bootstrap';


declare var bootstrap: any;
@Component({
  selector: 'app-accountsettings',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass, NgIf, DatePipe, NgFor, ɵEmptyOutletComponent, LocalDatePipe, RouterLink],
  templateUrl: './accountsettings.component.html',
  styleUrl: './accountsettings.component.scss',
})
export class AccountsettingsComponent implements OnInit, OnDestroy {
  updateuserDetatils!: FormGroup;
  editMode: boolean = false;
  showPassword: boolean = false;
  usersdetail: any;
  deviceInfo: any;
  access_Token: any;
  updated_data: any;
  homeTimeZone!: string;
  Bearer_token: any;
  planName: string = null;
  renewalDate: any;
  cardDetails!: any;
  cardDetails1: any = null;
  invoices: any = [];
  billingScheme!: string;
  tiredPlansDetails: any;
  invoicesDate!: any;
  showTiredPlan: boolean = false;
  subscribed: boolean;
  planInterval: any;
  interval_count: any;
  qunantity: any;
  qunantity_t: any;
  isLoading: boolean = false;
  userinfo: any;
  togglePasscode: boolean = false;
  total_price: any = 0;
  cancelSubscription: FormGroup;
  isConnectedDeviceShow: boolean = false;

  isSubscription: boolean = false;
  isHoaMainUser: boolean = false;
  isHoaSubUser: boolean | null = false;
  hoaSubUSerSubscription: any = null;
  hoaMainUserSubscription: any = null;
  subscreptionCanceled: boolean = false;
  planExpiredAt: Date;
  otpArray = [0, 1, 2, 3, 4, 5];
  otp: string = '';
  timer: number = 60;
  interval: any;
  canResend: boolean = false;
  isVerifying: boolean = false;
  isOtpError: boolean = false;
  constructor(
    private fb: FormBuilder,
    private crud: CrudService,
    private ts: ToastrService,
    private router: Router,
    private renderer: Renderer2,
    private utilsService: UtilsService,
    private notificationService: NotificationService,
    private sweetAlert: SweetalertService

  ) {
    // console.log('Current url: ', this.router.url)
    this.updateuserDetatils = this.fb.group({
      fullname: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      oldpassword: ['', [Validators.required, noWhitespaceValidator()]],
      newpassword: ['', [Validators.required, noWhitespaceValidator()]],
      zipcode: ['', Validators.required],
    });

    // delete user account form
    this.deleteAccountForm = this.fb.group({
      password: ['', [Validators.required]],
    });

    // delete user account form
    this.cancelSubscription = this.fb.group({
      password: ['', [Validators.required]],
    });

    this.isSubscription = JSON.parse(localStorage.getItem('deviceInfo') || '').subscribed;
  }





  ngOnInit(): void {
    this.getAllDetails();

    this.utilsService.get_all_device().subscribe(() => {
      this.isConnectedDeviceShow = this.utilsService.isCustomUser();
      // Update UI or logic here based on new value
    });
    const jsonData = localStorage.getItem('userDetails');
    this.isHoaSubUser = JSON.parse(localStorage.getItem('hoa_user'));
    console.log("Hoa sub user :", this.isHoaSubUser);
    if (jsonData !== null) {
      try {
        this.usersdetail = JSON.parse(
          localStorage.getItem('userDetails') || ''
        );
      } catch (error) {
        console.error('Error parsing JSON data:', error);
      }
    }
    const deviceInfoData = localStorage.getItem('deviceInfo');
    if (deviceInfoData !== null) {
      try {
        this.deviceInfo = JSON.parse(localStorage.getItem('deviceInfo') || '');
        this.homeTimeZone = this.deviceInfo.timezone;
      } catch (error) {
        console.error('Error parsing JSON data:', error);
      }
    }
    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
    this.updateuserDetatils.controls['email'].setValue(this.usersdetail?.email);
    this.updateuserDetatils.controls['zipcode'].setValue(
      this.usersdetail?.zipcode
    );

    this.subscribed = this.deviceInfo?.subscribed;
    this.notificationService.fetchCount();
  }

  startTimer() {
    this.timer = 60;
    this.canResend = false;

    this.interval = setInterval(() => {
      this.timer--;

      if (this.timer <= 0) {
        this.canResend = true;
        clearInterval(this.interval);
      }
    }, 1000);
  }

  toggleEditMode() {
    this.editMode = !this.editMode;
    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
    this.updateuserDetatils.get('newpassword').markAsUntouched();
    this.updateuserDetatils.get('oldpassword').markAsUntouched();
  }

  saveChanges() {
    // saving changes here
    this.isLoading = true;
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
        this.isLoading = true;
        this.access_Token = JSON.parse(
          localStorage.getItem('deviceInfo') || ''
        );
        const tokenData = this.access_Token?.access_token;
        const headers = new HttpHeaders({
          Authorization: `Bearer ${tokenData}`,
          Accept: 'application/json',
        });
        let body: any = {};
        // if (this.updated_data) {
        if (this.updateuserDetatils.value.fullname) {
          body = {
            name: this.updateuserDetatils.value.fullname,
          };
        }
        if (this.updateuserDetatils.value.oldpassword) {
          body = {
            name: this.updateuserDetatils.value.fullname,
            old_password: this.updateuserDetatils.value.oldpassword,
            password: this.updateuserDetatils.value.newpassword,
          };
        }

        this.crud.postRequest('auth/account-edit', body, headers).subscribe({
          next: (res: any) => {
            if (res.response_code == 200) {
              this.isLoading = false;
              // Update name in local storage
              this.usersdetail.name = body?.name;
              localStorage.setItem(
                'userDetails',
                JSON.stringify(this.usersdetail)
              );

              this.ts.success(res.message);
              this.toggleEditMode();
            } else {
              this.isLoading = false;
              this.ts.error(res.message);
            }
            this.isLoading = false;
          },
          error: (err: any) => {
            this.isLoading = false;
            if (Array.isArray(err.error.message.password)) {
              err.error.message.password.forEach((element) => {
                this.ts.error(element);
              })
            }
            if (!Array.isArray(err.error.message.password)) {
              this.ts.error(err.error.message);
            }

          },
        });
        // }
      } catch (error) {
        console.error('Error parsing JSON data', error);
        this.isLoading = false;
      }
    }

    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
    this.updateuserDetatils.controls['oldpassword'].setValue('');
    this.updateuserDetatils.controls['newpassword'].setValue('');
    // user detials
  }

  getAllDetails() {
    this.isLoading = true;
    this.crud
      .getRequest('auth/setting-details', this.getAuthHeaders())
      .subscribe({
        next: (res: any) => {
          try {
            let planFullName;
            const plan1st = res?.plan?.nickname;
            const plan2nd = res?.plan?.currency;
            const plan3rd = res?.plan?.interval;
            this.planInterval = plan3rd;
            this.interval_count = res?.plan?.interval_count;
            this.userinfo = res?.userinfo;
            // this.usersdetail.name = res?.userinfo?.name
            // this.usersdetail.email = res?.userinfo?.email
            // this.usersdetail.zipcode = res?.userinfo?.zipcode
            // update userDetails on local
            let userData = JSON.parse(localStorage.getItem('userDetails'));

            this.updateuserDetatils.controls['fullname'].setValue(
              res?.userinfo?.name
            );
            this.updateuserDetatils.controls['email'].setValue(
              res?.userinfo?.email
            );
            this.updateuserDetatils.controls['zipcode'].setValue(
              res?.userinfo?.zipcode
            );
            userData.name = res?.userinfo?.name
            userData.email = res?.userinfo?.email
            userData.zipcode = res?.userinfo?.zipcode

            localStorage.setItem(
              'userDetails',
              JSON.stringify(userData)
            );

            this.usersdetail = userData;

            this.isHoaMainUser = res?.userinfo.is_hoa_main_user
            this.isHoaSubUser = res?.userinfo.is_hoa_sub_user
            this.hoaSubUSerSubscription = res?.hoa_sub_user_subscription;
            this.hoaMainUserSubscription = res?.hoa_main_custom_subscription;
            // planFullName = `${plan1st?plan1st:''} ${plan3rd?plan3rd.substring(0, 3):''} ${plan2nd?plan2nd.substring(0,3):''}`;

            this.billingScheme = res?.plan?.billing_scheme;
            this.invoices = res?.invoices;
            if (this.invoices) {
              this.invoicesDate = new Date(this.invoices?.created * 1000);
            }
            this.renewalDate = res?.subscription.renewaldatetime;
            this.planName = res.subscription?.name;
            this.cardDetails = res?.subscription?.payment_method;
            this.cardDetails1 = res?.card_details;
            this.tiredPlansDetails = res?.plan.tiers;
            this.subscreptionCanceled = res?.subscription.canceled;


            this.planExpiredAt = new Date(res?.subscription.plan_expired_at);
            this.qunantity =
              res.subscription.plan_type == 'per_unit'
                ? 1
                : res.subscription.quantity;
            this.total_price =
              res.subscription.plan_type == 'per_unit'
                ? Math.floor(res.plan.amount / 100)
                : 0;

            if (res.subscription.plan_type == 'tiered') {
              this.get_price(this.qunantity, this.tiredPlansDetails);
            }

            console.log('===================>', this.subscribed + '!(!!cardDetails1) ' + !(!!this.cardDetails1) + '   ' + this.cardDetails1)
            const currentPlanID = JSON.stringify(res.plan?.id);
            localStorage.setItem('planId', currentPlanID);
            this.isLoading = false;
          } catch (error) {
            console.log(error)
          } finally {
            this.isLoading = false;
          }
        },
        error: (err) => {
          console.log(err);
          this.isLoading = false;
        },
      });
  }

  getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(
      localStorage.getItem('deviceInfo') || ''
    ).access_token;
    this.Bearer_token = tokenData;
    return new HttpHeaders({
      Authorization: `Bearer ${tokenData}`,
      Accept: 'application/json',
    });
  }

  get_price(quant: any, value: any) {
    const amount10 = +value[0]?.unit_amount / 100;
    const amount18 = +value[1]?.unit_amount / 100;
    const amount16 = +value[2]?.unit_amount / 100;

    const upto10 = value[0]?.up_to;
    const upto18 = value[1]?.up_to;
    if (value) {
      if (quant <= upto10) {
        this.total_price = quant * amount10;
      } else if (quant <= upto18 && quant > upto10) {
        this.total_price = quant * amount18;
      } else if (quant > upto18) {
        this.total_price = quant * amount16;
      }
    }
  }

  // delete subscription
  deleteSub(): void {
    this.isLoading = true;
    this.crud
      .deleteRequest('cancel-subscription/616', this.getAuthHeaders())
      .subscribe({
        next: (res: any) => {
          this.router.navigate(['user/plan']);
          this.ts.success('subscription cancel successfully');
          this.deviceInfo.subscribed = false;
          localStorage.setItem('deviceInfo', JSON.stringify(this.deviceInfo));
          this.isLoading = false;
        },
        error: (err) => {
          this.ts.error(err.error?.error);
          this.isLoading = false;
        },
      });
  }

  // delete user account
  deleteAccountForm: FormGroup;
  showPopDeleteAccount() {
    // document.getElementById('btnShowPopDeleteAccount').click();

    document.getElementById('btn-show-otp-dailog').click();
    this.isLoading = true;
    this.crud.postRequest('auth/account-delete/otp').pipe(finalize(() => {
      this.isLoading = false;
    })).subscribe({
      next: (res: any) => {
        this.startTimer();
        this.ts.success(res.message)
        this.isOtpError = false;
      },
      error: (err: any) => {
        this.ts.error(err.error.message)
        this.isOtpError = true;
      },
    })
  }

  showPopCancelSubc() {
    document.getElementById('btnShowPopCancelSubsc').click();
  }

  deleteUserAccount(): void {
    this.isLoading = true;
    this.crud
      .postRequest('auth/user/delete').pipe(finalize(() => {
        this.isLoading = false;
      }))
      .subscribe({
        next: (res: any) => {
          this.ts.success(res.message);
          localStorage.clear();
          this.router.navigate(['/']);
        },
        error: (err) => {

          this.ts.error(err.error?.message);

        },
      });
  }
  deleteUserAccount_15_04_26(): void {
    this.isLoading = true;
    this.crud
      .postRequest('auth/user/delete', {
        password: this.deleteAccountForm.get('password').value,
      })
      .subscribe({
        next: (res: any) => {
          this.ts.success('Account delete successfully');
          localStorage.clear();
          this.isLoading = false;
          this.router.navigate(['/']);
        },
        error: (err) => {
          this.deleteAccountForm.get('password').reset();
          this.ts.error(err.error?.message);
          this.isLoading = false;
        },
      });
  }

  cancelSubscriptionUserAccount(): void {
    this.isLoading = true;
    this.crud
      .postRequest('auth/verify-password', {
        password: this.cancelSubscription.get('password').value,
      })
      .subscribe({
        next: (res: any) => {
          this.isLoading = false;
          if (res) {
            this.showPopConf();
          }
        },
        error: (err) => {
          this.cancelSubscription.get('password').reset();
          if (err.error?.message.password) {
            this.ts.error(err.error?.message.password[0]);
          } else {
            this.ts.error(err.error?.message);
          }
          this.isLoading = false;
        },
      });
  }

  isString(value: any): boolean {
    return typeof value === 'string';
  }

  showPopConf() {
    document.getElementById('btnShowPopConf').click();
  }

  showPopupDeleteUserAccount() {
    document.getElementById('btnShowPopDeleteUserAccount').click();
  }

  clearDetails() {
    this.deleteAccountForm.get('password').reset();
    this.cancelSubscription.get('password').reset();
  }

  // get currnet time zone 
  getUserTimezoneName(): string {
    try {
      // Get the full timezone name using Intl.DateTimeFormat API
      const date = new Date();
      const timeZoneName = new Intl.DateTimeFormat('en-US', {
        timeZoneName: 'long',
      }).formatToParts(date).find(part => part.type === 'timeZoneName')?.value;

      // Return the timezone name or a fallback if not found
      return timeZoneName || 'Timezone not detected';
    } catch (error) {
      return 'Timezone detection failed';
    }
  }

  clearTimer() {
    if (this.interval) {
      clearInterval(this.interval);
    }
  }

  ngOnDestroy(): void {
    // Close all open modals on component destroy
    const modals = document.querySelectorAll('.modal.show');
    modals.forEach(modal => {
      const modalInstance = bootstrap.Modal.getInstance(modal);
      if (modalInstance) {
        modalInstance.hide();
      }
    });

    this.clearTimer();
  }

  // otp
  @ViewChildren('otpInput') otpInputs!: QueryList<ElementRef>;

  deleteAccountOtpForm = this.fb.group({
    otp: ['', [Validators.required, Validators.maxLength(6)]]
  });

  onOtpInput(event: any, index: number) {
    const input = event.target;
    const value = input.value.replace(/[^0-9]/g, '');

    input.value = value;

    if (value && index < 5) {
      this.otpInputs.toArray()[index + 1].nativeElement.focus();
    }

    this.updateOtp();
  }

  onKeyDown(event: KeyboardEvent, index: number) {
    if (event.key === 'Backspace') {
      const inputs = this.otpInputs.toArray();

      if (!inputs[index].nativeElement.value && index > 0) {
        inputs[index - 1].nativeElement.focus();
      }
    }
  }

  onPaste(event: ClipboardEvent) {
    const pasted = event.clipboardData?.getData('text') || '';

    if (pasted.length === 6) {
      const inputs = this.otpInputs.toArray();

      pasted.split('').forEach((char, i) => {
        inputs[i].nativeElement.value = char;
      });

      this.updateOtp();
      this.deleteAccountOtpForm.markAsTouched();
      this.deleteAccountOtpForm.markAsDirty();
      this.deleteAccountOtpForm.updateValueAndValidity();


    }
  }

  updateOtp() {
    this.otp = this.otpInputs
      .toArray()
      .map(input => input.nativeElement.value)
      .join('');




  }

  verifyOtp(event?: Event) {
    // Use optional chaining to prevent errors if event is null
    event?.preventDefault();

    // It's also good practice to stop propagation
    event?.stopPropagation();

    if (this.deleteAccountOtpForm.invalid) {
      this.ts.error("6 digit otp required");
      return;
    }

    const data = {
      otp: this.otp
    }

    this.isVerifying = true;
    this.crud.postRequest('auth/account-delete/otp/verify', data).pipe(finalize(() => {
      this.isVerifying = false;
    })).subscribe({
      next: (res: any) => {
        document.getElementById('btn-show-otp-dailog').click();
        this.ts.success(res.message)
        this.sweetAlert.confirm("Delete my account", "Do you want to delete your account ?")
          .then((res) => {
            if (res.isConfirmed) {
              // this.sweetAlert.success("Account deleted successfully !")
              this.deleteUserAccount();
            }
          })

      },
      error: (err: any) => {
        this.ts.error(err.error.message)

      },
    });


  }

  resendOtp() {
    this.isLoading = true;
    this.crud.postRequest('auth/account-delete/otp').pipe(finalize(() => {
      this.isLoading = false;
    })).subscribe({
      next: (res: any) => {
        this.clearOtpDetails();
        this.startTimer();
        this.ts.success(res.message)
        this.isOtpError = false;
      },
      error: (err: any) => {
        this.ts.success(err.error.message)
        this.isOtpError = true;
      },
    });
  }

  clearOtpDetails() {
    this.otp = '';
    this.otpInputs.forEach(input => input.nativeElement.value = '');
    this.clearTimer();
  }

  subscriptionResume() {
    this.sweetAlert.confirm("Subscription", "Do you want to resume subscription ?")
      .then((res) => {


        if (res.isConfirmed) {
          this.isLoading = true;
          this.crud.postRequest('auth/subscription/resume').pipe(finalize(() => {
            this.isLoading = false;
          })).subscribe({
            next: (res: any) => {
              this.ts.success(res.message)

            },
            error: (err: any) => {
              this.ts.success(err.error.message)

            },
          });
        }

      });

  }








}
