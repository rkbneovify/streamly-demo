import { NgFor, NgIf } from '@angular/common';
import { Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { QuillModule } from 'ngx-quill';
import { FileUploadComponent } from '../../components/file-upload/file-upload.component';
import { CrudService } from '../../services/crud.service';
import { SweetalertService } from '../../services/SweetAlert/sweetalert.service';
import { finalize } from 'rxjs';
import { environment } from '../../../environments/environment';
import { RecaptchaModule, RecaptchaComponent } from 'ng-recaptcha';
@Component({
  selector: 'app-support-ticket',
  standalone: true,
  imports: [FormsModule,
    ReactiveFormsModule,
    NgFor,
    NgIf,
    QuillModule,
    FileUploadComponent,
    RecaptchaModule],
  templateUrl: './support-ticket.component.html',
  styleUrl: './support-ticket.component.scss'
})
export class SupportTicketComponent implements OnInit {

  siteKey: string = environment.google.captchaKey;
  captchaToken: string | null = null;
  // ================
  ticketForm!: FormGroup;
  description = '';
  selectedFiles: File[] = [];


  quillConfig = {
    toolbar: [
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ font: [] }],
      [{ size: ['small', false, 'large', 'huge'] }],

      ['bold', 'italic', 'underline', 'strike'],

      [{ color: [] }, { background: [] }],

      [{ script: 'sub' }, { script: 'super' }],

      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ indent: '-1' }, { indent: '+1' }],

      [{ align: [] }],

      ['blockquote', 'code-block'],

      ['link'],

      ['clean']
    ]
  };

  categories: any[] = [];

  subCategories: any[] = [];

  selectedCategory = '';
  selectedSubCategory = '';

  filteredSubCategories: string[] = [];
  isLoading: boolean = false;


  @ViewChild(FileUploadComponent)
  fileUploadComponent: FileUploadComponent;


  constructor(private fb: FormBuilder, private api: CrudService, private sweetAlert: SweetalertService) {
    this.initializeForm();
  }

  ngOnInit(): void {
    this.getCategories();
    this.initializeForm();


  }

  resolved(token: any) {
    // console.log("captcha token :", token);
    // this.captchaToken = token;

    const formData = new FormData();


    formData.append(
      'category',
      this.ticketForm.value.category
    );

    formData.append(
      'sub_category',
      this.ticketForm.value.subCategory
    );

    formData.append(
      'platform',
      this.ticketForm.value.platform
    );
    formData.append(
      'subject',
      this.ticketForm.value.subject
    );

    formData.append(
      'description',
      this.ticketForm.value.description
    );

    formData.append(
      'captcha_token',
      token
    );

    this.selectedFiles.forEach(file => {
      formData.append('attachment[]', file);
    });



    // formData.forEach((value, key) => {
    //   console.log(key, value);
    // });
    // console.log(formData);

    this.sweetAlert.loading();
    this.isLoading = true;
    this.api.postRequest('support-ticket/store', formData).pipe(finalize(() => {
      this.sweetAlert.close()
      this.isLoading = false;
    })).subscribe({
      next: (res: any) => {
        console.log('res:', res);

        // this.sweetAlert.toast(res.message, 'success')
        this.fileUploadComponent.clearAll();
        this.ticketForm.reset();
        setTimeout(() => {
          this.sweetAlert.toast(res.message);
        }, 1000);
      },
      error: (err: any) => {
        console.log("error:", err);
        this.sweetAlert.toast(err.error.message, 'error')
      }
    });

  }

  getCategories() {
    this.sweetAlert.loading();
    this.api.getRequest('blogs/categories')
      .pipe(finalize(() => {
        this.sweetAlert.close();
      }))
      .subscribe({
        next: (res: any) => {
          console.log("res :", res)
          this.categories = res.data;
        },
        error: (err: any) => {
          console.log("error :", err.error)
          this.sweetAlert.toast(err.error.message, 'error');

        },
      })
  }

  getSubCategories(category: string) {
    this.sweetAlert.loading();
    this.api.getRequest(`blogs/${category}/sub-categories`)
      .pipe(finalize(() => {
        this.sweetAlert.close();
      }))
      .subscribe({
        next: (res: any) => {
          console.log("res :", res)
          this.subCategories = res.data;
        },
        error: (err: any) => {
          console.log("error :", err.error)
          this.sweetAlert.toast(err.error.message, 'error');
        },
      })
  }

  onCategoryChange(event: Event) {
    this.selectedCategory = (event.target as HTMLSelectElement).value;
    this.getSubCategories(this.selectedCategory);
  }

  private initializeForm(): void {
    this.ticketForm = this.fb.group({
      category: [null, Validators.required],
      subCategory: [null, Validators.required],
      platform: [null, Validators.required],
      subject: ['', [
        Validators.required,
        Validators.maxLength(255)
      ]],
      description: ['', Validators.required],

      attachment: [null]
    });

  }



  onFileSelected(event: Event): void {
    const input = event.target as HTMLInputElement;

    if (input.files && input.files.length > 0) {
      this.ticketForm.patchValue({
        attachment: input.files[0]
      });
    }
  }

  onFilesSelected(files: File[]) {
    this.selectedFiles = files;

    // console.log('Selected Files:', files);
  }

  submitTicket(captcha: RecaptchaComponent): void {


    if (this.ticketForm.invalid) {
      this.ticketForm.markAllAsTouched();
      return;
    }


    captcha.execute();





  }

  onReset(captcha: RecaptchaComponent): void {
    captcha.reset();
    this.fileUploadComponent.clearAll();
    this.ticketForm.reset();
  }




}
