import { CommonModule, NgClass } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-user-home',
  standalone: true,
  imports: [CommonModule, NgClass,RouterLink],
  templateUrl: './user-home.component.html',
  styleUrl: './user-home.component.scss'
})
export class UserHomeComponent implements OnInit {

  access_Token:any;
  sldierData:any;


  constructor( private crud: CrudService,private router: Router, private ts: ToastrService){}
  ngOnInit(): void {
    this.loginSliders();
  }


  loginSliders (){
    const userToken = localStorage.getItem('deviceInfo');
    if (userToken !== null) {
      try {
        this.access_Token = JSON.parse(localStorage.getItem('deviceInfo') || '');
        const tokenData = this.access_Token?.access_token;
        const headers = new HttpHeaders({
          'Authorization': `Bearer ${tokenData}`,
          'Accept': 'application/json'
        });
        console.log(tokenData,'--------token');
        this.crud.getRequest('auth/channel-sliders', headers).subscribe((res: any) => {
          console.log('get all channel-sliders ->>',res);
          if(res.response_code == 200){
            this.sldierData = res.data;
          }
        });
      } catch (error) {
          console.error('Error parsing JSON data:--->>>', error);
      }
  }
    // console.log('hello');
  }

}
