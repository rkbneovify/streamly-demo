import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { CrudService } from '../crud.service';
import { Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class UtilsService {
  constructor(private router: Router,private apiService: CrudService) {}

  // set the channelID and the channel video URL
  setChannelId(id: string, url: string) {
    localStorage.setItem('playerChannelId', id);
    localStorage.setItem('playerId', url);
    this.router.navigateByUrl('user/playstream');
  }

  // set local variable
  setLocalStorage(keyName: string, data: any) {
    try {
      localStorage.setItem(keyName, JSON.stringify(data));
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

  timeDifferenceInMinutes(inputTime: string): number {
    // Get the current date and time
    const now: any = new Date();

    // Convert the input time (hh:mm AM/PM) into a Date object
    const [time, period] = inputTime.split(' '); // Split the time and AM/PM part
    const [hours, minutes] = time.split(':'); // Split the hours and minutes

    let hour = parseInt(hours);
    const minute = parseInt(minutes);

    // Adjust for AM/PM
    if (period === 'PM' && hour !== 12) {
      hour += 12; // Convert PM hours to 24-hour format
    } else if (period === 'AM' && hour === 12) {
      hour = 0; // Handle 12 AM as 00 hours in 24-hour format
    }
    // Create a Date object for the input time, keeping today's date
    const inputDateTime: any = new Date(
      now.getFullYear(),
      now.getMonth(),
      now.getDate(),
      hour,
      minute
    );
    // Calculate the difference in milliseconds
    const diffInMillis = inputDateTime - now;
    // Convert milliseconds to minutes
    const diffInMinutes = Math.round(diffInMillis / (1000 * 60));

    return diffInMinutes;
  }

  isCustomUser(): boolean {
    const user = localStorage.getItem('user_connected_device');
    return user === '1';
  }


  get_all_device(): Observable<any> {
    return this.apiService.getRequest('auth/newconnecteddevice').pipe(
      tap((res: any) => {
        if (res.response_code === 200) {
          localStorage.setItem('user_connected_device', String(res.user_connected_device));
        }
      })
    );
  }
  
}
