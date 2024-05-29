// import { Location } from '@angular/common';
// import { Component } from '@angular/core';
// import { CrudService } from '../services/crud.service';

// @Component({
//   selector: 'app-contact-us',
//   standalone: true,
//   imports: [],
//   templateUrl: './contact-us.component.html',
//   styleUrl: './contact-us.component.scss'
// })
// export class ContactUsComponent {
//   constructor(private location:Location,private crud:CrudService){}
//   goBack(): void {
//     this.location.back();
//   }

//   //auth/contactForm


// contacForm(){
//   this.crud.postRequest('auth/contactForm',  {
//     "name": "test",
//     "email": "annu.sehrawat+test@yopmail.com",
//     "message": "testingggggggggggggg.........."
// }).subscribe({

//   })
// }
// }
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Location } from '@angular/common';
import { CrudService } from '../../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-contact-us',
  standalone: true,
  imports: [ReactiveFormsModule],
  templateUrl: './contact-us.component.html',
  styleUrl: './contact-us.component.scss'
})
export class ContactUsComponent {
  contactForm: FormGroup;

  constructor(private location: Location, private crud: CrudService, private fb: FormBuilder,private toast:ToastrService) {
    this.contactForm = this.fb.group({
      name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      message: ['', Validators.required]
    });
  }

  goBack(): void {
    this.location.back();
  }

  onSubmit() {
    if (this.contactForm.valid) {
      const formValue = this.contactForm.value;
      this.crud.postRequest('auth/contactForm', formValue,this.getAuthHeaders()).subscribe({
        next: (response:any) => {
          console.log('Form submitted successfully', response);
          this.toast.success(response?.message);
        },
        error: (error) => {
          console.error('Error submitting form', error);
        }
      });
    }
  }

  getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(
      localStorage.getItem('deviceInfo') || ''
    ).access_token;
    return new HttpHeaders({
      Authorization: `Bearer ${tokenData}`,
      Accept: 'application/json',
    });
  }
}
