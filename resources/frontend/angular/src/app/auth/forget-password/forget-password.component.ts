import { NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';

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

constructor(private fb:FormBuilder,private crudService:CrudService){
  this.forgetPasswordEmailForm = this.fb.group({
    email: ['', [Validators.required, Validators.email]],
  })
}

forgetPasswordmail(usermail:any){
  this.crudService.postRequest('password/email',usermail).subscribe({
    next:(res)=>{console.log("data -->",res)},
    error:(err)=>console.log("error -->",err),
  });
}



forgetPassword_Link(){
  this.forgetPasswordmail(this.forgetPasswordEmailForm.value);
  console.log('Email -->',this.forgetPasswordEmailForm.value,'===== this is form value-->',this.forgetPasswordEmailForm);
}

}
