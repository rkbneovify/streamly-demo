import { NgClass, NgIf } from '@angular/common';
import { Component, OnInit } from '@angular/core';
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
export class ResetPasswordComponent implements OnInit {
  showPassword1: boolean = false;
  showPassword2: boolean = false;
  loading:boolean = false;
  resetPasswordForm!: FormGroup;
  token: any = this.ar.snapshot.paramMap.get('token');
  email: any;
  isTokenValid:boolean = false;

  constructor(private fb: FormBuilder, private ar: ActivatedRoute,private crud:CrudService, private router: Router,
    private toastr: ToastrService) {
      this.ar.queryParams.subscribe(params => {
        this.email = params['email'];
      });

    this.resetPasswordForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password1: ['', [Validators.required, Validators.minLength(6)]],
      password2: ['', [Validators.required, Validators.minLength(6)]],
    },{ validator: this.passwordMatchValidator })
    
  }
  ngOnInit(){
    this.resetPasswordForm.controls['email'].setValue(this.email);
    console.log('Token ==>',this.token)
    if(this.token && this.email){
      console.log('verify User Token');
      this.veriftyUserToken();
    }
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
    if(this.isTokenValid){
      this.toastr.error('Token is not valid.')
      return;
    }else{
      console.log('Hello it else')
    }
    this.loading = true;
    let data =   {
      "token": this.token,
      "email": this.email,
      "password": this.resetPasswordForm.get('password1')?.value,
      "password_confirmation": this.resetPasswordForm.get('password2')?.value
  }
    this.crud.postRequest('password/reset',data).subscribe({
      next:(res:any)=>{
        this.toastr.success('Your password has been successfully reset. Please log in to continue.')
        // this.toastr.success(res.message)
        this.router.navigateByUrl('/auth/login');
        this.loading = false;
      },
      error:(err)=>{this.toastr.error(err.error.message);this.loading = false;},
    });
  }

   veriftyUserToken(){
    console.log('Token start')
    this.loading = true;
    let data =   {
      "token": this.token,
      "email": this.email,
  }
    this.crud.postRequest('validate-token',data).subscribe({
      next:(res:any)=>{
        // this.toastr.success('Token is ')
        // this.toastr.success(res.message)
        // this.router.navigateByUrl('/auth/login');
        this.loading = false;
      },
      error:(err)=>{
        this.toastr.error(err.error.message);
        console.log('Token res==>',err,err.error.message);
        this.isTokenValid = true;
        this.loading = false;
      },
    });
  }

}
