import { NgIf } from '@angular/common';
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

@Component({
  selector: 'app-get-location',
  standalone: true,
  imports: [NgIf],
  templateUrl: './get-location.component.html',
  styleUrl: './get-location.component.scss'
})
export class GetLocationComponent implements OnInit {
  @Input() isVisible: boolean = false;
  @Output() retry = new EventEmitter<void>();

  ngOnInit(): void {
      console.log('hello ==>',this.isVisible);
      if(this.isVisible){
        this.showPopConf()
      }
  }

  show(): void {
    this.isVisible = true;
    
  }
  showPopConf() {
    document.getElementById('btnShowPopConf').click();
  }
  hide(): void {
    this.isVisible = false;
  }

  retryLocationAccess(): void {
    this.retry.emit();
    // this.hide();
  }
}
