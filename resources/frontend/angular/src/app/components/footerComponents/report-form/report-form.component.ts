import { Component, NgModule, OnDestroy, OnInit } from '@angular/core';
import { CrudService } from '../../../services/crud.service';
import { Location, NgClass, NgFor, NgIf } from '@angular/common';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators
} from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { SweetalertService } from '../../../services/SweetAlert/sweetalert.service';
import { RouterLink } from '@angular/router';
import { environment } from '../../../../environments/environment';
import { RecaptchaComponent, RecaptchaModule } from 'ng-recaptcha';

interface PreviewFile {
  file: File;
  preview: string | null;
  isPdf: boolean;
}

@Component({
  selector: 'app-report-form',
  standalone: true,
  imports: [
    NgFor,
    NgIf,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
    RecaptchaModule
  ],
  templateUrl: './report-form.component.html',
  styleUrl: './report-form.component.scss'
})
export class ReportFormComponent implements OnInit, OnDestroy {
  siteKey: string = environment.google.captchaKey;
  bugForm: FormGroup;

  selectedFiles: PreviewFile[] = [];

  isDragging = false;

  readonly maxFileSize = 10 * 1024 * 1024;
  readonly maxFiles = 3;
  readonly allowedTypes = [
    'image/png',
    'image/jpeg',
    'image/jpg',
    'image/heic',
    'image/heif'
  ];

  constructor(private fb: FormBuilder, private sweetAlert: SweetalertService) {}

  ngOnInit(): void {
    this.initForm();
     const user = JSON.parse(localStorage.getItem('userDetails') || null);
    if (user != null) {
      this.bugForm.patchValue({
        name: user.name,
        email: user.email
      });
    }
  }

  initForm() {
    this.bugForm = this.fb.group({
      name: ['', Validators.required],

      email: ['', [Validators.required, Validators.email]],

      bugTitle: ['', Validators.required],

      description: ['', Validators.required],

      platform: ['', Validators.required],

      priority: ['low', Validators.required]
    });
  }
  /**
   * Submit bug report
   */

   resolved(token: any) {
    if (token == null) {
      return;
    }

    const formData = new FormData();

    Object.keys(this.bugForm.controls).forEach(key => {
      const value = this.bugForm.get(key)?.value;

      formData.append(key, value ?? '');
    });

    /**
     * Append files
     */
    this.selectedFiles.forEach(item => {
      formData.append('files[]', item.file);
    });

    console.log('Form:', this.bugForm.value);
    console.log('Files:', this.selectedFiles);

    /**
     * API call example:
     *
     * this.apiService.submitBugReport(formData)
     *   .subscribe({
     *     next: response => {
     *       console.log(response);
     *     },
     *     error: error => {
     *       console.error(error);
     *     }
     *   });
     */
  }

  submitBugReport(captcha: RecaptchaComponent): void {
    if (this.bugForm.invalid) {
      this.bugForm.markAllAsTouched();
      return;
    }


  }

  /**
   * File input change
   */
  onFileSelected(event: Event): void {
    const input = event.target as HTMLInputElement;

    if (!input.files) {
      return;
    }

    const files = Array.from(input.files);

    this.addFiles(files);

    /**
     * Reset input so the same file
     * can be selected again.
     */
    input.value = '';
  }

  /**
   * Drag over
   */
  onDragOver(event: DragEvent): void {
    event.preventDefault();
    event.stopPropagation();

    this.isDragging = true;
  }

  /**
   * Drag leave
   */
  onDragLeave(event: DragEvent): void {
    event.preventDefault();
    event.stopPropagation();

    this.isDragging = false;
  }

  /**
   * Drop files
   */
  onDrop(event: DragEvent): void {
    event.preventDefault();
    event.stopPropagation();

    this.isDragging = false;

    if (!event.dataTransfer?.files) {
      return;
    }

    const files = Array.from(event.dataTransfer.files);

    this.addFiles(files);
  }

  /**
   * Add files
   */
  addFiles(files: File[]): void {
    // Check if already 3 files
    if (files.length > this.maxFiles) {
      this.sweetAlert.toast('You can upload maximum 3 files.', 'error');

      return;
    }

    files.forEach(file => {
      /**
       * Validate file type
       */
      if (!this.allowedTypes.includes(file.type)) {
        this.sweetAlert.toast(
          `${file.name}: File type is not supported.`,
          'error'
        );
        // console.warn(`${file.name}: File type is not supported.`);

        return;
      }

      /**
       * Validate file size
       */
      if (file.size > this.maxFileSize) {
        this.sweetAlert.toast(
          `${file.name}: File size must be less than 10MB.`,
          'error'
        );
        // console.warn(`${file.name}: File size must be less than 10MB.`);

        return;
      }

      /**
       * Prevent duplicate files
       */
      const alreadyExists = this.selectedFiles.some(
        item => item.file.name === file.name && item.file.size === file.size
      );

      if (alreadyExists) {
        return;
      }

      const isPdf = file.type === 'application/pdf';

      const preview = isPdf ? null : URL.createObjectURL(file);

      this.selectedFiles.push({
        file,
        preview,
        isPdf
      });
    });
  }

  /**
   * Remove file
   */
  removeFile(index: number): void {
    const item = this.selectedFiles[index];

    if (!item) {
      return;
    }

    /**
     * Free object URL
     */
    if (item.preview) {
      URL.revokeObjectURL(item.preview);
    }

    this.selectedFiles.splice(index, 1);
  }

  /**
   * Get file size
   */
  getFileSize(file: File): string {
    const sizeInMB = file.size / (1024 * 1024);

    return `${sizeInMB.toFixed(2)} MB`;
  }

  /**
   * Cleanup preview URLs
   */
  ngOnDestroy(): void {
    this.selectedFiles.forEach(item => {
      if (item.preview) {
        URL.revokeObjectURL(item.preview);
      }
    });
  }
}
