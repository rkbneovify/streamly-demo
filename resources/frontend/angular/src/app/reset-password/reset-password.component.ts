import { NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CrudService } from '../services/crud.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-reset-password',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass, RouterLink,NgIf],
  templateUrl: './reset-password.component.html',
  styleUrl: './reset-password.component.scss'
})
export class ResetPasswordComponent {
  showPassword1: boolean = false;
  showPassword2: boolean = false;
  resetPasswordForm!: FormGroup;
  token: any = this.ar.snapshot.paramMap.get('token');
  email: any =this.ar.queryParams;
  constructor(private fb: FormBuilder, private ar: ActivatedRoute,private crud:CrudService, private router: Router,
    private toastr: ToastrService) {
    this.resetPasswordForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password1: ['', [Validators.required, Validators.minLength(6)]],
      password2: ['', [Validators.required, Validators.minLength(6)]],
    },{ validator: this.passwordMatchValidator })
    
  }
  ngOnInit(){
    this.resetPasswordForm.controls['email'].setValue(this.email['_value'].email);
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
    console.log('Email -->', this.resetPasswordForm.get('email')?.value);
    console.log('Email -->', this.resetPasswordForm.value.email);
    console.log('Token -->', this.token);
    console.log('Query Params -->', this.email);
    let data =   {
      "token": this.token,
      "email": this.email,
      "password": this.resetPasswordForm.get('password1')?.value,
      "password_confirmation": this.resetPasswordForm.get('password2')?.value
  }
    this.crud.postRequest('password/reset',data).subscribe({
      next:(res:any)=>{this.toastr.success(res.message)
        this.router.navigateByUrl('/auth/login');
      },
      error:(err)=>{this.toastr.error(err.error.message)},
    });
  }

}
