import { NgClass } from '@angular/common';
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
  imports: [ReactiveFormsModule, NgClass, RouterLink],
  templateUrl: './changepassword.component.html',
  styleUrl: './changepassword.component.scss',
})
export class ChangepasswordComponent {
  showPassword1: boolean = false;
  showPassword2: boolean = false;
  showPassword3: boolean = false;

  resetPasswordForm!: FormGroup;
  token: any = this.ar.snapshot.paramMap.get('token');
  constructor(
    private fb: FormBuilder,
    private ar: ActivatedRoute,
    private crud: CrudService,
    private router: Router,
    private toastr: ToastrService
  ) {
    this.resetPasswordForm = this.fb.group(
      {
        email: ['', [Validators.required, Validators.email]],
        oldPassword: ['', [Validators.required]],
        password1: ['', [Validators.required]],
        password2: ['', [Validators.required]],
      },
      { validator: this.passwordMatchValidator }
    );
  }

  passwordMatchValidator(formGroup: FormGroup) {
    const passwordControl = formGroup.get('password1');
    const confirmPasswordControl = formGroup.get('password2');

    if (
      passwordControl &&
      confirmPasswordControl &&
      passwordControl.value !== confirmPasswordControl.value
    ) {
      confirmPasswordControl?.setErrors({ passwordMismatch: true });
      this.toastr.error('Password not matching');
    } else {
      confirmPasswordControl?.setErrors(null);
    }
  }

  resetPassword_Link() {
    console.log('Email -->', this.resetPasswordForm.get('email')?.value);
    console.log('Email -->', this.resetPasswordForm.value.email);
    console.log('Token -->', this.token);
    let data = {
      email:this.resetPasswordForm.get('email')?.value,
      old_password: this.resetPasswordForm.get('oldPassword')?.value,
      password: this.resetPasswordForm.get('password1')?.value,
      password_confirmation: this.resetPasswordForm.get('password2')?.value,
    };

    const headers = new HttpHeaders({
      'Authorization': `Bearer ${this.token}`,
      'Accept': 'application/json'
    });

    console.log(this.token);

    this.crud.postRequest('auth/force-change-password', data,headers).subscribe({
      next: (res: any) => {
        this.toastr.success(res.message);
        this.router.navigateByUrl('/auth/login');
      },
      error: (err) => {
        this.toastr.error(err.error.message);
      },
    });
  }
}
