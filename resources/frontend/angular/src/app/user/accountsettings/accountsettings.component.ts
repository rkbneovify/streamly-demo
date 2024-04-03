import { NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';

@Component({
selector: 'app-accountsettings',
standalone: true,
imports: [ReactiveFormsModule,NgClass,NgIf],
templateUrl: './accountsettings.component.html',
styleUrl: './accountsettings.component.scss'
})
export class AccountsettingsComponent {

updateuserDetatils!:FormGroup;
editMode: boolean = false;
showPassword: boolean = false;
constructor(private fb:FormBuilder){
this.updateuserDetatils = this.fb.group({
fullname:['Deepak Yadav',Validators.required],
email: ['deepak1234gmail.com', [Validators.required, Validators.email]],
password:['1111111',Validators.required],
zipcode:['0000001',Validators.required]
})
}

toggleEditMode() {
this.editMode = !this.editMode;
}

saveChanges() {
// Implement saving changes here
this.toggleEditMode(); // Optionally, you can toggle the edit mode off after saving
}

}