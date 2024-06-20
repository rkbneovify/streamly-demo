import { Component, OnInit } from '@angular/core';
import { ActivatedRoute ,Router} from '@angular/router';
import { CrudService } from '../../../services/crud.service';
import { NgClass, NgFor, NgIf } from '@angular/common';
import { HttpHeaders } from '@angular/common/http';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-family',
  standalone: true,
  imports: [NgFor,NgClass,NgIf],
  templateUrl: './family.component.html',
  styleUrl: './family.component.scss'
})
export class FamilyComponent implements OnInit {
  keyword: string;
  body: any = {
    typeurl: 'mpd',
    zipcode: '',
  };
  data:any;
  Bearer_token:any;
  favChannelList:any;
  favoriteChannels: number[] = [];
  constructor(private router: Router,private route: ActivatedRoute, private apiService: CrudService,private ts:ToastrService) {}

  ngOnInit(): void {
    this.route.url.subscribe((urlSegments) => {
      this.keyword = urlSegments[urlSegments.length - 1].path;

      if (this.keyword) {
        const body = {
          "typeurl": "mpd",
          "genre":this.keyword 
      }
        this.apiService.filterChannelResponse(body).subscribe({
          next:(res:any)=>{
            console.log('Data===>',res)
            this.data = res?.data;
            this.getFavoriteList();
          }
        });
      }
      console.log(this.keyword);
    });
  }

  getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(
      localStorage.getItem('deviceInfo') || ''
    ).access_token;
    // this.Bearer_token = tokenData;
    return new HttpHeaders({
      Authorization: `Bearer ${tokenData}`,
      Accept: 'application/json',
    });
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
  


   // Add channel to favorites
   addFavoriteChannel(channelId: number): void {
    // Call API to add channel to favorites
    this.apiService.postRequest('auth/addtofavorite', { channelid: channelId }, this.getAuthHeaders()).subscribe((res: any) => {
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
    this.apiService.postRequest('auth/unfavorite', { channelid: channelId }, this.getAuthHeaders()).subscribe((res: any) => {
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
    const storedFavoriteChannels = localStorage.getItem('allFavoriteChannels');
    if (storedFavoriteChannels) {
        this.favoriteChannels = JSON.parse(storedFavoriteChannels);
    } else {
        // Fetch from API if not found in local storage
        this.apiService.getRequest('auth/my_favorites', this.getAuthHeaders()).subscribe((res: any) => {
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
    localStorage.setItem('allFavoriteChannels', JSON.stringify(this.favoriteChannels));
}

// set player data in local 
setplayerData(id:string){
  localStorage.setItem('playerId',id);
  this.router.navigateByUrl('/user/playstream')
}
}
