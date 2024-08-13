import { DatePipe, NgClass, NgFor, NgIf } from '@angular/common';
import { HttpHeaders } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import {  Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { CrudService } from '../../services/crud.service';

@Component({
  selector: 'app-accountsettings',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass, NgIf,DatePipe,NgFor],
  templateUrl: './accountsettings.component.html',
  styleUrl: './accountsettings.component.scss',
})
export class AccountsettingsComponent implements OnInit {
  updateuserDetatils!: FormGroup;
  editMode: boolean = false;
  showPassword: boolean = false;
  usersdetail: any;
  deviceInfo: any;
  access_Token: any;
  updated_data: any;
  homeTimeZone!:string;
  Bearer_token:any;
  planName!:string;
  renewalDate:any;
  cardDetails!:any;
  cardDetails1:any;
  invoices:any;
  billingScheme!:string;
  tiredPlansDetails:any;
  invoicesDate!:any;
  showTiredPlan:boolean=false;
  loading:boolean=false;
  subscribed:boolean;
  planInterval:any;
  interval_count:any;
  qunantity:any;
  qunantity_t:any;
  isLoading:boolean = false;

  constructor(
    private fb: FormBuilder,
    private crud: CrudService,
    private ts: ToastrService, 
    private router: Router
  ) {
    this.updateuserDetatils = this.fb.group({
      fullname: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      oldpassword: ['', Validators.required],
      newpassword: ['', Validators.required],
      zipcode: ['', Validators.required],
    });

    // delete user account form
    this.deleteAccountForm = this.fb.group({
      password: ['',[Validators.required]]
    });
  }

  ngOnInit(): void {
    this.getAllDetails();
    const jsonData = localStorage.getItem('userDetails');
    if (jsonData !== null) {
      try {
        this.usersdetail = JSON.parse(
          localStorage.getItem('userDetails') || ''
        );
      } catch (error) {
        console.error('Error parsing JSON data:', error);
      }
    }
    const deviceInfoData = localStorage.getItem('deviceInfo');
    if (deviceInfoData !== null) {
      try {
        this.deviceInfo = JSON.parse(localStorage.getItem('deviceInfo') || '');
        this.homeTimeZone = this.deviceInfo.timezone
      } catch (error) {
        console.error('Error parsing JSON data:', error);
      }
    }
    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
    this.updateuserDetatils.controls['email'].setValue(this.usersdetail?.email);
    this.updateuserDetatils.controls['zipcode'].setValue(
      this.usersdetail?.zipcode
    );

    this.subscribed = this.deviceInfo?.subscribed;
  }

  toggleEditMode() {
    this.editMode = !this.editMode;
    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
  }

  saveChanges() {
    // saving changes here
    this.loading = true;
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
    this.isLoading = true;
        this.access_Token = JSON.parse(
          localStorage.getItem('deviceInfo') || ''
        );
        const tokenData = this.access_Token?.access_token;
        const headers = new HttpHeaders({
          Authorization: `Bearer ${tokenData}`,
          Accept: 'application/json',
        });
        // if (this.updated_data) {
        let body = {
          name: this.updateuserDetatils.value.fullname,
          old_password: this.updateuserDetatils.value.oldpassword,
          password: this.updateuserDetatils.value.newpassword,
        };
        this.crud
          .postRequest('auth/account-edit', body, headers)
          .subscribe({
            next:(res: any) => {
              if(res.response_code == 200){
                this.loading = false;
                            // Update name in local storage
              this.usersdetail.name = body.name;
              localStorage.setItem(
                'userDetails',
                JSON.stringify(this.usersdetail)
                );
                
                this.ts.success(res.message);
                this.toggleEditMode();
              }else{
                this.loading = false;
                this.ts.error(res.message);
              }
              this.isLoading = false;
  
            },
            error:(err:any)=>{
              this.loading = false;
              err.error.message.password.forEach(element => {
                this.ts.error(element);
              });
            }
          });
        // }
      } catch (error) {
        console.error('Error parsing JSON data:--->>>', error);
    this.isLoading = false;
      }
    }

    this.updateuserDetatils.controls['fullname'].setValue(
      this.usersdetail?.name
    );
    this.updateuserDetatils.controls['oldpassword'].setValue('');
    this.updateuserDetatils.controls['newpassword'].setValue('');
    
    

    // user detials
  }

  getAllDetails(){
    this.isLoading = true;
    this.crud.getRequest('auth/setting-details',this.getAuthHeaders()).subscribe({
      next:(res:any)=>{
        let planFullName;
        const plan1st = res?.plan.nickname;
        const plan2nd = res?.plan?.currency;
        const plan3rd = res?.plan?.interval;
        this.planInterval = plan3rd;
        this.interval_count = res?.plan?.interval_count;

        // planFullName = `${plan1st?plan1st:''} ${plan3rd?plan3rd.substring(0, 3):''} ${plan2nd?plan2nd.substring(0,3):''}`;        
        
        this.billingScheme = res.plan.billing_scheme;
        this.invoices = res?.invoices;
        this.invoicesDate = new Date(this.invoices?.created * 1000);
        this.renewalDate = res?.subscription.renewaldatetime;
        this.planName = res.subscription.name;
        this.cardDetails = res?.subscription.payment_method;
        this.cardDetails1 = res.card_details;
        this.tiredPlansDetails = res.plan.tiers;
        this.qunantity = res.subscription.quantity

        this.get_price(this.qunantity,this.tiredPlansDetails)
    this.isLoading = true;
      },
      error:(err)=>{console.log(err); this.isLoading = true;}
    })
  }

  getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(
      localStorage.getItem('deviceInfo') || ''
    ).access_token;
    this.Bearer_token= tokenData;
    return new HttpHeaders({
      Authorization: `Bearer ${tokenData}`,
      Accept: 'application/json',
    });
  }

total_price:any = 0;


  get_price(quant:any,value:any){
    const amount10 =
    +value[0].unit_amount / 100;
    const amount18 =
    +value[1].unit_amount / 100;
    const amount16 =
    +value[2].unit_amount / 100;
    
    
    const upto10 =
    value[0].up_to;
    const upto18 =
    value[1].up_to;
    if(value){
      if(quant <=upto10){


        this.total_price = quant*amount10
      }else if(quant <=upto18 && quant >upto10){
        this.total_price = quant*amount18

      }else if(quant >upto18){
        this.total_price = quant*amount16
      }
    }
  }


// delete subscription
  deleteSub(): void {
    this.isLoading = true;
    this.crud.deleteRequest('cancel-subscription/616',this.getAuthHeaders()).subscribe({
      next: (res: any) => {
          this.router.navigate(['user/plan']);
          this.ts.success('subscription cancel successfully');
          this.deviceInfo.subscribed = false;
          localStorage.setItem('deviceInfo',JSON.stringify(this.deviceInfo));
          this.isLoading = false;
      },
      error: err => {this.ts.error(err.error?.error);this.isLoading = false;}

    });
  }

  // delete user account
  deleteAccountForm: FormGroup;
  showPopDeleteAccount(){
    document.getElementById('btnShowPopDeleteAccount').click();
  }

  deleteUserAccount(): void {
    this.isLoading = true;
    this.crud.postRequest('auth/user/delete',{password:this.deleteAccountForm.get('password').value}).subscribe({
      next: (res: any) => {
          this.ts.success('Account delete successfully');
          localStorage.clear();
          this.isLoading = false;
          this.router.navigate(['/']);
      },
      error: err => {this.ts.error(err.error?.message);this.isLoading = false;}

    });
  }

  

  showPopConf(){
    document.getElementById('btnShowPopConf').click();
  }
}
