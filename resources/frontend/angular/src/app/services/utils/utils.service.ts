import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class UtilsService {

  constructor(private router:Router) { }

  // set the channelID and the channel video URL
  setChannelId(id:string,url:string){
    localStorage.setItem('playerChannelId',id);
    localStorage.setItem('playerId',url);
    this.router.navigateByUrl('user/playstream');
  }
  
  setLocalStorage(keyName:string,data:any){
    try {
      localStorage.setItem(keyName,JSON.stringify(data));
      return true;
    } catch (error) {
      console.log(error);
      return false;
    }
    
  }
}
