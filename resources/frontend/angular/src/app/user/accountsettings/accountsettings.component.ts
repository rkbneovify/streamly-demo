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
    usersdetail:any = JSON.parse(localStorage.getItem('userDetails') || '');
    deviceInfo:any = JSON.parse(localStorage.getItem('deviceInfo') || '');

    constructor(private fb: FormBuilder) {
        this.updateuserDetatils = this.fb.group({
            fullname: ['', Validators.required],
            email: ['', [Validators.required, Validators.email]],
            password: ['1111111', Validators.required],
            zipcode: ['', Validators.required]
        })
        this.updateuserDetatils.controls['fullname'].setValue(this.usersdetail?.name);
        this.updateuserDetatils.controls['email'].setValue(this.usersdetail?.email);
        this.updateuserDetatils.controls['zipcode'].setValue(this.usersdetail?.zipcode);
    }

    toggleEditMode() {
        this.editMode = !this.editMode;
    }

    saveChanges() {
        // Implement saving changes here
        this.toggleEditMode(); // Optionally, you can toggle the edit mode off after saving
    }

}