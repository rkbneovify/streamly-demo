import { NgIf,NgFor } from '@angular/common';
import { Component,HostListener, OnInit } from '@angular/core';
import { NgClass } from '@angular/common';
import { CrudService } from '../../services/crud.service';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [NgIf,NgFor,NgClass,FormsModule],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss'
})
export class RegisterComponent implements OnInit {
  selectedTab: string = 'Availability';
  isWidth767:number=window.innerWidth;

  zipcodedata:any =[];
  // isUserExist:boolean = false;
  searchZipInput:string='';

  constructor(private crud: CrudService){}
  ngOnInit(): void {
    this.getZipcode('');
  }
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

  checkZipCodeValid(zipcode:any):boolean{
    // const searchData = {
    //   'search':userDetials.zip
    // };
    // this.crud.postRequest(`auth/getzipcodes`,searchData).subscribe({
    //   next:(res)=>{
    //     console.log(res,'zipcode');
    //   }
    //   ,
    //   error:(err)=>{
    //     console.log(err,'error Zip')
    //   }
    // });

    const search = zipcode;
    // return zipcodedata.some((zipObj) => zipObj.value === targetZip);
    return this.zipcodedata.some((zcode :any)=>{
      console.log(zcode.value,'==========================',search)
      return zcode.value === search
    })

  }

  getZipcode(data:any){
    this.searchZipInput=data;
    const searchData = {
      'search':data
    };
    this.crud.postRequest(`auth/getzipcodes`,searchData).subscribe((res:any)=>{
      console.log(res,'get zip code api');
      console.log(data,'key');
      // console.log(this.zipcodedata.length)
      if(this.zipcodedata.length === 0){
        this.zipcodedata =[...res];
        console.log('data added',this.zipcodedata)
      }
      
    });
  }

  checkEmailRegistration(data:any){
    const checkData = data; 
    const email = {"email" : data.email}
    this.crud.postRequest('auth/checkemail',email).subscribe({
      next:(resp:any)=>{
        const isUserExist = resp.exists;
        const message = resp.message;
        if(isUserExist){
          console.log(message);
        }else{
          console.log('check zip code');
          console.log('value',this.checkZipCodeValid(this.searchZipInput),'zipInput',this.searchZipInput)
          const isZipCodeValid = this.checkZipCodeValid(this.searchZipInput);
          if (isZipCodeValid) {
            console.log('Zip code is valid. Changing selectedTab to "done".');
            this.selectedTab = 'Verification';
          } else {
            console.log('Invalid zip code.');
          }
        }
        console.log(resp);
      },
      error:(error)=>{
        console.log(error);
      }
    });
  }

  submitForm(formData:any){
    const data = formData.value;
    this.checkEmailRegistration(data);
    console.log('form Data :',formData.value);    
  }
}
