import { NgClass, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-forgot-passcode',
  standalone: true,
  imports: [NgIf, RouterLink, ReactiveFormsModule, NgClass,],
  templateUrl: './forgot-passcode.component.html',
  styleUrl: './forgot-passcode.component.css'
})
export class ForgotPasscodeComponent {
  selectTab: string = 'ForgetPassword';
  showPassword1: boolean = false;
  showPassword2: boolean = false;
  forgetPasscodeForm!: FormGroup;
  loading: boolean = false;

  constructor(private fb: FormBuilder, private crudService: CrudService, private toastr: ToastrService, private router: Router) {
    this.forgetPasscodeForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
    })
  }

  forgetPasscode(usermail: any) {
    this.loading = true;
    this.crudService.postRequest('hoa/sub-user/forgot-passcode', usermail).subscribe({
      next: (res: any) => {
        this.loading = false;
        this.toastr.success(res.message)
        this.router.navigateByUrl('/auth/login')

      },
      error: (err) => {
        this.toastr.error(err.error.message)
        this.loading = false;
      },
    });
  }



  forgetPasscodeLink() {
    this.forgetPasscode(this.forgetPasscodeForm.value);
  }

  onBtnBack() {
    this.router.navigate(['/auth/login'], { 'queryParams': { 'tab': 'passcode' } })
  }

}

