import { NgIf, NgFor } from '@angular/common';
import { Component, HostListener, NgModule, OnInit, Renderer2 } from '@angular/core';
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

  zipcodedata: any = [];
  searchZipInput: string = '';

  wrong_zipcodeDetials!: FormGroup;
  availabilityForm!: FormGroup;

  private searchTerms = new Subject<string>();

onSearch(searchTerm: any): void {
  console.log(searchTerm.term);
  this.searchTerms.next(searchTerm.term);
  if(!this.availabilityForm.get('zipcode')?.value){
    this.availabilityForm.get('zipcode')?.setValue(searchTerm.term);
    this.searchZipInput =searchTerm.term
  }
  console.log('zip vlaue on search-->',this.availabilityForm.get('zipcode')?.value)
}
onSelect(selectedValue: any): void {
  if(selectedValue){
    this.availabilityForm.get('city')?.setValue(selectedValue.city);
    this.availabilityForm.get('state')?.setValue(selectedValue.state);
    this.availabilityForm.get('zipcode')?.setValue(selectedValue.value);
    this.searchZipInput = selectedValue.value;
    console.log('Selected value:', selectedValue);
  }
}

  constructor(private crud: CrudService, private fb: FormBuilder) {
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
  }
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

  getOtpWithMail(verifiMailDetails:any){
    this.crud.postRequest('auth/send-otp',verifiMailDetails);
  }

  // check the zipcode is valid or not
  checkZipCodeValid(zipcode: any): boolean {
    const search = zipcode;
    return this.zipcodedata.some((zcode: any) => {
      console.log(zcode.value, '==========================', search);
      return zcode.value === search;
    });
  }

  // getZipcode(data: any) {
  //   this.searchZipInput = data;
  //   const searchData = {
  //     search: data,
  //   };

  //   if (data.length > 0) {
  //     this.crud
  //       .postRequest(`auth/getzipcodes`, searchData)
  //       .subscribe((res: any) => {
  //         console.log('search input is->', data, res, 'get zip code api');
  //         this.zipcodedata = [...res];
  //         console.log('input-->', data, 'data added', this.zipcodedata);

  //         // console.log(this.zipcodedata.length)
  //         // if (this.zipcodedata.length === 0) {
  //         //   this.zipcodedata = [...res];
  //         //   console.log('data added', this.zipcodedata)
  //         // }
  //       });
  //   }else{
  //     this.zipcodedata = [];
  //   }
  // }

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
          this.getOtpWithMail(data.email)
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
}
