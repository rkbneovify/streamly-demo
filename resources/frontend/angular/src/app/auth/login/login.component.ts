import { Component, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { NgClass, NgIf } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CrudService } from '../../services/crud.service';


@Component({
  selector: 'app-login',
  standalone: true,
  imports: [RouterLink,NgIf,ReactiveFormsModule,NgClass],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss',
})
export class LoginComponent implements OnInit{
  loginFormWithEmail!: FormGroup;
  selectedTab: string = 'loginwithpassword';
  showPassword: boolean = false;
  showPasscode:boolean = false;
  userDeviceID!:string;
  userToken!:string;
  constructor(private fb: FormBuilder, private crudService: CrudService,private router:Router) {
    this.loginFormWithEmail = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
    });
  }

  ngOnInit(): void {
    // Check if device ID is present in local storage
    const storedDeviceId = localStorage.getItem('deviceId');
    console.log('storedDeviceId--->>',storedDeviceId);
    if (storedDeviceId) {
      console.log(storedDeviceId);
      this.userDeviceID = storedDeviceId;
      const tokenId = localStorage.getItem('access_token');
      if(tokenId){
        this.router.navigate(['/']);
      }
    } else {
      this.generateDeviceId();
      
    }
  }


  onSubmit() {
    if (this.loginFormWithEmail.valid) {
      const formData = this.loginFormWithEmail.value;
      formData.deviceid =  this.userDeviceID;// Call the function to generate Device ID
      console.log(formData);
      this.crudService.postRequest('auth/login', formData).subscribe({
        next:(res:any)=>{
          if (res.status === 200) {
                // this.router.navigate(['/']);
                console.log(res);
              }     
        },
        error:(err:any)=>{
          if (err.status == 479) {
            this.crudService.sendResponse(err);
                this.router.navigate(['auth/remove-device']);
                console.log('479-->>',err);
              } else {
                console.log('error',err);
                ;
              }
        }
      });
    }
  }

  private generateDeviceId() {
    this.crudService.getRequest('auth/devicedetail').subscribe({
      next:(res:any)=>{console.log('hey');this.userDeviceID=res?.deviceid;localStorage.setItem('deviceId', this.userDeviceID);},
      error:err=>console.log(err)
    });
  }


}
