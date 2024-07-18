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
  favChannelList:any;
  favoriteChannels: number[] = [];


  constructor( private crud: CrudService,private router: Router, private ts: ToastrService){}
  ngOnInit(): void {
    console.log('Home page start..')
    this.loginSliders();
    this.getFavoriteList();
    this.getRecentsListData();
    this.channelsWithGenre();
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

  // Check if a channel is marked as favorite
  isFavorite(channelId: number): boolean {
    return this.favoriteChannels.includes(channelId);
}


          // Toggle favorite status
    toggleFavorite(channelId: number): void {
      // If channel is already favorite, remove it
      if (this.isFavorite(channelId)) {
          this.removeFavoriteChannel(channelId);
      } else {
          // add it to favorites
          this.addFavoriteChannel(channelId);
      }
  }
  
  // 
      getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(localStorage.getItem('deviceInfo') || '').access_token;
    return new HttpHeaders({
        'Authorization': `Bearer ${tokenData}`,
        'Accept': 'application/json'
    });
}


   // Add channel to favorites
   addFavoriteChannel(channelId: number): void {
    // Call API to add channel to favorites
    this.crud.postRequest('auth/addtofavorite', { channelid: channelId }, this.getAuthHeaders()).subscribe((res: any) => {
        if (res.response_code === 200) {
            this.favoriteChannels.push(channelId); // Update local favorite list
            this.updateFavoriteChannels(); // Update local storage
            this.ts.success(res.message);
        }
    });
}

// Remove channel from favorites
removeFavoriteChannel(channelId: number): void {
    // Call API to remove channel from favorites
    this.crud.postRequest('auth/unfavorite', { channelid: channelId }, this.getAuthHeaders()).subscribe((res: any) => {
        if (res.response_code === 200) {
            this.favoriteChannels = this.favoriteChannels.filter(id => id !== channelId); // Update local favorite list
            this.updateFavoriteChannels(); // Update local storage
            this.ts.success(res.message);
        }
    });
}

// Fetch favorite list
getFavoriteList(): void {
  console.log('==========================================>')
    const storedFavoriteChannels = localStorage.getItem('favoriteChannels');
    if (storedFavoriteChannels) {
        this.favoriteChannels = JSON.parse(storedFavoriteChannels);
    } else {
        // Fetch from API if not found in local storage
        this.crud.getRequest('auth/my_favorites', this.getAuthHeaders()).subscribe((res: any) => {
            if (res.response_code === 200) {
                this.favoriteChannels = res.data.map((channel: any) => channel.id);
                this.updateFavoriteChannels(); // Update local storage
            }
        });
    }
}

// Update local storage with current favorite channels
updateFavoriteChannels(): void {
  console.log('favoriteChannels',this.favoriteChannels);
    localStorage.setItem('favoriteChannels', JSON.stringify(this.favoriteChannels));
}

// set player data in local 
setplayerData(id:string,channel_id?:any){
  localStorage.setItem('playerId',id);
  localStorage.setItem('playerChannelId',channel_id)
  this.router.navigateByUrl('/user/playstream');
}

genre_Data:any ;
// get genre channel
channelsWithGenre() {
  const body = {
    "typeurl": "mpd",
    "zipcode": ""
}
  this.crud.postRequest('auth/channel-list', body, this.getAuthHeaders()).subscribe({
    next: (res: any) => {
      // const list = res.data;
      // localStorage.setItem('allChannel',JSON.stringify(list));
      // this.data = JSON.parse(localStorage.getItem('allChannel'));
      this.genre_Data = res.data;
      console.log(this.genre_Data);
    },
    error: (err: any) => {
      console.log(err);
    }
  });
}

recentsPlayData: any;
getRecentsListData(): void {
  // Fetch from API if not found in local storage
  const body = {"typeurl" : "mpd"}
  this.crud.postRequest('auth/recent-channels',body).subscribe((res: any) => {
    if (res.response_code === 200) {
      this.recentsPlayData = res.data;
    }
  });
}
}
