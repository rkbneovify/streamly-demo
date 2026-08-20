import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Location, NgIf } from '@angular/common';
import { CrudService } from '../../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-contact-us',
  standalone: true,
  imports: [ReactiveFormsModule,NgIf],
  templateUrl: './contact-us.component-old.html',
  styleUrl: './contact-us.component-old.scss'
})
export class ContactUsComponentOld {
  contactForm: FormGroup;
  showbackbtn:boolean = true;

  constructor(private location: Location, private crud: CrudService, private fb: FormBuilder,private toast:ToastrService) {
    this.contactForm = this.fb.group({
      name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      message: ['', Validators.required]
    });

    if(localStorage.getItem('token')){
      this.showbackbtn = false;
    }else{
      this.showbackbtn = true
    }
  }

  goBack(): void {
    this.location.back();
  }

  onSubmit() {
    if (this.contactForm.valid) {
      const formValue = this.contactForm.value;
      this.crud.postRequest('auth/contactForm', formValue,this.getAuthHeaders()).subscribe({
        next: (response:any) => {
          this.toast.success(response?.message);
          this.contactForm.reset()
        },
        error: (error) => {
          console.error('Error submitting form', error);
          this.toast.error(error.error)
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
