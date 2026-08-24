import { NgIf } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import {
  FormBuilder,
  FormControl,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators
} from '@angular/forms';
import { RecaptchaComponent, RecaptchaModule } from 'ng-recaptcha';
import { environment } from '../../../../environments/environment';
import { CrudService } from '../../../services/crud.service';
import { SweetalertService } from '../../../services/SweetAlert/sweetalert.service';
import { finalize } from 'rxjs';

@Component({
  selector: 'app-contact-us',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, NgIf, RecaptchaModule],
  templateUrl: './contact-us.component.html',
  styleUrl: './contact-us.component.scss'
})
export class ContactUsComponent implements OnInit {
  myForm: FormGroup;
  siteKey: string = environment.google.captchaKey;
  constructor(
    private fb: FormBuilder,
    private api: CrudService,
    private sweetAlert: SweetalertService
  ) {
    this.myForm = this.fb.group({
      name: ['', [Validators.required, Validators.maxLength(225)]],
      email: ['', [Validators.required, Validators.email]],
      message: ['', [Validators.required]]
    });
  }

  ngOnInit(): void {
    const user = JSON.parse(localStorage.getItem('userDetails') || null);
    if (user != null) {
      this.myForm.patchValue({
        name: user.name,
        email: user.email
      });
    }
  }
  submitForm(captcha: RecaptchaComponent): void {
    if (this.myForm.invalid) {
      this.myForm.markAllAsTouched();
      return;
    }
    captcha.execute();
    // console.log(this.myForm.value);
  }

  resolved(token: any) {
    if (token == null) {
      return;
    }

    this.sweetAlert.loading();

    this.myForm.addControl('captcha_token', new FormControl(''));
    this.myForm.patchValue({
      captcha_token: token
    });
    this.api
      .postRequest('auth/contactForm', this.myForm.value)
      .pipe(
        finalize(() => {
          this.sweetAlert.close();
        })
      )
      .subscribe({
        next: (res: any) => {
          console.log('res:', res);

          this.myForm.reset();
          setTimeout(() => {
            this.sweetAlert.toast(res.message);
          }, 1000);
        },
        error: (err: any) => {
          console.log('error:', err);
          this.myForm.reset();
          setTimeout(() => {
            this.sweetAlert.toast(err.error.message, 'error');
          }, 1000);
        }
      });
  }
}
