import { NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-changepassword',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass, RouterLink, NgIf],
  templateUrl: './changepassword.component.html',
  styleUrl: './changepassword.component.scss',
})
export class ChangepasswordComponent {
  showPassword1: boolean = false;
  showPassword2: boolean = false;
  showPassword3: boolean = false;
  loading: boolean = false;

  resetPasswordForm!: FormGroup;
  token: any = localStorage.getItem('changeToken');
  constructor(
    private fb: FormBuilder,
    private ar: ActivatedRoute,
    private crud: CrudService,
    private router: Router,
    private toastr: ToastrService
  ) {
    this.resetPasswordForm = this.fb.group(
      {
        oldPassword: ['', [Validators.required]],
        password1: ['', [Validators.required, Validators.minLength(6)]],
        password2: ['', [Validators.required, Validators.minLength(6)]],
      },
      { validator: this.passwordMatchValidator }
    );
  }

  passwordMatchValidator(formGroup: FormGroup) {
    const passwordControl = formGroup.get('password1');
    const confirmPasswordControl = formGroup.get('password2');

    if (passwordControl && confirmPasswordControl && passwordControl.value !== confirmPasswordControl.value) {
      confirmPasswordControl?.setErrors({ passwordMismatch: true });
    } else {
      confirmPasswordControl?.setErrors(null);
    }
  }

  resetPassword_Link() {
    this.loading = true;
    let data = {
      email: this.resetPasswordForm.get('email')?.value,
      old_password: this.resetPasswordForm.get('oldPassword')?.value,
      password: this.resetPasswordForm.get('password1')?.value,
      password_confirmation: this.resetPasswordForm.get('password2')?.value,
    };

    if (this.token) {
      const headers = new HttpHeaders({
        Authorization: `Bearer ${this.token}`,
        Accept: 'application/json',
      });
      this.crud
        .postRequest('auth/force-change-password', data, headers)
        .subscribe({
          next: (res: any) => {
            this.toastr.success(res.message);
            localStorage.removeItem('deviceId');
            localStorage.removeItem('deviceInfo');
            localStorage.removeItem('userDetails');
            localStorage.removeItem('changeToken');
            this.loading = false;
            this.router.navigateByUrl('/auth/login');
          },
          error: (err) => {
            this.loading = false;

            this.toastr.error(err.error.message);
          },
        });
    } else {
      this.loading = false;
    }
  }
}
