import {
  Component,
  ElementRef,
  AfterViewInit,
  OnDestroy,
  ViewChild,
  Input
} from '@angular/core';

import lightGallery from 'lightgallery';
import lgThumbnail from 'lightgallery/plugins/thumbnail';
import lgZoom from 'lightgallery/plugins/zoom';

@Component({
  selector: 'app-image-gallery',
  standalone: true,
  imports: [],
  templateUrl: './image-gallery.component.html',
  styleUrl: './image-gallery.component.css'
})
export class ImageGalleryComponent implements AfterViewInit, OnDestroy {
  @ViewChild('gallery') gallery!: ElementRef;

  @Input() images: any[] = [];

  @Input() width = 120;
  @Input() height = 100;

  private galleryInstance: any;

  ngAfterViewInit(): void {
    this.initializeGallery();
  }

  initializeGallery(): void {
    if (!this.images.length) return;

    this.galleryInstance = lightGallery(
      this.gallery.nativeElement,
      {
        plugins: [lgThumbnail, lgZoom],
        speed: 500,
        thumbnail: true,
        zoom: true,
        download: false
      }
    );
  }

  ngOnDestroy(): void {
    this.galleryInstance?.destroy();
  }
}
