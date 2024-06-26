import { animate, style, transition, trigger } from '@angular/animations';
import { JsonPipe, NgFor, NgIf, NgStyle } from '@angular/common';
import {
  AfterViewInit,
  Component,
  ElementRef,
  OnDestroy,
  OnInit,
  QueryList,
  ViewChild,
  ViewChildren,
} from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { CrudService } from '../../services/crud.service';
import { ToastrService } from 'ngx-toastr';
import { DurationFormatPipe,DurationISOFormatPipe,TruncatePipe } from '../../pipe/duration-format.pipe';
import { TimeFormatPipe } from '../../pipe/time-format.pipe';
import { FormsModule } from '@angular/forms';
import { ScheduleFormatterPipe } from '../../pipe/schedule-formatter.pipe';

@Component({
  selector: 'app-playlist-schedule',
  standalone: true,
  imports: [
    NgFor,
    RouterLink,
    NgIf,
    TimeFormatPipe,
    DurationFormatPipe,
    NgStyle,
    FormsModule,
    DurationISOFormatPipe,
    TruncatePipe,
    ScheduleFormatterPipe,
    JsonPipe
  ],
  templateUrl: './playlist-schedule.component.html',
  styleUrl: './playlist-schedule.component.scss',
  animations: [
    trigger('slideInOut', [
      transition(':enter', [
        style({ transform: 'translateX(-100%)' }),
        animate('300ms ease-out', style({ transform: 'translateX(0%)' })),
      ]),
      transition(':leave', [
        animate('300ms ease-in', style({ transform: 'translateX(100%)' })),
      ]),
    ]),
  ],
})
export class PlaylistScheduleComponent implements OnInit, AfterViewInit,OnDestroy {
  playlist_details_show: boolean = false;
  @ViewChild('scrollablecategory') scrollablecategory: ElementRef;
  @ViewChild('scrollableContent') scrollableContent: ElementRef;
  @ViewChild('scrollableContent1') scrollableContent1: ElementRef;

  @ViewChildren('scrollByRight') scrollableElements: QueryList<ElementRef>;

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
    'Reality-TV',
  ];

  // filter time acc to currnet time
  getCurrentTimeSlot(): string[] {
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
    let closestTime = `${this.padZero(currentHour % 12 || 12)}:${this.padZero(
      currentMinute < 30 ? 0 : 30
    )} ${currentHour >= 12 ? 'PM' : 'AM'}`;

    const closestIndex = this.scheduleHours.indexOf(closestTime);

    // Return the schedule starting from the closest time slot
    return [
      ...this.scheduleHours.slice(closestIndex),
      ...this.scheduleHours.slice(0, closestIndex),
    ];
  }
  padZero(num: number): string {
    return num < 10 ? '0' + num : num.toString();
  }

  constructor(
    private apiService: CrudService,
    private toaster: ToastrService,
    private router:Router
  ) {}

  ngOnInit(): void {
    console.log(
      'minutes value====>',
      this.calculateWidth(1718841600, 1718845200)
    );
    console.log('currnet time', this.getCurrentTimeSlot());
    this.getScheduleData('Movies');
    this.setDefaultDay();
    // this.startTimer()
  }

  ngAfterViewInit() {
    // Ensure elements are correctly referenced
    this.scrollableElements.forEach((element, index) => {
      console.log(`Element ${index}:`, element.nativeElement);
    });
  }

  showPlaylist() {
    this.playlist_details_show = !this.playlist_details_show;
  }

  scrollLeft(offset: number) {
    if (this.scrollablecategory.nativeElement) {
      this.scrollablecategory.nativeElement.scrollLeft += offset;
    }
  }
  scrollRight(offset: number) {
    if (this.scrollablecategory.nativeElement) {
      this.scrollablecategory.nativeElement.scrollLeft -= offset;
    }
  }

  scrollContent(position: number) {
    if (position != 0) {
      if (
        this.scrollableContent.nativeElement &&
        this.scrollableContent.nativeElement
      ) {
        this.scrollableContent.nativeElement.scrollLeft += position;
        this.scrollableContent1.nativeElement.scrollLeft += position;
      }
    } else {
      this.scrollableContent.nativeElement.scrollLeft = 0;
      this.scrollableContent1.nativeElement.scrollLeft = 0;
    }
    console.log(position);
  }

  // getScheduleProgram: any;
  getScheduleChannelLogo: any;
  getScheduleData(genre?: string) {
    const body = {
      typeurl: 'mpd',
      genre: genre,
    };
    this.apiService.postRequest('auth/epg-guide', body).subscribe({
      next: (res: any) => {
        console.log(res);
        this.getScheduleChannelLogo = this.filterchannelAnd(res.data);
        // this.getScheduleProgram = res.data;
        this.filterPrograms(res);
      },
      error: (err: any) => {
        console.error(err);
      },
    });
  }
  // get the channels log and id
  filterchannelAnd(stations: any) {
    return stations
      .filter((station) => station.prgSvcId !== null)
      .map((station) => ({
        id: station.id,
        image: station.image,
        channel_url: station.channel_url,
        url: station.url,
      }));
  }

  // function to change the time into width
  calculateWidth(start: any, end: any) {
    if (!start || !end) {
      return '';
    }
    const startDate = new Date(start * 1000);
    const endDate = new Date(end * 1000);
    const durationMs = endDate.getTime() - startDate.getTime();
    const durationMinutes = Math.floor(durationMs / (1000 * 60));
    return durationMinutes * 10;
  }

  filteredPrograms: any = [];
  filterPrograms(apiResponse: any) {
    this.filteredPrograms = [];
    const now = Math.floor(Date.now() / 1000);
    console.log('Current time (seconds since epoch):', now);
    apiResponse.data.forEach((service: any) => {
      if (service.prgSvcId) {
        const currentPrograms = service.program.filter(
          (program: any, i: any) => {
            // console.log(i,'start=> ',program.start <= now ,'end=> ', program.end >= now);

            if (
              (+program.start <= +now && +program.end >= +now) ||
              (+now <= +program.start && +now <= +program.end)
            ) {
              return true;
            } else {
              return false;
            }
          }
        );

        if (currentPrograms.length > 0) {
          this.filteredPrograms.push({
            ...service,
            program: currentPrograms,
          });
        }
      }
    });

    console.log('Filtered Programs:', this.filteredPrograms);
  }

  // program-details data 
  programDetailData:any;
  getProgramDetailData(tmsId?: string) {
    const body = {
      "TMSId": tmsId
    };
    this.apiService.postRequest('auth/program-details', body).subscribe({
      next: (res: any) => {
        console.log(res);
        this.showPlaylist()
        this.programDetailData = res.data;
        // localStorage.setItem('playerChannelId',res.data)
        console.log(res);
      },
      error: (err: any) => {
        console.error(err);
      },
    });
  }



  ngOnDestroy(): void {
    this.clearInterval();
  }

  // show live timer
  private intervalId: any;
  startTimer(): void {
    this.intervalId = setInterval(() => {
      this.scrollContent(100);
    }, 60000); // 60000 milliseconds = 1 minute
  }

  clearInterval(): void {
    if (this.intervalId) {
      clearInterval(this.intervalId);
    }
  }

  setChannelId(id:string,url:string){
    localStorage.setItem('playerChannelId',id);
    localStorage.setItem('playerId',url);
    this.router.navigateByUrl('user/playstream')
  }



  // filter according to days
  days: string[] = ['Today', 'Tomorrow'];
  selectedDay: string;

  setDefaultDay() {
    const today = new Date().getDay();
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const todayName = dayNames[today];
    this.days.push(todayName);
    this.selectedDay = todayName;
    
  }


  changeDays(day: string) {
    // Implement the logic to update the schedule data based on the selected day (Today or Tomorrow)
    console.log(`Day changed to: ${day}`);
    // Add logic to filter schedule data based on the day
  
    if(this.selectedDay == 'Tomorrow' || this.selectedDay == 'Today'){
      this.filterProgramsByDay(day);
    }
  }

  filterProgramsByDay(day: string) {
    const today = new Date();
    let targetDate: Date;
    if (day === 'Tomorrow') {
      targetDate = new Date(today);
      targetDate.setDate(today.getDate() + 1);
    } else {
      targetDate = today;
    }
    const targetStart = new Date(targetDate.setHours(0, 0, 0, 0)).getTime() / 1000;
    const targetEnd = new Date(targetDate.setHours(23, 59, 59, 999)).getTime() / 1000;

    this.filteredPrograms = [];
    this.apiService.getRequest('auth/epg-guide').subscribe({
      next: (res: any) => {
        res.data.forEach((service: any) => {
          if (service.prgSvcId) {
            const currentPrograms = service.program.filter((program: any) => {
              const programStart = new Date(program.start * 1000).getTime() / 1000;
              const programEnd = new Date(program.end * 1000).getTime() / 1000;
              return (
                (programStart >= targetStart && programStart <= targetEnd) ||
                (programEnd >= targetStart && programEnd <= targetEnd) ||
                (programStart <= targetStart && programEnd >= targetEnd)
              );
            });

            if (currentPrograms.length > 0) {
              this.filteredPrograms.push({
                ...service,
            program: currentPrograms,
              });
            }
          }
        });
      },
      error: (err: any) => {
        console.error(err);
      },
    });
  }
}
