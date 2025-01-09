import { DatePipe, DecimalPipe, KeyValuePipe, NgClass, NgFor, NgIf } from '@angular/common';
import {
  ChangeDetectorRef,
  Component,
  ElementRef,
  OnInit,
  ViewChild,
} from '@angular/core';
import {
  FormBuilder,
  FormControl,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import {
  Observable,
  Subject,
  debounceTime,
  distinctUntilChanged,
  of,
  switchMap,
} from 'rxjs';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { Router, RouterLink } from '@angular/router';
import { NgSelectModule } from '@ng-select/ng-select';
import {
  loadStripe,
  Stripe,
  StripeCardElement,
  StripeCardElementOptions,
} from '@stripe/stripe-js';
import { HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-sub-payment',
  standalone: true,
  imports: [
    NgIf,
    NgFor,
    NgClass,
    FormsModule,
    NgSelectModule,
    RouterLink,
    ReactiveFormsModule,
    KeyValuePipe,
    DatePipe,
    DecimalPipe
  ],
  templateUrl: './sub-payment.component.html',
  styleUrl: './sub-payment.component.scss',
})
export class SubPaymentComponent implements OnInit {
  selectedPlanType: string = '';
  checkoutPlans: any;
  discount: any = 0;
  loading: boolean = false;
  private searchCoupnTerm = new Subject<string>();
  private searchZipTerms = new Subject<string>();
  devicePlanAmount: number = 0;
  showplansDetails: boolean = true;
  searchZipInput: string = '';
  city_state_readonly: boolean = false;

  // forms
  counterControl!: FormControl;
  paymentFormDetailSub!: FormGroup;
  wrong_zipcodeDetials!: FormGroup;
  availabilityForm!: FormGroup;
  otpVerificationForm!: FormGroup;
  couponFormControl!: FormControl;
  selectedPlan: any;
  acceptTerm: boolean = false;
  userEmail: string = '';
  private searchTerms = new Subject<string>();
  zipcodedata: any = [];
  cardZipData!: any;
  device_per_unit: number = 5;

  @ViewChild('cardInfo') public cardInfo!: ElementRef;
  _totalAmount: number = 10;
  card: any;
  cardHandler = this.onChange.bind(this);
  cardError: any;
  subscribeNowBtn:string = 'Subscribe Now';

  constructor(
    private crud: CrudService,
    private fb: FormBuilder,
    private el: ElementRef,
    private router: Router,
    private ts: ToastrService,
    private cd: ChangeDetectorRef
  ) {

    const UserEmail = JSON.parse(localStorage.getItem('userDetails')).email?JSON.parse(localStorage.getItem('userDetails')).email:'';

    this.availabilityForm = this.fb.group({
      fullName: ['', Validators.required],
      email: ['', Validators.required],
      zipcode: [null, Validators.required],
      city: ['', Validators.required],
      state: ['', Validators.required],
    });

    this.wrong_zipcodeDetials = new FormGroup({
      w_email: new FormControl(null),
    });

    this.otpVerificationForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      digit1: ['', Validators.required],
      digit2: ['', Validators.required],
      digit3: ['', Validators.required],
      digit4: ['', Validators.required],
      digit5: ['', Validators.required],
      digit6: ['', Validators.required],
    });

    this.paymentFormDetailSub = this.fb.group({
      nameOnCard: ['', Validators.required],
      // cardNumber: ['', Validators.required],
      cardZip: [
        null,
        [Validators.required, Validators.maxLength(5), Validators.minLength(5)],
      ],
      cardEmail: [UserEmail, [Validators.required, Validators.email]],
      couponCode: [''],
      address1: ['', Validators.required],
      address2: [''],
      paymentCity: ['', Validators.required],
      paymentState: ['', Validators.required],
      country: ['', Validators.required],
    });
  }

  async ngOnInit() {
    this.searchTerms
      .pipe(
        debounceTime(300),
        distinctUntilChanged(), // ignore if next search term is same as previous
        switchMap((term: string) => this.getZipcode(term))
      )
      .subscribe((apiResponse: any) => {
        this.zipcodedata = [...apiResponse];
        if (
          apiResponse.length == 1 &&
          apiResponse[0].city &&
          apiResponse[0].state
        ) {
          this.paymentFormDetailSub
            .get('paymentState')
            ?.setValue(apiResponse[0].state);
          this.paymentFormDetailSub
            .get('paymentCity')
            ?.setValue(apiResponse[0].city);
        }
      });

    this.searchZipTerms
      .pipe(
        debounceTime(3000), // Delay API request by 3 seconds
        switchMap((value: string) => {
          if (value.length > 0) {
            return this.getZipcode(value);
          } else {
            return of([]); // Return an empty array if the search term is empty
          }
        })
      )
      .subscribe({
        next: (res: any) => {
          this.cardZipData = res.some((zcode: any) => {
            return zcode.value === this.paymentFormDetailSub.value.cardZip;
          });

          if (this.cardZipData) {
            this.ts.success('ZipCode is valid');
            this.paymentFormDetailSub
              .get('paymentState')
              ?.setValue(res[0].state);
            this.paymentFormDetailSub.get('paymentCity')?.setValue(res[0].city);
          } else {
            this.ts.error('ZipCode is not valid');
          }
        },
        error: (err: any) => {
          console.log(err);
        },
      });

    // this.searchCoupnTerm.pipe(debounceTime(300),switchMap((term:s)))
    this.searchCoupnTerm
      .pipe(debounceTime(3000)) // Delay API request by 3 seconds
      .subscribe((value: string) => {
        this.validateCoupon();
      });

    this.counterControl = this.fb.control(5, [Validators.min(5)]);
    this.couponFormControl = this.fb.control('');

    this.couponFormControl.valueChanges.subscribe(() => {
      if (this.couponFormControl.value === '') {
        // this.applyCoupon();
        this.validateCoupon();
      }
      // this.touchedOnce = true;
    });

    this.selectedPlanType = localStorage.getItem('planType');
    this.selectedPlan = localStorage.getItem('planId');
    if (this.selectedPlan) {
      this.selectedPlanById();
    }
  }

  // get the zipcode acc. to the input
  getZipcode(searchTerm: string): Observable<any> {
    if (searchTerm.length > 0) {
      const searchData = { search: searchTerm };
      return this.crud.postRequest('auth/getzipcodes', searchData);
    } else {
      return of([]); // Return an empty array if the search term is empty
    }
  }

  couponValidationResult: any;
  couponDataDetails: any;
  basicPrice: any = 20;
  totalPrice: any = this.basicPrice;
  grandTotalPrice: any = this.basicPrice;
  couponCode:string = '';
  validateCoupon() {
    const coupon = this.couponFormControl.value;
    if (coupon) {
      this.crud.postRequest('validate-coupon', { coupon }).subscribe({
        next: (data: any) => {
          if (data.message != `No such coupon: '${coupon}'`) {
            this.couponValidationResult = data;
            this.ts.success(data.message);
            this.couponDataDetails = data;
            this.couponCode = coupon;
            this.grandTotalPrice = this.totalPrice;
            if (this.couponDataDetails) {
              this.discountCode();
            }
          } else {
            this.ts.error(data.message);
            this.discount = 0;
            this.couponDataDetails = null;
            this.grandTotalPrice = this.totalPrice;
          }
        },
        error: (err: any) => {
          console.error('Error validating coupon:', err);
          this.ts.error(err.error.message.coupon[0]);
          this.grandTotalPrice = this.totalPrice;
          this.couponDataDetails = null;
          this.couponCode = '';
        },
        complete:()=>{
          this.couponFormControl.reset();
        }
      });
    } else {
      this.ts.error('Enter Coupon');
      this.couponDataDetails = null;
      this.couponCode = ''
      this.discount = 0;
      this.grandTotalPrice = this.totalPrice;
    }
    // }
  }

  // counter inc
  increment() {
    if (this.counterControl.value < 1000) {
      this.counterControl.setValue(+this.counterControl.value + 1);
      this.device_per_unit = Number(+this.counterControl.value);
      this.getTotalPrice();
    }
  }

  decrement() {
    if (this.counterControl.value > 5) {
      this.counterControl.setValue(+this.counterControl.value - 1);
      this.device_per_unit = Number(+this.counterControl.value);
      this.getTotalPrice();
    }
    else{
      this.ts.error('Devices in not less then 5.')
    }
  }

  getTotalPrice() {
    if (this.checkoutPlans) {
      this.totalPrice = 0;
      const amount10 =
        +this.checkoutPlans.plandetails.tiers[0].unit_amount / 100;
      const amount18 =
        +this.checkoutPlans.plandetails.tiers[1].unit_amount / 100;
      const amount16 =
        +this.checkoutPlans.plandetails.tiers[2].unit_amount / 100;

      if (this.device_per_unit <= 10) {
        this.devicePlanAmount = amount10;
        this.totalPrice = amount10 * this.device_per_unit;
      } else if (this.device_per_unit <= 15) {
        this.devicePlanAmount = amount18;
        this.totalPrice = amount18 * this.device_per_unit;
      } else {
        this.devicePlanAmount = amount16;
        this.totalPrice = amount16 * this.device_per_unit;
      }
      this.grandTotalPrice = this.totalPrice;
      this.discountCode();
    }
  }

  onChange(error: any) {
    if (error) {
      this.cardError = error.message;
    } else {
      this.cardError = null;
    }
    this.cd.detectChanges();
  }

  stripe: any;
  elements: any;

  ngOnDestroy() {
    if (this.card) {
      this.card.removeEventListener('change', this.cardHandler);
      this.card.destroy();
    }
  }

  initiateCardElement() {
    const cards = {
      hidePostalCode: true,
      style: {
        base: {
          iconColor: '#666EE8',
          color: '#ffffff',
          lineHeight: '40px',
          fontWeight: 300,
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSize: '15px',
          magin: '0 0 0 -12px',
          '::placeholder': {
            color: '#bdbdbd',
          },
        },
      },
    };

    if (true) {
      const cardStyle = {
        base: {
          color: '#32325d',
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSmoothing: 'antialiased',
          fontSize: '16px',
          '::placeholder': {
            color: '#aab7c4',
          },
        },
        invalid: {
          color: '#fa755a',
          iconColor: '#fa755a',
        },
      };
      setTimeout(() => {
        this.card = this.elements.create('card', cards);
        this.card.mount(this.cardInfo.nativeElement);
        this.card.addEventListener('change', this.cardHandler);
      }, 4000);
    }
  }

  async createStripeToken() {
    this.paymentFormDetailSub.markAllAsTouched();
    this.loading = true;
    const { token, error } = await this.stripe.createToken(this.card);
      if (token) {
        this.submitPaymentDetails(token);
      }
      if (token) {
        this.onSuccess(token);
      } else {
        this.onError(error);
      }
  }

  onSuccess(token: any) {}
  onError(error: any) {
    if (error.message) {
      this.cardError = error.message;
      this.ts.error(this.cardError);
      this.loading = false;
    }
  }

  discountCode() {
    this.grandTotalPrice = this.totalPrice;
    if (this.couponDataDetails) {
      this.discount = this.couponDataDetails?.amountoff;
      if (this.couponDataDetails?.amountoff) {
        this.grandTotalPrice = this.totalPrice - this.discount;
      } else if (this.couponDataDetails?.percentoff) {
        this.discount =
        this.totalPrice * (this.couponDataDetails?.percentoff / 100);
        this.grandTotalPrice = this.totalPrice - this.discount;
      }
    } else {
      this.discount = 0;
    }
  }

  submitPaymentDetails(token: any) {
    const formData = this.paymentFormDetailSub.value;
    let trial_day;
    let quantity = this.device_per_unit;

    if (this.checkoutPlans.plandetails?.trial_period_days) {
      trial_day = this.checkoutPlans.plandetails?.trial_period_days;
    } else {
      trial_day = 0;
    }

    let body = {
      name: this.paymentFormDetailSub.value.nameOnCard,
      email: this.paymentFormDetailSub.value.cardEmail,
      stripeToken: token.id,
      trial_days: trial_day,
      plan_id: this.selectedPlan,
      quantity: quantity,
      line1: this.paymentFormDetailSub.value.address1,
      line2: this.paymentFormDetailSub.value.address2,
      city: this.paymentFormDetailSub.value.paymentCity,
      state: this.paymentFormDetailSub.value.paymentState,
      country: this.paymentFormDetailSub.value.country,
      card_brand: token.card.brand,
      card_last_four: token.card.last4,
      zip_code: this.paymentFormDetailSub.value.cardZip,
      coupon:  this.couponCode,
    };

    this.crud
      .postRequest('subscription-payment', body, this.getAuthHeaders())
      .subscribe({
        next: (res: any) => {
          this.loading = false;
          setTimeout(() => {
            this.router.navigateByUrl('/user');
          }, 1000);
          this.ts.success(res.message);
          let info = JSON.parse(localStorage.getItem('deviceInfo'));
          info.subscribed = true;
          localStorage.setItem('deviceInfo', JSON.stringify(info));
          localStorage.setItem('setActivePlanId', JSON.stringify(this.selectedPlan));
        },
        error: (err: any) => {
          this.loading = false;
          this.ts.error(err.error.error);
        },
      });
  }

  paymentShowData() {
    const formData = this.paymentFormDetailSub.value;
    this.loading = false;
  }

  async initializeKey(key: any) {
    this.stripe = await Stripe(key);
    this.elements = this.stripe.elements();
    if (this.stripe) {
      this.initiateCardElement();
    }
  }

  // selectedplansbyid
  selectedPlanById() {
    if (this.selectedPlan) {
      const id = this.selectedPlan;

      if (this.selectedPlanType == 'per_unit') {
        this.crud.getRequest('get-basic-plan/' + id).subscribe({
          next: (data: any) => {
            this.checkoutPlans = data;
            this.totalPrice = this.checkoutPlans.plandetails.amount / 100;
            this.grandTotalPrice = this.checkoutPlans.plandetails.amount / 100;

            this.initializeKey(data.STRIPE_KEY);
            // this.initiateCardElement();
          },
          error: (err: any) => {
            console.log(err);
          },
        });
      }

      if (this.selectedPlanType == 'tiered') {
        this.crud.getRequest('get-tier-plan/' + id).subscribe({
          next: (data: any) => {
            this.checkoutPlans = data;
            // this.initiateCardElement();
            // this.paymentFormDetailSub
            //   .get('cardEmail')
            //   ?.setValue(this.availabilityForm.get('email')?.value);
            // console.log(
            //   'avail email',
            //   this.availabilityForm.get('email')?.value
            // );
            this.initializeKey(data.STRIPE_KEY);
          },
          error: (err: any) => {
            console.log(err);
          },
        });
      }
    }
  }

  Bearer_token: any;
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
  // searchZipInput: string = '';
  // city_state_readonly: boolean = false;
  searchZipCardInput: string = '';

  onSearch(searchTerm: any): void {
    this.searchTerms.next(searchTerm.term);
    this.paymentFormDetailSub.get('cardZip')?.setValue(searchTerm.term);
    this.searchZipCardInput = searchTerm.term;
    this.city_state_readonly = false;
  }

  onZipChange(event: any) {
    const searchTerm = event.target.value;
    this.searchZipTerms.next(searchTerm);
  }

  onSelect(selectedValue: any): void {
    if (typeof selectedValue !== 'undefined' && selectedValue !== null) {
      this.paymentFormDetailSub
        .get('paymentCity')
        ?.setValue(selectedValue.city);
      this.paymentFormDetailSub
        .get('paymentState')
        ?.setValue(selectedValue.state);
      this.paymentFormDetailSub.get('cardZip')?.setValue(selectedValue.value);
      this.searchZipInput = selectedValue.value;
    } else {
      this.paymentFormDetailSub.get('paymentCity')?.setValue('');
      this.paymentFormDetailSub.get('paymentState')?.setValue('');
    }
  }

  checkCardDetail: boolean = false;
  checkCardDetails() {
    const element = this.cardInfo.nativeElement;
    if (this.paymentFormDetailSub.valid && element) {
      const hasClassComplete = element.classList.contains(
        'StripeElement--complete'
      );
      if (hasClassComplete) {
        this.checkCardDetail = true;
      } else {
        this.checkCardDetail = false;
      }
    } else {
    }
  }

  ngAfterViewChecked(): void {
    this.checkCardDetails();
  }

  // Function to prevent input of more than 5 digits
  onZipcodeInput(event: any) {
    const input = event.target.value;
    if (input.length >= 5) {
      event.preventDefault(); // Prevents user from entering more than 5 characters
    }
  }
}
