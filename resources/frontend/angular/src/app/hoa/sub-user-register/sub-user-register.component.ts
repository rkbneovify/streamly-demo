import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CommonModule, NgClass, NgIf } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { AuthService } from '../../services/authService/authservice.service';
import { NgSelectModule } from '@ng-select/ng-select';



@Component({
  selector: 'app-login',
  standalone: true,
  imports: [NgIf, ReactiveFormsModule, NgClass, RouterLink, CommonModule, NgSelectModule],
  templateUrl: './sub-user-register.component.html',
  styleUrl: './sub-user-register.component.css',
})
export class SubUserRegisterComponent implements OnInit {
  registerForm: FormGroup;
  loading = false;
  zipcodeLoading = false;
  items: any[] = [];




  constructor(
    private fb: FormBuilder,
    private crudService: CrudService,
    private router: Router,
    private toastr: ToastrService,
    private authService: AuthService,
    private activeRoute: ActivatedRoute
  ) {

  }

  ngOnInit(): void {
    this.initializeForms();
  }

  zipcodeOnSearch(term: any) {

    const query: string = term.term;
    if (!query || query.length < 2) {
      this.items = [];
      return;
    }
    this.zipcodeLoading = !this.zipcodeLoading;

    const data = {
      search: query
    }
    this.crudService.postRequestWithDetails("/api/auth/getzipcodes", data).subscribe({
      next: (res: any) => {
        this.zipcodeLoading = !this.zipcodeLoading;
        console.log("respons", res)
        this.items = [...res.body];

      },
      error: (err: any) => {
        console.log("respons", err)
        this.zipcodeLoading = !this.zipcodeLoading;
        this.toastr.error(err.error.message)
      }
    });
  }
  zipcodeOnSelected(event: any) {
    console.log("selected value:", event);
  }
  private initializeForms() {


    this.registerForm = this.fb.group({
      house_no: [
        '',
        [
          Validators.required,
          Validators.pattern('^[0-9A-Za-z\/\-]+$')
        ],
      ],
      name: [
        '',
        [
          Validators.required,
          Validators.pattern(/^[A-Za-z ]+$/)
        ],
      ],
      phone: [
        '',
        [
          Validators.required,
          Validators.pattern(/^[0-9]{10}$/)
        ],
      ],

      zipcode: [null, [Validators.required, Validators.pattern(/^[0-9]{5}$/)]]



    });




  }

  onSubmit() {
    const subUserId = this.activeRoute.snapshot.paramMap.get('id');
    const hash = this.activeRoute.snapshot.paramMap.get('hash');
    const amount = this.activeRoute.snapshot.queryParamMap.get('amount');
    const expires = this.activeRoute.snapshot.queryParamMap.get('expires');
    const months = this.activeRoute.snapshot.queryParamMap.get('plan_in_month');

    this.registerForm.markAllAsTouched();

    // console.log("validation :", this.registerForm.valid);
    // console.log("values :", this.registerForm.value);
    if (this.registerForm.valid) {
      this.loading = true;
      const data = {
        sub_user_id: subUserId,
        house_no: this.registerForm.value.house_no,
        name: this.registerForm.value.name,
        phone: this.registerForm.value.phone,
        plan_in_month: months,
        amount: this.registerForm.value.amount,
        zipcode: this.registerForm.value.zipcode
      };
      this.crudService.postRequestWithDetails("/api/hoa/sub-user/account-active", data).subscribe({
        next: (res) => {
          this.loading = !this.loading;
          console.log('res:', res)
          this.toastr.info(res.body.message);
          this.router.navigate(['auth/login'], {
            queryParams: { tab: 'passcode' }
          });
        },
        error: (err: any) => {
          this.loading = !this.loading;
          console.log('err:', err)
          if (err.status == 422) {
            this.handleValidationErrors(err.error.errors);
            this.toastr.error(err.error.message)
          }
          this.toastr.error(err.error.message)
        }

      })
    }
  }

  handleValidationErrors(errors: any) {
    Object.keys(errors).forEach(field => {
      const control = this.registerForm.get(field);
      if (control) {
        control.setErrors({ server: errors[field][0] });
      }
    });
  }



































}
