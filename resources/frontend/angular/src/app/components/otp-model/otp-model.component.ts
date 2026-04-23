import { Component, ElementRef, EventEmitter, Input, OnDestroy, OnInit, Output, QueryList, ViewChildren } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { SweetalertService } from '../../services/SweetAlert/sweetalert.service';
import { finalize } from 'rxjs';
import { NgClass, NgFor, NgIf } from '@angular/common';





@Component({
  selector: 'app-otp-model',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass, NgIf, NgFor],
  templateUrl: './otp-model.component.html',
  styleUrl: './otp-model.component.css'
})



export class OtpModelComponent implements OnInit, OnDestroy {

  otpArray = [0, 1, 2, 3, 4, 5];
  otp: string = '';
  timer: number = 60;
  interval: any;
  canResend: boolean = false;
  isVerifying: boolean = false;
  isOtpError: boolean = false;
  isLoading: boolean = false;

  @Input() action!: string;
  @Input() displayText!: string;
  @Output() onOtpVerified = new EventEmitter<{}>();

  constructor(
    private fb: FormBuilder,
    private crud: CrudService,
    private ts: ToastrService,
    private router: Router,
    private sweetAlert: SweetalertService
  ) {

  }



  resendOtp() {
    document.getElementById('btn-show-otp-dailog').click();
    this.isLoading = true;
    const data = {
      action: this.action
    };
    this.crud.postRequest('auth/account/otp-send', data).pipe(finalize(() => {
      this.isLoading = false;
    })).subscribe({
      next: (res: any) => {
        this.clearOtpDetails();
        this.startTimer();
        this.ts.success(res.message)
        this.isOtpError = false;
      },
      error: (err: any) => {
        this.ts.error(err.error.message)
        this.isOtpError = true;
      },
    });
  }
  startTimer() {
    this.timer = 60;
    this.canResend = false;

    this.interval = setInterval(() => {
      this.timer--;

      if (this.timer <= 0) {
        this.canResend = true;
        clearInterval(this.interval);
      }
    }, 1000);
  }

  ngOnInit(): void {


  }



  @ViewChildren('otpInput') otpInputs!: QueryList<ElementRef>;

  otpForm = this.fb.group({
    otp: ['', [Validators.required, Validators.maxLength(6)]]
  });




  onOtpInput(event: any, index: number) {
    const input = event.target;
    const value = input.value.replace(/[^0-9]/g, '');

    input.value = value;

    if (value && index < 5) {
      this.otpInputs.toArray()[index + 1].nativeElement.focus();
    }

    this.updateOtp();
  }

  onKeyDown(event: KeyboardEvent, index: number) {
    if (event.key === 'Backspace') {
      const inputs = this.otpInputs.toArray();

      if (!inputs[index].nativeElement.value && index > 0) {
        inputs[index - 1].nativeElement.focus();
      }
    }
  }

  onPaste(event: ClipboardEvent) {
    const pasted = event.clipboardData?.getData('text') || '';

    if (pasted.length === 6) {
      const inputs = this.otpInputs.toArray();

      pasted.split('').forEach((char, i) => {
        inputs[i].nativeElement.value = char;
      });

      this.updateOtp();
      this.otpForm.markAsTouched();
      this.otpForm.markAsDirty();
      this.otpForm.updateValueAndValidity();


    }
  }

  updateOtp() {
    this.otp = this.otpInputs
      .toArray()
      .map(input => input.nativeElement.value)
      .join('');




  }

  verifyOtp(event?: Event) {
    // Use optional chaining to prevent errors if event is null
    event?.preventDefault();

    // It's also good practice to stop propagation
    event?.stopPropagation();

    if (this.otpForm.invalid) {
      this.ts.error("6 digit otp required");
      return;
    }

    const data = {
      otp: this.otp,
      action: this.action
    }

    this.isVerifying = true;
    this.crud.postRequest('auth/account/otp-verify', data).pipe(finalize(() => {
      this.isVerifying = false;
    })).subscribe({
      next: (res: any) => {
        document.getElementById('btn-show-otp-dailog').click();
        this.ts.success(res.message)
        this.onOtpVerified.emit({
          isOtpVeriefied: true
        });
        // if (this.otpSvAction == "delete_account") {
        //   this.deleteUserAccount();
        // } if (this.otpSvAction == "resume_subscription") {
        //   this.subscriptionResume();
        // }


      },
      error: (err: any) => {
        this.ts.error(err.error.message)
        this.onOtpVerified.emit(false);
      },
    });


  }



  clearOtpDetails() {
    this.otp = '';
    this.otpInputs.forEach(input => input.nativeElement.value = '');
    this.clearTimer();
  }



  subscriptionResume() {

    this.sweetAlert.confirm("Subscription", "Do you want to resume subscription ?")
      .then((res) => {
        if (res.isConfirmed) {
          this.isLoading = true;
          this.crud.postRequest('auth/subscription/resume').pipe(finalize(() => {
            this.isLoading = false;
          })).subscribe({
            next: (res: any) => {
              this.ts.success(res.message)
              location.reload();

            },
            error: (err: any) => {
              this.ts.success(err.error.message)

            },
          });
        }

      });


  }



  clearTimer() {
    if (this.interval) {
      clearInterval(this.interval);
    }
  }
  ngOnDestroy(): void {
    this.clearTimer();
    this.clearOtpDetails();
  }

}
