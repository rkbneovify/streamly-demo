import { NgIf } from '@angular/common';
import { Component,HostListener } from '@angular/core';
import { NgClass } from '@angular/common';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [NgIf,NgClass],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss'
})
export class RegisterComponent {
  selectedTab: string = 'Availability';
  isWidth767:number=window.innerWidth;
  
  @HostListener('window:resize',['$event'])
  onResize(event:Event):void{
    this.isWidth767 = window.innerWidth;
    // console.log(this.isWidth767);
  }

  applyclassForAva():boolean{
    // console.log(this.isWidth767 === 767 && this.selectedTab === 'Availability');
      return this.isWidth767 <= 767 && this.selectedTab === 'Availability';
  }
  applyclassForVer():boolean{
      return this.isWidth767 <= 767 && this.selectedTab === 'Verification';
  }

  
}
