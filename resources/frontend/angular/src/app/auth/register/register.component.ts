import { NgIf } from '@angular/common';
import { Component,HostListener } from '@angular/core';
import { NgClass } from '@angular/common';
import { CrudService } from '../../services/crud.service';

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
  constructor(private crud: CrudService){}
  
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
  searchZipCode(event:any){
    var data = {
      search: event.target.value
    }
    this.crud.postRequest(`auth/getzipcodes`,data).subscribe((res:any)=>{
      console.log(res,'Response of get zip code api');
      
    });
  }

  
}
