import { NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';

@Component({
    selector: 'app-accountsettings',
    standalone: true,
    imports: [ReactiveFormsModule, NgClass, NgIf],
    templateUrl: './accountsettings.component.html',
    styleUrl: './accountsettings.component.scss'
})
export class AccountsettingsComponent {

    updateuserDetatils!: FormGroup;
    editMode: boolean = false;
    showPassword: boolean = false;
    usersdetail: any;
    deviceInfo: any;

    constructor(private fb: FormBuilder) {
        this.updateuserDetatils = this.fb.group({
            fullname: ['', Validators.required],
            email: ['', [Validators.required, Validators.email]],
            oldPassword:['',Validators.required],
            password: ['', Validators.required],
            zipcode: ['', Validators.required]
        })
        const jsonData = localStorage.getItem('userDetails');   
        if (jsonData !== null) {
            try {
                this.usersdetail = JSON.parse(localStorage.getItem('userDetails') || '');
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
        this.updateuserDetatils.controls['fullname'].setValue(this.usersdetail?.name);
        this.updateuserDetatils.controls['email'].setValue(this.usersdetail?.email);
        this.updateuserDetatils.controls['zipcode'].setValue(this.usersdetail?.zipcode);
    }

    toggleEditMode() {
        this.editMode = !this.editMode;
        this.updateuserDetatils.controls['fullname'].setValue(this.usersdetail?.name);
        this.updateuserDetatils.controls['email'].setValue(this.usersdetail?.email);
    }

    

    saveChanges() {
        // saving changes here
        console.log(this.updateuserDetatils);
        this.updateuserDetatils.controls['fullname'].setValue(this.usersdetail?.name);
        this.updateuserDetatils.controls['email'].setValue(this.usersdetail?.email);
        this.toggleEditMode(); 
    }

}