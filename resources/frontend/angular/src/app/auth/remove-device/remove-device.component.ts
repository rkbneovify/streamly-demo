import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { NgFor } from '@angular/common';

@Component({
  selector: 'app-remove-device',
  standalone: true,
  imports: [NgFor],
  templateUrl: './remove-device.component.html',
  styleUrl: './remove-device.component.scss',
})
export class RemoveDeviceComponent implements OnInit {
  response: any;
  connectedDevice!:any;
  constructor(private apiService: CrudService) {}

  ngOnInit() {
    this.apiService.apiResponse$.subscribe((response) => {
      console.log('response api--->',response.error.loggedindeviceinfolist.data);      
      this.response = response;
      this.connectedDevice = response.error.loggedindeviceinfolist.data;   
    });
  }

  removedevice(id:string):void{
    this.apiService.postRequest('auth/same-device-id-logout',{'id':id}).subscribe({
      next:(res:any)=>{
        if(res.response_code == 200){
          this.connectedDevice = this.connectedDevice.filter((data:any)=>data.session_id !== id)
        }
      },
      error:err=>console.log(err)
      
    });
  }
}
