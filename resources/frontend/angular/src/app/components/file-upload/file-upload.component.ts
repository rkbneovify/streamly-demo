import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Output } from '@angular/core';

interface PreviewFile {
  file: File;
  preview: string;
  type: 'image' | 'video' | 'other';
}

@Component({
  selector: 'app-file-upload',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './file-upload.component.html',
  styleUrls: ['./file-upload.component.scss']
})
export class FileUploadComponent {

  @Output() filesChange = new EventEmitter<File[]>();

  isDragging = false;
  files: PreviewFile[] = [];

  onDragOver(event: DragEvent): void {
    event.preventDefault();
    this.isDragging = true;
  }

  onDragLeave(event: DragEvent): void {
    event.preventDefault();
    this.isDragging = false;
  }

  onDrop(event: DragEvent): void {
    event.preventDefault();
    this.isDragging = false;

    const droppedFiles = event.dataTransfer?.files;

    if (droppedFiles?.length) {
      this.processFiles(droppedFiles);
    }
  }

  onFileSelected(event: Event): void {
    const input = event.target as HTMLInputElement;

    if (input.files?.length) {
      this.processFiles(input.files);
    }

    input.value = '';
  }

  processFiles(fileList: FileList): void {

    Array.from(fileList).forEach(file => {

      let type: 'image' | 'video' | 'other' = 'other';

      if (file.type.startsWith('image/')) {
        type = 'image';
      } else if (file.type.startsWith('video/')) {
        type = 'video';
      }

      this.files.push({
        file,
        preview: URL.createObjectURL(file),
        type
      });
    });

    this.emitFiles();
  }

  removeFile(index: number): void {
    URL.revokeObjectURL(this.files[index].preview);
    this.files.splice(index, 1);

    this.emitFiles();
  }

  clearAll(): void {
    this.files.forEach(file => URL.revokeObjectURL(file.preview));
    this.files = [];

    this.emitFiles();
  }

  private emitFiles(): void {
    this.filesChange.emit(
      this.files.map(item => item.file)
    );
  }
}
