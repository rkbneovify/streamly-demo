import { NgIf, NgFor, KeyValuePipe } from '@angular/common';
import {
  Component,
  ElementRef,
  HostListener,
  NgModule,
  OnInit,
  Renderer2,
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
export class RegisterComponent implements OnInit {
  selectedTab: string = 'Payment';
  isWidth767: number = window.innerWidth;
  userState: string = '';
  userCity: string = '';
  showplansDetails:boolean = false;
  private searchTerms = new Subject<string>();
  zipcodedata: any = [];
  searchZipInput: string = '';
  city_state_readonly: boolean = false;
  resendButtonDisabled = false;
  countdown = 60;
  all_Plans: any = [];
  checkoutPlans:any;
  device_per_unit:number=1;


  // forms
  counterControl!: FormControl;
  wrong_zipcodeDetials!: FormGroup;
  availabilityForm!: FormGroup;
  otpVerificationForm!: FormGroup;
  selectedPlan:any;
  selectedPlanType:string=''
  acceptTerm: boolean = false;
  // ======================== constructor ================================
  constructor(
    private crud: CrudService,
    private fb: FormBuilder,
    private el: ElementRef,
    private renderer: Renderer2,
    private ts:ToastrService
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
  }

  // ============================== ngOnIt==========================
  ngOnInit(): void {
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

      this.counterControl = this.fb.control(1, [Validators.min(1)]);
  }

  onSearch(searchTerm: any): void {
    console.log(searchTerm.term);
    this.searchTerms.next(searchTerm.term);
    this.availabilityForm.get('zipcode')?.setValue(searchTerm.term);
    this.searchZipInput = searchTerm.term;
    this.city_state_readonly = false;
  }

  onSelect(selectedValue: any): void {
    this.availabilityForm.get('city')?.setValue(selectedValue.city);
    this.availabilityForm.get('state')?.setValue(selectedValue.state);
    this.availabilityForm.get('zipcode')?.setValue(selectedValue.value);
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
            console.log( el.id, 'name', el.amount, 'nikename', el.nickname, el.metadata
            );
          });
        }
      },
      error: (err) => console.log('Plans error --> ', err),
    });
  }

  // selectedplansbyid
  selectedPlanById(){
    if(this.selectedPlan){
      const id = this.selectedPlan.id;

      if(this.selectedPlanType == 'per_unit'){
        this.crud.postRequest('get-basic-plan/'+id,{}).subscribe({
          next:(data:any)=>{
            console.log('===========basic plan>',data);
            this.checkoutPlans = data;
            console.log('checkout================>',this.checkoutPlans)
            this.selectedTab = 'Payment'
          },
          error:(err:any)=>{
            console.log(err);
          }
        });
      }


      if(this.selectedPlanType == 'tiered'){this.crud.postRequest('get-tier-plan/'+id,{}).subscribe({
        next:(data:any)=>{
          console.log('============ tiered plans ==>',data);
          this.checkoutPlans = data;
          this.selectedTab = 'Payment'
        },
        error:(err:any)=>{
          console.log(err);
        }
      });}
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
      next: (data:any) => {this.ts.success(data.success);
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
        next: (data:any) => {console.log('otpdata-->', data);this.ts.success(data.message)},
        error: (err:any) => {this.ts.success(err.message);console.log('otp-error-->', err)},
      });
  }
  verifyOtpWithMail(verifyDetails: any) {
    this.crud.postRequest('auth/verify-otp', verifyDetails).subscribe({
      next: (data:any) => {
        console.log('otpdata verify-->', data);
        this.ts.success(data.message);
        setTimeout(()=>{
          if(data){
            this.getAllThePlans();
            this.selectedTab = 'Plans';
          }
        },1000);
        
      },
      error: (err) => {this.ts.error(err.error.error)
      this.otpVerificationForm.reset()},
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
    const email = this.otpVerificationForm.get('email')?.value;
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
          console.log( 'register function', 'value', this.checkZipCodeValid(this.searchZipInput), 'zipInput', this.searchZipInput );
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

  backbutton(data:any){
    if(data=='Plans'){
      this.selectedTab='Plans'
      // this.getAllThePlans();
    }
    else{
      this.selectedTab='Terms'
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
    if (this.counterControl.value < 100) {
      this.counterControl.setValue(this.counterControl.value + 1);
      this.device_per_unit = Number(this.counterControl.value);
    }
  }

  decrement() {
    if (this.counterControl.value > 1) {
      this.counterControl.setValue(this.counterControl.value - 1);
      this.device_per_unit = Number(this.counterControl.value);
    }
  }


  // 
  selectedTabTerms(data:any){
    if(data){
      this.selectedPlanType = data.type
      this.selectedPlan = data;
      this.ts.success('Plan Selected');
      console.log('===========>',this.selectedPlan);
      this.selectedTab = 'Terms'; 

    }
  }

}
