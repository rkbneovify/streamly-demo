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
  
  // set local variable
  setLocalStorage(keyName:string,data:any){
    try {
      localStorage.setItem(keyName,JSON.stringify(data));
      return true;
    } catch (error) {
      console.log(error);
      return false;
    }
    
  }


  removeLeadingTrailingSpaces(str: string): string {
    return str.trim();
  }

  // Replace multiple spaces with a single space and trim
  removeExtraSpaces(str: string): string {
    return str.replace(/\s+/g, ' ').trim();  
  }
}
