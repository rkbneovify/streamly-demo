import { NgClass } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-reset-password',
  standalone: true,
  imports: [ReactiveFormsModule,NgClass,RouterLink],
  templateUrl: './reset-password.component.html',
  styleUrl: './reset-password.component.scss'
})
export class ResetPasswordComponent {
showPassword1:boolean=false;
showPassword2:boolean=false;
resetPasswordForm!:FormGroup;

constructor(private fb:FormBuilder){
  this.resetPasswordForm = this.fb.group({
    email: ['demo@gmail.com', [Validators.required, Validators.email]],
    password1:['',[Validators.required]],
    password2:['',[Validators.required]],
  })
}


resetPassword_Link(){
  console.log('Email -->',this.resetPasswordForm.get('email')?.value,'===== this is form value-->',this.resetPasswordForm);
}

}
