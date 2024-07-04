import { Component, NgModule, OnInit } from '@angular/core';
import { CrudService } from '../../../services/crud.service';
import { Location, NgClass, NgFor, NgIf } from '@angular/common';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-report-form',
  standalone: true,
  imports: [NgFor, NgIf, FormsModule, ReactiveFormsModule, NgClass],
  templateUrl: './report-form.component.html',
  styleUrl: './report-form.component.scss',
})
export class ReportFormComponent implements OnInit {
  privacyPolicyData: any;
  title: string;
  reportForm: FormGroup;
  bugReportService: any;
  constructor(
    private location: Location,
    private apiService: CrudService,
    private fb: FormBuilder,
    private ts: ToastrService
  ) {
    this.reportForm = this.fb.group({
      email_input: ['', [Validators.required, Validators.email]],
      bug_severity: ['', Validators.required],
      occurrence: ['', Validators.required],
      platform_info: ['', Validators.required],
      details: ['', Validators.required],
    });
  }
  goBack(): void {
    this.location.back();
  }

  ngOnInit(): void {
    this.getPrivacyP();

    // this.bugForm = this.fb.group({
    //   email:[''],

    // })
  }

  getPrivacyP() {
    this.apiService.getRequest('auth/privacypol').subscribe({
      next: (res: any) => {
        const data = res?.data.split('\n');
        this.privacyPolicyData = data.slice(1);
        this.title = data[0];
        console.log(res);
      },
      error: (err: any) => {
        console.log(err);
      },
    });
  }

  onSubmit(): void {
    if (this.reportForm.valid) {
      this.apiService
        .postRequest('auth/bugReport', this.reportForm.value)
        .subscribe(
          (response:any) => {
            console.log('Bug report sent successfully:', response);
      this.ts.success(response?.message)
            // Handle success response
          },
          (error) => {
            console.error('Error sending bug report:', error);
            this.ts.error(error.message);

            // Handle error response
          }
        );
    } else {
      // console.log('Form is invalid');
      this.ts.error('Form is invalid');
    }
  }
}
