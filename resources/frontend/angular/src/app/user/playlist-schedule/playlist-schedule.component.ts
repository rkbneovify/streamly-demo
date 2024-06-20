import { animate, style, transition, trigger } from '@angular/animations';
import { NgFor, NgIf, NgStyle } from '@angular/common';
import { Component, OnInit} from '@angular/core';
import { RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { DurationFormatPipe } from '../../pipe/duration-format.pipe';
import { TimeFormatPipe } from '../../pipe/time-format.pipe';


@Component({
  selector: 'app-playlist-schedule',
  standalone: true,
  imports: [NgFor, RouterLink, NgIf,TimeFormatPipe,DurationFormatPipe,NgStyle],
  templateUrl: './playlist-schedule.component.html',
  styleUrl: './playlist-schedule.component.scss',
  animations: [
    trigger('slideInOut', [
      transition(':enter', [
        style({ transform: 'translateX(-100%)' }),
        animate('300ms ease-out', style({ transform: 'translateX(0%)' }))
      ]),
      transition(':leave', [
        animate('300ms ease-in', style({ transform: 'translateX(100%)' }))
      ])
    ])
  ]
})
export class PlaylistScheduleComponent implements OnInit {
  playlist_details_show: boolean = false;
  scheduleHours = [
    '12:00 AM',
    '12:30 AM',
    '01:00 AM',
    '01:30 AM',
    '02:00 AM',
    '02:30 AM',
    '03:00 AM',
    '03:30 AM',
    '04:00 AM',
    '04:30 AM',
    '05:00 AM',
    '05:30 AM',
    '06:00 AM',
    '06:30 AM',
    '07:00 AM',
    '07:30 AM',
    '08:00 AM',
    '08:30 AM',
    '09:00 AM',
    '09:30 AM',
    '10:00 AM',
    '10:30 AM',
    '11:00 AM',
    '11:30 AM',
    '12:00 PM',
    '12:30 PM',
    '01:00 PM',
    '01:30 PM',
    '02:00 PM',
    '02:30 PM',
    '03:00 PM',
    '03:30 PM',
    '04:00 PM',
    '04:30 PM',
    '05:00 PM',
    '05:30 PM',
    '06:00 PM',
    '06:30 PM',
    '07:00 PM',
    '07:30 PM',
    '08:00 PM',
    '08:30 PM',
    '09:00 PM',
    '09:30 PM',
    '10:00 PM',
    '10:30 PM',
    '11:00 PM',
    '11:30 PM',
  ];

  categories: any = [
    'Comedy',
    'Family',
    'Kids',
    'Action',
    'Drama',
    'Thriller',
    'Romance',
    'Sci-Fi',
    'Fantasy',
    'Adventure',
    'Mystery',
    'Documentary',
    'Crime',
    'Animation',
    'Reality TV',
  ];

  visibleCategories = this.categories.slice(0, 6); // Initially show the first three categories

  constructor(private apiService:CrudService,private toaster:ToastrService) {}

  ngOnInit(): void {
    console.log('minutes value====>',this.calculateWidth(1718841600,1718845200));
  }

  showPlaylist() {
    this.playlist_details_show = !this.playlist_details_show;
  }

  moveCategories(direction: 'left' | 'right') {
    const step = 1; // Number of categories to move
    if (direction === 'left') {
      // Move left
      const firstIndex = this.categories.indexOf(this.visibleCategories[0]);
      if (firstIndex > 0) {
        this.visibleCategories = this.categories.slice(
          firstIndex - step,
          firstIndex + 6 - step
        );
      }
    } else if (direction === 'right') {
      // Move right
      const lastIndex = this.categories.indexOf(
        this.visibleCategories[this.visibleCategories.length - 1]
      );
      if (lastIndex < this.categories.length - 1) {
        this.visibleCategories = this.categories.slice(
          lastIndex + 1,
          lastIndex + 1 + 6
        );
      }
    }
  }

  getScheduleProgram:any;
  getScheduleChannelLogo:any;
  getScheduleData(){
    const body = {
      "typeurl": "mpd",
      "genre": "Movies"
  }
    this.apiService.postRequest('auth/epg-guide',body).subscribe({
      next:(res:any)=>{
        console.log(res);
        this.getScheduleChannelLogo = this.filterchannelAnd(res.data);
        this.getScheduleProgram = res.data;
      },
      error:(err:any)=>{
        console.error(err);
      }
    })
  }

  // get the channels log and id 
  filterchannelAnd(stations:any){
    return stations
      .filter(station => station.prgSvcId !== null)
      .map(station => ({
        id: station.id,
        image: station.image,
        url: station.channel_url
      }))
  }

  // function to change the time into width
  calculateWidth(start:any,end:any){

    if (!start || !end) {
      return '';
    }

    const startDate = new Date(start * 1000); // Convert to milliseconds
    const endDate = new Date(end * 1000); // Convert to milliseconds
    const durationMs = endDate.getTime() - startDate.getTime(); // Calculate the duration in milliseconds
    const durationMinutes = Math.floor(durationMs / (1000 * 60)); // Convert duration to minutes
    return durationMinutes * 10;
  }
}
