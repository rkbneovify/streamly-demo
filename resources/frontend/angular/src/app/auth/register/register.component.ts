import { NgIf, NgFor } from '@angular/common';
import { Component, ElementRef, HostListener, NgModule, OnInit, Renderer2 } from '@angular/core';
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
import { Observable, Subject, debounceTime, distinctUntilChanged, of, switchMap } from 'rxjs';

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
    
  ],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss',
})
export class RegisterComponent implements OnInit {
  selectedTab: string = 'Availability';
  isWidth767: number = window.innerWidth;
  userState: string = '';
  userCity: string = '';
  private searchTerms = new Subject<string>();
  zipcodedata: any = [];
  searchZipInput: string = '';

  // forms
  wrong_zipcodeDetials!: FormGroup;
  availabilityForm!: FormGroup;
  otpVerificationForm!:FormGroup;

  // ======================== constructor ================================
  constructor(private crud: CrudService, private fb: FormBuilder, private el: ElementRef, private renderer: Renderer2) {
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
    })
  }


    // ============================== ngOnIt==========================
    ngOnInit(): void {
      this.searchTerms.pipe(
        debounceTime(300), 
        distinctUntilChanged(), // ignore if next search term is same as previous
        switchMap((term: string) => this.getZipcode(term))
      ).subscribe((apiResponse: any) => {
        this.zipcodedata = [...apiResponse];
        console.log('API Response:', apiResponse);
      });
    }

onSearch(searchTerm: any): void {
  console.log(searchTerm.term);
  this.searchTerms.next(searchTerm.term);
    this.availabilityForm.get('zipcode')?.setValue(searchTerm.term);
    this.searchZipInput =searchTerm.term;
}

onSelect(selectedValue: any): void {
    this.availabilityForm.get('city')?.setValue(selectedValue.city);
    this.availabilityForm.get('state')?.setValue(selectedValue.state);
    this.availabilityForm.get('zipcode')?.setValue(selectedValue.value);
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
    const currentEmail= this.availabilityForm.get('email')?.value;
    console.log('current email-->',currentEmail);
    if(currentEmail){
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
      next: (data) => console.log('data-->', data),
      error: (err) => console.log('error-->', err),
    });
    console.log('this is invalid....');
  }

  // getotp
  getOtpWithMail(verifiMailDetails:any){
    console.log('hello im here',verifiMailDetails);
    this.crud.postRequest('auth/send-otp',{'email':verifiMailDetails}).subscribe({
      next: (data) => console.log('otpdata-->', data),
      error: (err) => console.log('otp-error-->', err),
    });
  }
  verifyOtpWithMail(verifyDetails:any){
    this.crud.postRequest('auth/verify-otp',verifyDetails).subscribe({
      next: (data) => {console.log('otpdata verify-->', data);this.selectedTab='Plans'},
      error: (err) => console.log('otp-error not verify-->', err),
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
    const currentDigit = parseInt(currentControlName.charAt(currentControlName.length - 1));

    if (currentDigit < 6) {
      const nextDigit = currentDigit + 1;
      const nextInput = this.el.nativeElement.querySelector(`#digit${nextDigit}`);
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
    const activeElementId = this.el.nativeElement.ownerDocument.activeElement.id;
    return activeElementId;
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
          console.log(
            'register function',
            'value',
            this.checkZipCodeValid(this.searchZipInput),
            'zipInput',
            this.searchZipInput
          );
          const isZipCodeValid = this.checkZipCodeValid(this.searchZipInput);
          if (isZipCodeValid) {
            console.log(
              'register function',
              'Zip code is valid. Changing selectedTab to "done".'
            );
            // call send otp here
            // mailverification
          console.log('email for getOTP-->',data.email)
          this.getOtpWithMail(data.email);
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
    const data = this.availabilityForm.value;
    this.checkEmailRegistration(data);
    console.log('form Data :', this.availabilityForm.value);
  }

  submitFormWrong() {
    this.getZipCodeInvalid(this.wrong_zipcodeDetials.value);
    console.log('00000000000000000', this.wrong_zipcodeDetials);
    console.log('00000000000000000', this.wrong_zipcodeDetials.value);
  }

  otpVerifySubmit(){
    const otpAndEmail = {
      "email":this.otpVerificationForm.get('email')?.value,
      "otp":this.getOtpValue()
    };
    this.verifyOtpWithMail(otpAndEmail);
  }
}
