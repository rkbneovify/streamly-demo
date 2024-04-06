import { NgClass, NgIf } from '@angular/common';
import { HttpHeaders } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { Router } from 'express';
import { ToastrService } from 'ngx-toastr';
import { CrudService } from '../../services/crud.service';

@Component({
  selector: 'app-accountsettings',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass, NgIf],
  templateUrl: './accountsettings.component.html',
  styleUrl: './accountsettings.component.scss',
})
export class AccountsettingsComponent implements OnInit {
  updateuserDetatils!: FormGroup;
  editMode: boolean = false;
  showPassword: boolean = false;
  usersdetail: any;
  deviceInfo: any;
  access_Token: any;
  updated_data: any;

  constructor(
    private fb: FormBuilder,
    private crud: CrudService,
    private ts: ToastrService
  ) {
    this.updateuserDetatils = this.fb.group({
      fullname: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      oldpassword: ['', Validators.required],
      newpassword: ['', Validators.required],
      zipcode: ['', Validators.required],
    });
  }

  ngOnInit(): void {
    const jsonData = localStorage.getItem('userDetails');
    if (jsonData !== null) {
      try {
        this.usersdetail = JSON.parse(
          localStorage.getItem('userDetails') || ''
        );
      } catch (error) {
        console.error('Error parsing JSON data:', error);
      }
    }
    const deviceInfoData = localStorage.getItem('deviceInfo');
    if (deviceInfoData !== null) {
      try {
        this.deviceInfo = JSON.parse(localStorage.getItem('deviceInfo') || '');
      } catch (error) {
        console.error('Error parsing JSON data:', error);
      }
    }
    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
    this.updateuserDetatils.controls['email'].setValue(this.usersdetail?.email);
    this.updateuserDetatils.controls['zipcode'].setValue(
      this.usersdetail?.zipcode
    );
  }

  toggleEditMode() {
    this.editMode = !this.editMode;
    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
  }

  saveChanges() {
    // saving changes here
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
        this.access_Token = JSON.parse(
          localStorage.getItem('deviceInfo') || ''
        );
        const tokenData = this.access_Token?.access_token;
        const headers = new HttpHeaders({
          Authorization: `Bearer ${tokenData}`,
          Accept: 'application/json',
        });
        console.log('token', headers);

        // if (this.updated_data) {
        let body = {
          name: this.updateuserDetatils.value.fullname,
          old_password: this.updateuserDetatils.value.oldpassword,
          password: this.updateuserDetatils.value.newpassword,
        };
        console.log('updated values -->>>', body);
        this.crud
          .postRequest('auth/account-edit', body, headers)
          .subscribe({
            next:(res: any) => {
              console.log('->>', res);
              if(res.response_code == 200){
                            // Update name in local storage
              this.usersdetail.name = body.name;
              console.log(this.usersdetail);
              localStorage.setItem(
                'userDetails',
                JSON.stringify(this.usersdetail)
                );
                
                this.ts.success(res.message);
                this.toggleEditMode();
              }else{
                console.log(res,'123123123');
                this.ts.error(res.message);
              }
  
            },
            error:(err:any)=>{
              console.log(err,'error');
                this.ts.error(err.error.message);
            }
          });
        // }
      } catch (error) {
        console.error('Error parsing JSON data:--->>>', error);
      }
    }

    console.log(this.updateuserDetatils.value);
    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
    this.updateuserDetatils.controls['oldpassword'].setValue('');
    this.updateuserDetatils.controls['newpassword'].setValue('');
    
  }
}
