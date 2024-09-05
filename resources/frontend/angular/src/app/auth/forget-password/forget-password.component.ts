import { NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-forget-password',
  standalone: true,
  imports: [NgIf,RouterLink,ReactiveFormsModule,NgClass,],
  templateUrl: './forget-password.component.html',
  styleUrl: './forget-password.component.scss'
})
export class ForgetPasswordComponent {
selectTab:string='ForgetPassword';
showPassword1:boolean=false;
showPassword2:boolean=false;
forgetPasswordEmailForm!:FormGroup;
loading:boolean = false;

constructor(private fb:FormBuilder,private crudService:CrudService, private toastr: ToastrService, private router: Router){
  this.forgetPasswordEmailForm = this.fb.group({
    email: ['', [Validators.required, Validators.email]],
  })
}

forgetPasswordmail(usermail:any){
  this.loading = true;
  this.crudService.postRequest('password/email',usermail).subscribe({
    next:(res:any)=>{this.toastr.success(res.message)
      this.router.navigateByUrl('/auth/login');
      this.loading = false;    
    },
    error:(err)=>{this.toastr.error(err.error.message)
      this.loading = false;
    },
  });
}



forgetPassword_Link(){
  this.forgetPasswordmail(this.forgetPasswordEmailForm.value);
}

}
