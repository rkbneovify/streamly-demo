import { NgIf, NgFor, KeyValuePipe } from '@angular/common';
import {
  AfterViewInit,
  ChangeDetectorRef,
  Component,
  ElementRef,
  HostListener,
  NgModule,
  OnInit,
  Renderer2,
  ViewChild,
} from '@angular/core';
import { NgClass } from '@angular/common';
import { CrudService } from '../../services/crud.service';
import {
  FormBuilder,
  FormControl,
  FormGroup,
  FormsModule,
  Validators,
  ReactiveFormsModule,
  NgModel,
} from '@angular/forms';
import { NgSelectModule } from '@ng-select/ng-select';
import { RouterLink } from '@angular/router';
import {
  Observable,
  Subject,
  debounceTime,
  distinctUntilChanged,
  of,
  switchMap,
} from 'rxjs';
import { ToastrService } from 'ngx-toastr';
import { environment } from '../../../environments/environment';
import {
  loadStripe,
  Stripe,
  StripeCardElement,
  StripeCardElementOptions,
} from '@stripe/stripe-js';

// declare var $: any;
// declare var stripe: any;
// declare var elements: any
@Component({
  selector: 'app-register',
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
  ],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss',
})

export class RegisterComponent implements OnInit, AfterViewInit {
  // stripe: any;
  selectedTab: string = 'Availability';
  isWidth767: number = window.innerWidth;
  userState: string = '';
  userCity: string = '';
  showplansDetails: boolean = true;
  private searchTerms = new Subject<string>();
  zipcodedata: any = [];
  searchZipInput: string = '';
  city_state_readonly: boolean = false;
  resendButtonDisabled = false;
  countdown = 60;
  all_Plans: any = [];
  checkoutPlans: any;
  device_per_unit: number = 1;
  planAmount!: number;
  devicePlanAmount: number = 0;
  couponValidationResult: any;
  couponDataDetails: any;
  zipcardShow: boolean = false;
  cardZipData!: any;
  showTiredPlan: boolean = false;
  private searchCoupnTerm = new Subject<string>();
  private searchZipTerms = new Subject<string>();



  // forms
  counterControl!: FormControl;
  paymentFormDetails!: FormGroup;
  wrong_zipcodeDetials!: FormGroup;
  availabilityForm!: FormGroup;
  otpVerificationForm!: FormGroup;
  selectedPlan: any;
  selectedPlanType: string = '';
  acceptTerm: boolean = false;

  @ViewChild('cardInfo') public cardInfo!: ElementRef;
  _totalAmount: number = 10;
  card: any;
  cardHandler = this.onChange.bind(this);
  cardError: any;
  // ======================== constructor ================================
  constructor(
    private crud: CrudService,
    private fb: FormBuilder,
    private el: ElementRef,
    private ts: ToastrService, private cd: ChangeDetectorRef
  ) {
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

    this.paymentFormDetails = this.fb.group({
      nameOnCard: ['', Validators.required],
      // cardNumber: ['', Validators.required],
      cardZip: ['', Validators.required],
      cardEmail: [this.availabilityForm.value.email, [Validators.required, Validators.email]],
      couponCode: [''],
      address1: ['', Validators.required],
      address2: [''],
      paymentCity: ['', Validators.required],
      paymentState: ['', Validators.required],
      country: ['', Validators.required]
    });
  }

  // async ngAfterViewInit(){
  //   // if(this.selectedTab == 'Payment'){
  //     this.stripe = await loadStripe(environment.stripe.publicKey);
  //   console.log(this.stripe);

  //   const el = this.stripe?.elements();

  //   const card = el.create('card');

  //   card.mount('#card-data');

  //   card.on('change', (ev: any) => {
  //     const displayError: any = document.getElementById('card-errors');
  //     ev.error
  //       ? (displayError.textContent = ev.error.message)
  //       : (displayError.textContent = '');
  //   });

  //   const button = document.getElementById('button');
  //   button?.addEventListener('click', async (event) => {
  //     event.preventDefault();
  //     const ownerInfo = {
  //       owner: { name: 'Deepak' },
  //       amount: 1000,
  //       currency: 'USD',
  //     };

  //     try {
  //       const result: any = await this.stripe.createPaymentMethod({
  //         type: 'card',
  //         card,
  //         billing_details: { name: 'Deepak' },
  //       });
  //       const tokenInfo = await this.stripe.createToken(card, ownerInfo);
  //       console.log('payment Method', result);
  //       console.log('Token Created', tokenInfo);
  //     } catch (error) {
  //       console.log(error);
  //     }
  //   });
  //   // }
  // }

  // ============================== ngOnIt==========================

  // async ngAfterViewInit() {

  //   if (this.selectedTab == 'Payment') {

  //     try {
  //       this.stripe = await loadStripe(environment.stripe.publicKey);
  //       const el = this.stripe?.elements();
  //       const card = el.create('card');

  //       // Check if the card-data element exists before mounting
  //       const cardDataElement = document.getElementById('card-data');
  //       if (cardDataElement) {
  //         card.mount('#card-data');

  //         card.on('change', (ev: any) => {
  //           const displayError: any = document.getElementById('card-errors');
  //           ev.error
  //             ? (displayError.textContent = ev.error.message)
  //             : (displayError.textContent = '');
  //         });

  //         const button = document.getElementById('button');
  //         button?.addEventListener('click', async (event) => {
  //           event.preventDefault();
  //           const ownerInfo = {
  //             owner: { name: 'Deepak' },
  //             amount: 1000,
  //             currency: 'USD',
  //           };

  //           try {
  //             const result: any = await this.stripe.createPaymentMethod({
  //               type: 'card',
  //               card,
  //               billing_details: { name: 'Deepak' },
  //             });
  //             const tokenInfo = await this.stripe.createToken(card, ownerInfo);
  //             console.log('payment Method', result);
  //             console.log('Token Created', tokenInfo);
  //           } catch (error) {
  //             console.log(error);
  //           }
  //         });
  //       } else {
  //         console.error('Element with ID card-data not found in the DOM.');
  //       }
  //     } catch (error) {
  //       console.error('Error loading Stripe:', error);
  //     }

  //   }
  // }

  async ngOnInit() {
    this.searchTerms
      .pipe(
        debounceTime(300),
        distinctUntilChanged(), // ignore if next search term is same as previous
        switchMap((term: string) => this.getZipcode(term))
      )
      .subscribe((apiResponse: any) => {
        this.zipcodedata = [...apiResponse];
        console.log('API Response:', apiResponse);
      });

    // this.searchZipTerms
    // .pipe(
    //   debounceTime(300),
    //   distinctUntilChanged(), // ignore if next search term is same as previous
    //   switchMap((term: string) => this.getZipcode(term))
    // )
    // .subscribe((apiResponse: any) => {
    //   this.zipcodedata = [...apiResponse];
    //   console.log('API Response:', apiResponse);
    // });

    // this.searchZipTerms
    // .pipe(debounceTime(3000)) // Delay API request by 3 seconds
    // .subscribe((value: string) => {
    //   this.getZipcode(value).subscribe({
    //     next:(res:any)=>{
    //       console.log('======= res>',res);
    //     },
    //     error:(err:any)=>{console.log(err)}
    //   });
    // });

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
          console.log('======= res>', res);
          this.cardZipData = res.some((zcode: any) => {
            console.log(zcode.value, '==========================', this.paymentFormDetails.value.cardZip);
            return zcode.value === this.paymentFormDetails.value.cardZip;
          });;

          if (this.cardZipData) {
            console.log(this.cardZipData, 'data in if');
            this.ts.success('ZipCode is valid');
            this.paymentFormDetails.get('paymentState')?.setValue(res[0].state);
            this.paymentFormDetails.get('paymentCity')?.setValue(res[0].city);
          } else {
            this.ts.error("ZipCode is not valid")
          }
          console.log(this.cardZipData, '========>cardzip');
        },
        error: (err: any) => {
          console.log(err);
        }
      });

    // this.searchCoupnTerm.pipe(debounceTime(300),switchMap((term:s)))
    this.searchCoupnTerm
      .pipe(debounceTime(3000)) // Delay API request by 3 seconds
      .subscribe((value: string) => {
        this.validateCoupon();
      });

    this.counterControl = this.fb.control(1, [Validators.min(1)]);

    // stripe call
    // this.addStripe();
    // addStripe() {
    //   this.STRIPE = Stripe(environment.stripe.publicKey);
    //   console.log(this.STRIPE);

    //   const elements = this.STRIPE.elements();
    //   const cardElement = elements.create('card', { hidePostalCode: true });

    //   cardElement.mount('#card-element');

    //  const cardHolderName = document.getElementById('name');
    //  const cardButton = document.getElementById('card-button');
    //  const clientSecret = cardButton.dataset.secret;
    //  let validCard = false;
    //  const cardError = document.getElementById('card-errors');

    //  cardElement.addEventListener('change', function(event) {

    //      if (event.error) {
    //          validCard = false;
    //          cardError.textContent = event.error.message;
    //      } else {
    //          validCard = true;
    //          cardError.textContent = '';
    //      }
    //  });
    // if(this.selectedTab == 'Payment'){
    //   this.stripe = await loadStripe(environment.stripe.publicKey);
    // console.log(this.stripe);

    // const el = this.stripe?.elements();

    // const card = el.create('card');

    // card.mount('#card-data');

    // card.on('change', (ev: any) => {
    //   const displayError: any = document.getElementById('card-errors');
    //   ev.error
    //     ? (displayError.textContent = ev.error.message)
    //     : (displayError.textContent = '');
    // });

    // const button = document.getElementById('button');
    // button?.addEventListener('click', async (event) => {
    //   event.preventDefault();
    //   const ownerInfo = {
    //     owner: { name: 'Deepak' },
    //     amount: 1000,
    //     currency: 'USD',
    //   };

    //   try {
    //     const result: any = await this.stripe.createPaymentMethod({
    //       type: 'card',
    //       card,
    //       billing_details: { name: 'Deepak' },
    //     });
    //     const tokenInfo = await this.stripe.createToken(card, ownerInfo);
    //     console.log('payment Method', result);
    //     console.log('Token Created', tokenInfo);
    //   } catch (error) {
    //     console.log(error);
    //   }
    // });
    // }
  }

  onSearch(searchTerm: any): void {
    console.log(searchTerm.term);
    this.searchTerms.next(searchTerm.term);
    this.availabilityForm.get('zipcode')?.setValue(searchTerm.term);
    this.searchZipInput = searchTerm.term;
    this.city_state_readonly = false;
  }

  onZipChange(event: any) {
    const searchTerm = event.target.value;
    this.searchZipTerms.next(searchTerm);
  }


  onSelect(selectedValue: any): void {
    this.availabilityForm.get('city')?.setValue(selectedValue.city);
    this.availabilityForm.get('state')?.setValue(selectedValue.state);
    this.availabilityForm.get('zipcode')?.setValue(selectedValue.value);
    if(this.selectedTab == 'Payment'){
      this.paymentFormDetails.get('paymentCity')?.setValue(selectedValue.city);
    this.paymentFormDetails.get('paymentState')?.setValue(selectedValue.state);
    this.paymentFormDetails.get('cardZip')?.setValue(selectedValue.value);
    }
    this.city_state_readonly = true;
    this.searchZipInput = selectedValue.value;
  }

  @HostListener('window:resize', ['$event'])
  onResize(event: Event): void {
    this.isWidth767 = window.innerWidth;
    // console.log(this.isWidth767);
  }

  // click to open the login popup
  showAlreadyUserModal(): void {
    document.getElementById('btnstaticAlreadyUser')?.click();
  }
  // click to open the correct zipcode popup
  showCorrectZipcode(): void {
    const currentEmail = this.availabilityForm.get('email')?.value;
    console.log('current email-->', currentEmail);
    if (currentEmail) {
      this.otpVerificationForm.get('email')?.setValue(currentEmail);
    }
    document.getElementById('btnstaticZipCorrectUser')?.click();
  }
  // click to open the wrong zipcode popup
  showWrongZipcode(): void {
    document.getElementById('btnstaticZipWrongUser')?.click();
  }

  applyclassForAva(): boolean {
    // console.log(this.isWidth767 === 767 && this.selectedTab === 'Availability');
    return this.isWidth767 <= 767 && this.selectedTab === 'Availability';
  }
  applyclassForVer(): boolean {
    return this.isWidth767 <= 767 && this.selectedTab === 'Verification';
  }

  // Get All The Plans
  getAllThePlans(): void {
    this.crud.getRequest('stripe/plans').subscribe({
      next: (res) => {
        this.all_Plans = res;
        if (this.all_Plans.length > 0) {
          console.log('Plans data --> ', this.all_Plans);

          this.all_Plans[0].forEach((el: any) => {
            console.log(
              el.id,
              'name',
              el.amount,
              'nikename',
              el.nickname,
              el.metadata
            );
          });
        }
      },
      error: (err) => console.log('Plans error --> ', err),
    });
  }

  // selectedplansbyid
  selectedPlanById() {
    if (this.selectedPlan) {
      const id = this.selectedPlan.id;

      if (this.selectedPlanType == 'per_unit') {
        this.crud.getRequest('get-basic-plan/' + id).subscribe({
          next: (data: any) => {
            console.log('===========basic plan>', data);
            this.checkoutPlans = data;
            console.log('checkout================>', this.checkoutPlans);
            this.selectedTab = 'Payment';
            this.paymentFormDetails.get('cardEmail')?.setValue(this.availabilityForm.get('email')?.value);
            console.log('avail email',this.availabilityForm.get('email')?.value);
            console.log('avail email 2',this.availabilityForm.value.email);

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
            console.log('============ tiered plans ==>', data);
            this.checkoutPlans = data;
            this.selectedTab = 'Payment';
            // this.initiateCardElement(); 
            this.paymentFormDetails.get('cardEmail')?.setValue(this.availabilityForm.get('email')?.value);
            console.log('avail email',this.availabilityForm.get('email')?.value);
            console.log('avail email 2',this.availabilityForm.value.email);
            this.initializeKey(data.STRIPE_KEY);
          },
          error: (err: any) => {
            console.log(err);
          },
        });
      }
    }
  }

  // if the zipcode is Invalid then send the details
  getZipCodeInvalid(data: any): void {
    const userDetails = {
      email: data.w_email,
      zip: this.searchZipInput,
      city: this.userCity,
      state: this.userState,
    };
    console.log(userDetails);
    this.crud.postRequest('auth/zipcodeInquery', userDetails).subscribe({
      next: (data: any) => {
        this.ts.success(data.success);
        document.getElementById('closeWrongZipForm')?.click();
      },
      error: (err) => this.ts.error(err.error),
    });
  }

  // getotp
  getOtpWithMail(verifiMailDetails: any) {
    console.log('hello im here', verifiMailDetails);
    this.crud
      .postRequest('auth/send-otp', { email: verifiMailDetails })
      .subscribe({
        next: (data: any) => {
          console.log('otpdata-->', data);
          this.ts.success(data.message);
        },
        error: (err: any) => {
          this.ts.success(err.message);
          console.log('otp-error-->', err);
        },
      });
  }

  verifyOtpWithMail(verifyDetails: any) {
    this.crud.postRequest('auth/verify-otp', verifyDetails).subscribe({
      next: (data: any) => {
        console.log('otpdata verify-->', data);
        this.ts.success(data.message);
        setTimeout(() => {
          if (data) {
            this.getAllThePlans();
            this.selectedTab = 'Plans';
          }
        }, 1000);
      },
      error: (err) => {
        this.ts.error(err.error.error);
        // this.otpVerificationForm.reset();
        Object.keys(this.otpVerificationForm.controls).forEach(key => {
          if (key !== 'email') {
            this.otpVerificationForm.get(key)?.reset(); // Reset the value of the control
          }
        });
      },
    });
  }

  // ============================
  onInputChange() {
    // Handle OTP input change if needed
    const otpValue = this.getOtpValue();
    console.log('Email:', this.otpVerificationForm.get('email')?.value);
    console.log('OTP:', otpValue);
    // Move focus to the next input box
    const currentControlName = this.getCurrentControlName();
    console.log('currentControlName--->', currentControlName);
    const currentDigit = parseInt(
      currentControlName.charAt(currentControlName.length - 1)
    );

    if (currentDigit < 6) {
      const nextDigit = currentDigit + 1;
      const nextInput = this.el.nativeElement.querySelector(
        `#digit${nextDigit}`
      );
      nextInput.focus();
    }
  }

  // getotpvalue
  private getOtpValue(): string {
    return Object.values(this.otpVerificationForm.value)
      .filter((value, index) => index !== 0) // Exclude email from the array
      .join('');
  }

  private getCurrentControlName(): string {
    const activeElementId =
      this.el.nativeElement.ownerDocument.activeElement.id;
    return activeElementId;
  }

  // Autofill OTP fields when pasting
  onPaste(event: ClipboardEvent) {
    const clipboardData = event.clipboardData;
    if (clipboardData) {
      const pastedText = clipboardData.getData('text');
      if (pastedText.length === 6 && !isNaN(Number(pastedText))) {
        for (let i = 0; i < pastedText.length; i++) {
          const control = this.otpVerificationForm.get(`digit${i + 1}`);
          if (control) {
            control.setValue(pastedText[i]);
          }
        }
        for (let i = 1; i <= pastedText.length; i++) {
          const input = this.el.nativeElement.querySelector(`#digit${i}`);
          if (input) {
            this.onInputChange();
          }
        }
        event.preventDefault();
      }
    }
  }

  // resent otp
  resendOtp() {
    this.selectedTab = 'Verification';
    const email = this.availabilityForm.get('email')?.value;
    if (email) {
      this.getOtpWithMail(email);
      console.log('Working');
      this.resendButtonDisabled = true;
      const timer = setInterval(() => {
        if (this.countdown > 0) {
          this.countdown--;
        } else {
          clearInterval(timer);
          this.resendButtonDisabled = false;
          this.countdown = 60;
        }
      }, 1000);
    } else {
      console.log('Email is required.');
    }
  }

  // check the zipcode is valid or not
  checkZipCodeValid(zipcode: any): boolean {
    const search = zipcode;
    return this.zipcodedata.some((zcode: any) => {
      console.log(zcode.value, '==========================', search);
      return zcode.value === search;
    });
  }

  // check the zipcode is valid or not
  checkcardZipCodeValid(zipcode: any): boolean {
    const search = zipcode;
    return this.cardZipData.some((zcode: any) => {
      console.log(zcode.value, '==========================', search);
      if (zcode.value === search) {
        this.paymentFormDetails.get('paymentState')?.setValue('');

      }
      return zcode.value === search;
    });
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

  // check the mail is already register or not
  checkEmailRegistration(data: any) {
    this.userState = data.state;
    this.userCity = data.city;
    const email = { email: data.email };
    this.crud.postRequest('auth/checkemail', email).subscribe({
      next: (resp: any) => {
        const isUserExist = resp.exists;
        const message = resp.message;
        if (isUserExist) {
          console.log('register function', message);
          this.showAlreadyUserModal();
        } else {
          console.log('register function', 'check zip code');
          console.log(
            'register function',
            'value',
            this.checkZipCodeValid(this.searchZipInput),
            'zipInput',
            this.searchZipInput
          );
          const isZipCodeValid = this.checkZipCodeValid(this.searchZipInput);
          if (isZipCodeValid) {
            this.showCorrectZipcode();
          } else {
            this.showWrongZipcode();
            console.log('register function', 'Invalid zip code.');
          }
        }
        console.log(resp);
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  submitForm() {
    if (this.availabilityForm.valid) {
      const data = this.availabilityForm.value;
      this.checkEmailRegistration(data);
      console.log('form Data :', this.availabilityForm.value);
    } else {
      console.log(this.availabilityForm.errors);
    }
  }

  submitFormWrong() {
    this.getZipCodeInvalid(this.wrong_zipcodeDetials.value);
    console.log('wrong_zipcode value -->>', this.wrong_zipcodeDetials.value);
  }

  backbutton(data: any) {
    if (data == 'Plans') {
      this.selectedTab = 'Plans';
      // this.getAllThePlans();
    } else {
      this.selectedTab = 'Terms';
    }
  }

  otpVerifySubmit() {
    const otpAndEmail = {
      email: this.otpVerificationForm.get('email')?.value,
      otp: this.getOtpValue(),
    };
    this.verifyOtpWithMail(otpAndEmail);
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
    if (this.counterControl.value > 1) {
      this.counterControl.setValue(+this.counterControl.value - 1);
      this.device_per_unit = Number(+this.counterControl.value);
      this.getTotalPrice();
    }
  }

  //
  selectedTabTerms(data: any) {
    if (data) {
      this.selectedPlanType = data.type;
      this.selectedPlan = data;
      this.ts.success('Plan Selected');
      console.log('===========>', this.selectedPlan);
      this.selectedTab = 'Terms';
    }
  }


  totalPrice: any = 20;

  getTotalPrice() {
    if (this.checkoutPlans) {
      this.totalPrice = 0;
      const amount10 = +this.checkoutPlans.plandetails.tiers[0].unit_amount / 100;
      const amount18 = +this.checkoutPlans.plandetails.tiers[1].unit_amount / 100;
      const amount16 = +this.checkoutPlans.plandetails.tiers[2].unit_amount / 100;

      if (this.device_per_unit <= 10) {
        this.devicePlanAmount = amount10
        this.totalPrice = amount10 * this.device_per_unit;
      } else if (this.device_per_unit <= 15) {
        this.devicePlanAmount = amount18
        this.totalPrice = amount18 * this.device_per_unit;

      } else {
        this.devicePlanAmount = amount16
        this.totalPrice = amount16 * this.device_per_unit;

      }
      this.discountCode();
    }
  }

  onCouponInputChange(event: any) {
    this.searchCoupnTerm.next(event.target.value);
  }

  validateCoupon() {
    let body = {
      "message": "Coupon is valid! & 15% for (1 months)",
      "amountoff": 0,
      "percentoff": 15
    }

    // if (this.paymentFormDetails.valid) {
    const coupon = this.paymentFormDetails.value.couponCode;
    this.crud.postRequest('validate-coupon', { coupon }).subscribe(
      {
        next: (data: any) => {
          this.couponValidationResult = data;
          console.log('Coupon validation data:', data);
          this.ts.success(data.message);
          this.couponDataDetails = body;
          if (this.couponDataDetails) {
            this.discountCode();
          }
        },
        error: (err: any) => {
          console.error('Error validating coupon:', err);
          this.ts.error(err.error.message.coupon[0]);

        }
      }
    );
    // }
  }

  // {
  //   "message": "Coupon is valid! & 15% for (1 months)",
  //   "amountoff": 0,
  //   "percentoff": 15
  // }

  discountCode() {
    if (this.couponDataDetails) {
      if (this.couponDataDetails?.amount) {
        this.totalPrice = this.totalPrice - this.couponDataDetails?.amount;
      } else if (this.couponDataDetails?.percentoff) {
        this.totalPrice = this.totalPrice - this.couponDataDetails?.percentoff / 100
      }
    }
  }

  // {
    //     "name": "AnnuStripeTest1",
    //     "email": "annu.sehrawat+AnnuStripeTest1@oodles.io",
    //     "password": "12345678",
    //     "stripeToken": "tok_visa",
    //     "trial_days": "1",
    //     "plan_id": "FJZCNhouM8Nr",
    //     "quantity": "1",
    //     "line1": "H. No. 265",
    //     "line2": "abc",
    //     "city": "Chesterfield",
    //     "state": "Massachusetts",
    //     "country": "United States",
    //     "card_brand": "visa",
    //     "card_last_four": "4242",
    //     "zip_code": "01012"
    // }

  //   nameOnCard: ['', Validators.required],
  //   cardNumber: ['', Validators.required],
  //   cardZip: ['', Validators.required],
  //   cardEmail: ['', [Validators.required, Validators.email]],
  //   couponCode: [''],
  //   address1: ['', Validators.required],
  //   address2: [''],
  //   paymentCity: ['', Validators.required],
  //   paymentState: ['', Validators.required],
  //   country: ['', Validators.required]
  // });

  submitPaymentDetails(token:any) {
    const formData = this.paymentFormDetails.value;
    let body = {
          "name": this.paymentFormDetails.value.nameOnCard,
          "email": this.paymentFormDetails.value.cardEmail,
          "stripeToken": token.id,
          "trial_days": '7',
          "plan_id": this.selectedPlan.id,
          "quantity": '1',
          "line1": this.paymentFormDetails.value.address1,
          "line2": this.paymentFormDetails.value.address2,
          "city": this.paymentFormDetails.value.paymentCity,
          "state": this.paymentFormDetails.value.paymentState,
          "country": this.paymentFormDetails.value.country,
          "card_brand": token.card.brand,
          "card_last_four": token.card.last4,
          "zip_code": this.paymentFormDetails.value.cardZip
      }

      this.crud.postRequest('auth/register',body).subscribe({
        next:(res:any)=>{console.log('resdata ======> ',res);this.ts.success(res.message);
        this.selectedTab = 'Confirmation'
      },
        error:(err:any)=>{console.log('errdata ======> ',err);this.ts.success(err.message)}
      })
    console.log('==Body======>',body);
  }


  paymentShowData(){
    const formData = this.paymentFormDetails.value;
    console.log('========paymentData==========>',formData);
  }







  // payment code by ashish

  stripe:any;
  elements:any;

  ngOnDestroy() {
    if (this.card) {
      this.card.removeEventListener('change', this.cardHandler);
      this.card.destroy();
    }
  }
  ngAfterViewInit() {
    // this.initiateCardElement();
  }

  async initializeKey(key:any){
    this.stripe = await Stripe(key);
    this.elements = this.stripe.elements();
    if(this.stripe){
      this.initiateCardElement();
    }
  }

  initiateCardElement() {
    this.selectedTab = 'Payment';

    const cards = {
      hidePostalCode: true,
      style: {
       base: {
        iconColor: '#666EE8',
        color: '#31325F',
        lineHeight: '40px',
        fontWeight: 300,
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSize: '15px',
      
        '::placeholder': {
          color: '#CFD7E0',
         },
        }, 
       } 
      }

    if (this.selectedTab == 'Payment') {
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
        this.card = this.elements.create('card',  cards ,);
        console.log(this.card);
        this.card.mount(this.cardInfo.nativeElement);
        this.card.addEventListener('change', this.cardHandler);
      }, 4000);
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

  async createStripeToken() {
    console.log(this.card, 'card');
    const { token, error } = await this.stripe.createToken(this.card);
    console.log(token, 'token');  

    this.submitPaymentDetails(token);
    if (token) {
      this.onSuccess(token);
    } else {
      this.onError(error);
    }
  }
  onSuccess(token: any) {
  }
  onError(error: any) {
    if (error.message) {
      this.cardError = error.message;
    }
  }





//   curl --location 'http://127.0.0.1:8000api/auth/register' \
// --data-raw '{
//     "name": "AnnuStripeTest1",
//     "email": "annu.sehrawat+AnnuStripeTest1@oodles.io",
//     "password": "12345678",
//     "stripeToken": "tok_visa",
//     "trial_days": "1",
//     "plan_id": "FJZCNhouM8Nr",
//     "quantity": "1",
//     "line1": "H. No. 265",
//     "line2": "abc",
//     "city": "Chesterfield",
//     "state": "Massachusetts",
//     "country": "United States",
//     "card_brand": "visa",
//     "card_last_four": "4242",
//     "zip_code": "01012"
// }'










}
