import { NgClass } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { CrudService } from '../services/crud.service';

@Component({
  selector: 'app-reset-password',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass, RouterLink],
  templateUrl: './reset-password.component.html',
  styleUrl: './reset-password.component.scss'
})
export class ResetPasswordComponent {
  showPassword1: boolean = false;
  showPassword2: boolean = false;
  resetPasswordForm!: FormGroup;
  token: any = this.ar.snapshot.paramMap.get('token');
  email: any =this.ar.queryParams;
  constructor(private fb: FormBuilder, private ar: ActivatedRoute,private crud:CrudService) {
    this.resetPasswordForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password1: ['', [Validators.required]],
      password2: ['', [Validators.required]],
    },{ validator: this.passwordMatchValidator })
    
  }
  ngOnInit(){
    this.resetPasswordForm.controls['email'].setValue(this.email['_value'].email);
  }

  passwordMatchValidator(formGroup: FormGroup){
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
    this.crud.postRequest('password/reset',data).subscribe((res)=>console.log(res));
  }

}
