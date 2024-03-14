import { NgClass } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, RouterLink } from '@angular/router';

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
  constructor(private fb: FormBuilder, private ar: ActivatedRoute) {
    this.resetPasswordForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password1: ['', [Validators.required]],
      password2: ['', [Validators.required]],
    })
    
  }
  ngOnInit(){
    this.resetPasswordForm.controls['email'].setValue(this.email['_value'].email);
  }


  resetPassword_Link() {
    console.log('Email -->', this.resetPasswordForm.get('email')?.value);
    console.log('Email -->', this.resetPasswordForm.value.email);
    console.log('Token -->', this.token);
  }

}
