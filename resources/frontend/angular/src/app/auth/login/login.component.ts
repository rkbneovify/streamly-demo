import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { NgIf } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CrudService } from '../../services/crud.service';


@Component({
  selector: 'app-login',
  standalone: true,
  imports: [RouterLink,NgIf,ReactiveFormsModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss',
})
export class LoginComponent implements OnInit{
  loginFormWithEmail!: FormGroup;
  selectedTab: string = 'loginwithpassword';
  userDeviceID!:string;
  constructor(private fb: FormBuilder, private crudService: CrudService) {
    this.loginFormWithEmail = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
    });
  }

  ngOnInit(): void {
    this.generateDeviceId();
  }

  onSubmit() {
    if (this.loginFormWithEmail.valid) {
      const formData = this.loginFormWithEmail.value;
      formData.deviceid =  this.userDeviceID;// Call the function to generate Device ID
      console.log(this.generateDeviceId());
      console.log(formData);
      this.crudService.postRequest('auth/login', formData).subscribe({
        next:(response) => {
          console.log('login-->',response);
        },
        error:(err) => {
          console.log('err-->',err);
        },
      }
      );
    }
  }

  private generateDeviceId() {
    this.crudService.getRequest('auth/devicedetail').subscribe({
      next:(res:any)=>this.userDeviceID=res?.deviceid,
      error:err=>console.log(err)
    });
  }
}
