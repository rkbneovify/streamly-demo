import { NgFor } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { QuillModule } from 'ngx-quill';
import { FileUploadComponent } from '../../components/file-upload/file-upload.component';
import { CrudService } from '../../services/crud.service';
@Component({
  selector: 'app-support-ticket',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, NgFor, QuillModule, FileUploadComponent],
  templateUrl: './support-ticket.component.html',
  styleUrl: './support-ticket.component.scss'
})
export class SupportTicketComponent implements OnInit {
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

  constructor(private fb: FormBuilder, private api: CrudService) {
    this.initializeForm();
  }

  ngOnInit(): void {
    this.getCategories();
  }

  getCategories() {
    this.api.getRequest('blogs/categories')
      .subscribe({
        next: (res: any) => {
          console.log("res :", res)
          this.categories = res.data;
        },
        error: (err: any) => {
          console.log("error :", err.error)
        },
      })
  }

  getSubCategories(category: string) {
    this.api.getRequest(`blogs/${category}/sub-categories`)
      .subscribe({
        next: (res: any) => {
          console.log("res :", res)
          this.subCategories = res.data;
        },
        error: (err: any) => {
          console.log("error :", err.error)
        },
      })
  }

  onCategoryChange(event: Event) {
    this.selectedCategory = (event.target as HTMLSelectElement).value;
    this.getSubCategories(this.selectedCategory);
  }

  initializeForm(): void {
    this.ticketForm = this.fb.group({
      category: [null, Validators.required],
      subCategory: [null, Validators.required],
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

    console.log('Selected Files:', files);
  }

  submitTicket(): void {
    // if (this.ticketForm.invalid) {
    //   this.ticketForm.markAllAsTouched();
    //   return;
    // }

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
      'subject',
      this.ticketForm.value.subject
    );

    formData.append(
      'description',
      this.ticketForm.value.description
    );

    // if (this.ticketForm.value.attachment) {
    //   formData.append(
    //     'attachment',
    //     this.ticketForm.value.attachment
    //   );
    // }

    this.selectedFiles.forEach(file => {
      formData.append('attachment[]', file);
    });

    // for (const [key, value] of formData.entries()) {
    //   console.log(`${key}: ${value}`);
    // }
    // console.log(this.ticketForm.value);


    // API Call
    // this.ticketService.createTicket(formData).subscribe(...)
  }


}
