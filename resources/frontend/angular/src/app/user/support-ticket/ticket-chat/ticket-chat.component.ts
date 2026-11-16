import { AfterViewInit, Component, ElementRef, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { CommonModule, DatePipe, NgFor, NgIf } from '@angular/common';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CrudService } from '../../../services/crud.service';
import { ActivatedRoute, Router } from '@angular/router';
import { SweetalertService } from '../../../services/SweetAlert/sweetalert.service';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser';
import { AvatarPipe } from '../../../pipe/avatar/avatar.pipe';
import { QuillModule } from 'ngx-quill';
import { ImageGalleryComponent } from '../../../components/image-gallery/image-gallery.component';
import { FileUploadComponent } from '../../../components/file-upload/file-upload.component';


@Component({
  selector: 'app-ticket-chat',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    DatePipe,
    NgIf,
    NgFor,
    AvatarPipe,
    QuillModule,
    ImageGalleryComponent,
    FileUploadComponent],
  templateUrl: './ticket-chat.component.html',
  styleUrl: './ticket-chat.component.scss'
})
export class TicketChatComponent implements OnInit, AfterViewInit, OnDestroy {


  ticketData: any = {};
  ticketReplyForm!: FormGroup;
  description = '';
  selectedFiles: File[] = [];
  ticketNo: string | null = null;
  @ViewChild(FileUploadComponent)
  fileUploadComponent: FileUploadComponent;

  showReplyForm = false;
  replyMessage = '';

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

  constructor(
    private api: CrudService,
    private alert: SweetalertService,
    private activatedRoute: ActivatedRoute,
    private sanitizer: DomSanitizer,
    private fb: FormBuilder
  ) {
    this.initializeForm();
  }
  ngAfterViewInit(): void {

  }

  ngOnInit(): void {
    this.activatedRoute.paramMap.subscribe(params => {
      this.ticketNo = params.get('ticketId');
      this.getLoadTicket(this.ticketNo);
    });

  }
  ngOnDestroy(): void {

  }

  initializeForm() {
    this.ticketReplyForm = this.fb.group({
      description: ['', Validators.required],
      attachment: [null]
    });
  }
  getSvg(svg: string): SafeHtml {
    return this.sanitizer.bypassSecurityTrustHtml(svg);
  }

  getLoadTicket(ticketNo: string) {
    this.alert.loading();
    this.api.getRequest(`support-ticket/${ticketNo}`).subscribe({
      next: (res: any) => {
        this.alert.close();
        console.log('Res :', res.data);
        this.ticketData = res.data;
      },
      error: (err: any) => {
        this.alert.close();
        console.log('Error :', err);
        this.alert.error(err.error.message);
      },
    });
  }

  openReply(): void {
    this.showReplyForm = !this.showReplyForm;
    this.ticketReplyForm.reset();
    this.fileUploadComponent.clearAll();
  }

  cancelReply(): void {
    this.showReplyForm = false;
    this.ticketReplyForm.reset();
    this.fileUploadComponent.clearAll();
  }

  sendReply(): void {

    if (this.ticketReplyForm.invalid) {
      this.ticketReplyForm.markAllAsTouched();
      return;
    }
    const formData = new FormData();
    formData.append(
      'ticket_number',
      this.ticketNo
    );
    formData.append(
      'message',
      this.ticketReplyForm.value.description
    );
    this.selectedFiles.forEach(file => {
      formData.append('attachment[]', file);
    });

    this.alert.loading();

    this.api.postRequest('support-ticket/reply', formData).subscribe({
      next: (res: any) => {

        this.showReplyForm = false;
        this.ticketReplyForm.reset();
        this.fileUploadComponent.clearAll();
        // this.alert.success(res.message, "success");
        setTimeout(() => {
          this.alert.close();
          location.reload();
        }, 3000)


      },
      error: (err: any) => {
        this.alert.close();
        this.alert.error(err.error.message, "error");
      },
    })

  }

  onFileSelected(event: Event): void {
    const input = event.target as HTMLInputElement;

    if (input.files && input.files.length > 0) {
      this.ticketReplyForm.patchValue({
        attachment: input.files[0]
      });
    }
  }

  onFilesSelected(files: File[]) {
    this.selectedFiles = files;

    // console.log('Selected Files:', files);
  }

}
