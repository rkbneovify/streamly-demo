import { NgIf } from '@angular/common';
import { Component, ElementRef, HostListener, EventEmitter, Output } from '@angular/core';
@Component({
  selector: 'app-sort-by-dropdown',
  standalone: true,
  imports: [NgIf,],
  templateUrl: './sort-by-dropdown.component.html',
  styleUrl: './sort-by-dropdown.component.css'
})
export class SortByDropdownComponent {
  @Output() sortChanged = new EventEmitter<number | string>();
  isOpen = false;
  constructor(private eRef: ElementRef) { }

  toggleDropdown() {
    this.isOpen = !this.isOpen;
    // console.log("Clicked:", this.isOpen);
  }
  @HostListener('document:click', ['$event'])
  clickOutside(event: Event) {
    if (!this.eRef.nativeElement.contains(event.target)) {
      this.isOpen = false;
    }
  }
  selectOption(option: number | string | null) {
    this.sortChanged.emit(option)
    this.isOpen = false;
  }
}