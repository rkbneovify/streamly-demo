import { NgIf,NgFor } from '@angular/common';
import { Component,HostListener, OnInit, Renderer2 } from '@angular/core';
import { NgClass } from '@angular/common';
import { CrudService } from '../../services/crud.service';
import { FormControl, FormGroup, FormsModule } from '@angular/forms';
import { NgSelectModule } from '@ng-select/ng-select';
import { RouterLink } from '@angular/router';
import { ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [NgIf,NgFor,NgClass,FormsModule,NgSelectModule,RouterLink,ReactiveFormsModule],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss'
})
export class RegisterComponent implements OnInit {
  selectedTab: string = 'Availability';
  isWidth767:number=window.innerWidth;
  userState:string ='';
  userCity:string = '';

  zipcodedata:any =[];
  searchZipInput:string='';
  
  wrong_zipcodeDetials:FormGroup=new FormGroup({});

  constructor(private crud: CrudService){}
  ngOnInit(): void {
    this.wrong_zipcodeDetials = new FormGroup({
      w_email: new FormControl(null),
    })
    this.getZipcode('');
  }
  @HostListener('window:resize',['$event'])
  onResize(event:Event):void{
    this.isWidth767 = window.innerWidth;
    // console.log(this.isWidth767);
  }

  // click to open the login popup
  showAlreadyUserModal(): void {
    document.getElementById('btnstaticAlreadyUser')?.click();  
  }
  // click to open the correct zipcode popup
  showCorrectZipcode():void{
    document.getElementById('btnstaticZipCorrectUser')?.click();  
  }
  // click to open the wrong zipcode popup
  showWrongZipcode():void{
    document.getElementById('btnstaticZipWrongUser')?.click();

  }

  applyclassForAva():boolean{
    // console.log(this.isWidth767 === 767 && this.selectedTab === 'Availability');
      return this.isWidth767 <= 767 && this.selectedTab === 'Availability';
  }
  applyclassForVer():boolean{
      return this.isWidth767 <= 767 && this.selectedTab === 'Verification';
  }

  getZipCodeInvalid(data:any):void{
    const userDetails = {
      "email": data.w_email,
    "zip": this.searchZipInput,
    "city": this.userCity,
    "state": this.userState,
    }
    console.log(userDetails);
    this.crud.postRequest('api/auth/zipcodeInquery',userDetails).subscribe({
    })
    console.log('this is invalid....')
  }
  
  checkZipCodeValid(zipcode:any):boolean{
    const search = zipcode;
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
          this.showAlreadyUserModal()
        }else{
          console.log('check zip code');
          console.log('value',this.checkZipCodeValid(this.searchZipInput),'zipInput',this.searchZipInput)
          const isZipCodeValid = this.checkZipCodeValid(this.searchZipInput);
          if (isZipCodeValid) {
            console.log('Zip code is valid. Changing selectedTab to "done".');
            this.showCorrectZipcode();
          } else {
            this.showWrongZipcode();
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

  submitFormWrong(){
    this.getZipCodeInvalid(this.wrong_zipcodeDetials.value)
    console.log('00000000000000000',this.wrong_zipcodeDetials);
    console.log('00000000000000000',this.wrong_zipcodeDetials.value)

  }
}
