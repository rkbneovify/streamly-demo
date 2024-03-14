import { NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-forget-password',
  standalone: true,
  imports: [NgIf,RouterLink,ReactiveFormsModule,NgClass],
  templateUrl: './forget-password.component.html',
  styleUrl: './forget-password.component.scss'
})
export class ForgetPasswordComponent {
selectTab:string='ForgetPassword';
showPassword1:boolean=false;
showPassword2:boolean=false;
forgetPasswordEmailForm!:FormGroup;

constructor(private fb:FormBuilder){
  this.forgetPasswordEmailForm = this.fb.group({
    emailFP: ['', [Validators.required, Validators.email]],
  })
}


resetPassword_Link(){
  console.log('Email -->',this.forgetPasswordEmailForm.get('emailFP')?.value,'===== this is form value-->',this.forgetPasswordEmailForm);
}

}
