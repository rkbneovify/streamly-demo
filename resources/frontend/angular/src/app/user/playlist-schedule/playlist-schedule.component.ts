import { animate, style, transition, trigger } from '@angular/animations';
import { JsonPipe, NgClass, NgFor, NgIf, NgStyle } from '@angular/common';
import {
  AfterViewInit,
  ChangeDetectorRef,
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
import {
  DurationFormatPipe,
  DurationISOFormatPipe,
  TruncatePipe,
} from '../../pipe/duration-format.pipe';
import { TimeFormatPipe } from '../../pipe/time-format.pipe';
import { FormsModule } from '@angular/forms';
import { ScheduleFormatterPipe } from '../../pipe/schedule-formatter.pipe';
import { InactivityService } from '../../services/inactive/inactive.service';
import { TabInactivityService } from '../../services/tabInactive/tab-inactive.service';
import { UtilsService } from '../../services/utils/utils.service';
import { ScreenSizeService } from '../../services/screenSize/screen-size.service';
import { Subscription } from 'rxjs';

declare let window: Window;

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
    JsonPipe,
    NgClass,
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
export class PlaylistScheduleComponent
  implements OnInit, AfterViewInit, OnDestroy
{
  playlist_details_show: boolean = false;
  isLoading: boolean = false;
  liveCurrentTime: number = 0;
  intervalLiveWidth: any;
  toggleLeftArrow: boolean = false;
  isMacChrome:boolean = false;
  isMobileChrom:boolean = false;
  currentWidth: number = 0;
  
  private screenSizeSubscription!: Subscription;

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
  dayfilter: string = 'today';
  categories: any;
  isTimePassed:boolean = true;
  // get category list
  getAllCategories() {
    this.isLoading = true;
    this.apiService.getRequest('auth/all-genre').subscribe({
      next: (res: any) => {
        this.categories = res.data;
        // this.categories.unshift({name:'All',image:'../../../assets/dummy-img/All.svg'})
        // this.categories.push({name:'Others',image:''})

        // Add 'All' category at the beginning if it doesn't already exist
        const allCategory = {
          name: 'All',
          image: '../../../assets/dummy-img/All.svg',
        };
        if (!this.categories.some((category) => category.name === 'All')) {
          this.categories.unshift(allCategory);
        }

        // Add 'Others' category at the end if it doesn't already exist
        const othersCategory = { name: 'Others', image: '' };
        if (!this.categories.some((category) => category.name === 'Others')) {
          this.categories.push(othersCategory);
        }
        this.isLoading = true;
      },
      error: (err: any) => {
        console.log(err);
        this.isLoading = true;
      },
    });
  }

  getTwoDaysSchedule(utcStartTime: number, scheduleHours: string[]): string[] {
    // Convert UTC start time to local time
    const localTime = new Date(utcStartTime * 1000); // Convert seconds to milliseconds
    const localHours = localTime.getHours();
    const localMinutes = localTime.getMinutes();

    // Format the local time to match the scheduleHours format
    const formattedTime = `${localHours % 12 || 12}:${localMinutes
      .toString()
      .padStart(2, '0')} ${localHours >= 12 ? 'PM' : 'AM'}`;

    // Find the index of the formatted local time in the scheduleHours array
    const startIndex = scheduleHours.indexOf(formattedTime);
    if (startIndex === -1) {
      throw new Error('Start time not found in the schedule hours.');
    }

    // Splice the array to get data for two days (48 entries)
    return scheduleHours.slice(startIndex, startIndex + 48);
  }

  // filter time acc to currnet time
  startTimeT:number;
  getCurrentTimeDate: any;
  getCurrentTimeDataFirstIndex: any;
  getCurrentTimeSlot(dayFilter?: string) {
    if (dayFilter != 'tomorrow') {
      const now = new Date();
      const currentHour = now.getHours();
      const currentMinute = now.getMinutes();
      let closestTime = `${this.padZero(currentHour % 12 || 12)}:${this.padZero(
        currentMinute < 30 ? 0 : 30
      )} ${currentHour >= 12 ? 'PM' : 'AM'}`;

      const closestIndex = this.scheduleHours.indexOf(closestTime);

      // Return the schedule starting from the closest time slot

      this.getCurrentTimeDate = [
        ...this.scheduleHours.slice(closestIndex),
        ...this.scheduleHours.slice(0, closestIndex),
      ];
    } else {
      this.getCurrentTimeDate = [
        ...this.scheduleHours.slice(this.scheduleHours.indexOf(this.convertUtcToLocalTime(this.startTimeT))),
        ...this.scheduleHours,
      ];
      this.count = 0;
      this.scrollContent(0);
    }

    this.getCurrentTimeDataFirstIndex = this.getCurrentTimeDate[0];
    console.log(this.getCurrentTimeDataFirstIndex);
    console.log(
      'Get Date lenght==>',
      this.getCurrentTimeDate?.length,
      this.getCurrentTimeDate?.length * 150
    );
  }
  //
  padZero(num: number): string {
    return num < 10 ? '0' + num : num.toString();
  }

  constructor(
    private apiService: CrudService,
    private toaster: ToastrService,
    private router: Router,
    private cdr: ChangeDetectorRef,
    private inactivityService: InactivityService,
    private tabInactivityService: TabInactivityService,
    private utilsService:UtilsService,
    private screenSizeService: ScreenSizeService
  ) {}
  ngOnInit(): void {
    this.getAllCategories();
    this.getScheduleData('');
    this.getFavoriteList();
    this.getCurrentTimeSlot();
    this.getScheduleT();
    // this.intervalLiveWidth()
    // setInterval(()=>{
    //   this.liveCurrentTime+=10
    // },1000)
    // Update marginLeft every 1 minute (60,000 milliseconds)
    this.setLiveMinWith();
    if (this.liveCurrentTime) {
      this.intervalLiveWidth = setInterval(() => {
        this.liveCurrentTime += 5;
        this.cdr.detectChanges();
      }, 60000); // 60000 milliseconds = 1 minute
    }
    
    const data = this.generateTimeArray('10:00 AM', '11:30 AM', true);

    // Subscribe to screen width changes
    this.screenSizeSubscription = this.screenSizeService
    .getScreenWidth()
    .subscribe((width) => {
      this.currentWidth = width;
      console.log(`Screen width is: ${this.currentWidth}`);

    });

     this.isMacChrome = navigator.userAgent.includes('Mac') && navigator.userAgent.includes('Chrome') ;
    if (this.isMacChrome) {
      console.log('this is mac chrome');
    }else{
      if( navigator.userAgent.includes('Chrome') && this.currentWidth < 450 ){
      console.log('This is isMobileChrom')
        this.isMobileChrom = true;
      }
    }

    this.utilsService.timeDifferenceInMinutes('02:00 PM')
    console.log(this.getCurrentTimeDate[1]);

    setInterval(() => {
      this.isTimePassed = false;
    }, 1000*60*this.utilsService.timeDifferenceInMinutes(this.getCurrentTimeDate[1]));

    this.tabInactivityService.startTabInactivityCheck();

    console.log('Data ==>',this.convertUtcToLocalTime(1736812800))
  }

  convertUtcToLocalTime(utcTimestamp: number): string {
    // Convert UTC seconds to milliseconds
    const date = new Date(utcTimestamp * 1000);

    // Format the time to the local timezone
    const options: Intl.DateTimeFormatOptions = {
      hour: '2-digit',
      minute: '2-digit',
      hour12: true
    };

    // Get the local time string
    let timeString = date.toLocaleTimeString(undefined, options);

    // Ensure AM/PM is uppercase
    timeString = timeString.replace(/\s*(am|pm)$/i, (match) => match.toUpperCase());

    return timeString;
  }

  ngAfterViewInit() {
    // Ensure elements are correctly referenced
    this.scrollableElements.forEach((element, index) => {
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

  refreshPage(){
    window.location.reload();
  }


  showTomorrowDate: boolean = false;

  isNextDay: boolean = false;
  go_Live: boolean = false;
  count: number = 0;
  isProcessing: boolean = false;
  scrollContent(position: number) {
    // Prevent multiple executions
    if (this.isProcessing) {
      return;
    }
  
    // Early return for invalid `position` values
    if (position === 0 && this.count === 0) {
      this.resetScroll();
      return;
    }
  
    this.isProcessing = true; // Set the processing flag
  
    try {
      if (position !== 0) {
        // Enable controls when scrolling
        this.changeColorBackgroung = true;
        this.toggleLeftArrow = true;
        this.go_Live = true;
  
        if (this.scrollableContent.nativeElement && this.scrollableContent1.nativeElement) {
          // Perform scrolling
          this.scrollableContent.nativeElement.scrollLeft += position;
          this.scrollableContent1.nativeElement.scrollLeft += position;
  
          // Update counters and check if it's the next day
          if (position > 0) {
            this.count++;
          } else {
            this.count--;

          }
  
          // Boundary check for `count`
          if (this.count < 0) {
            this.count = 0;
          } else if (this.count >= this.getCurrentTimeDate.length) {
            this.count = this.getCurrentTimeDate.length - 1;
          }
  
          this.isNextDay = this.checkIfNextDay(this.getCurrentTimeDate[this.count]);
          console.log('isNext data =>', this.isNextDay, this.getCurrentTimeDate[this.count]);
        }
      } else {
        this.resetScroll(); // Reset when position is 0
      }
  
      // Handle edge case: count is 0
      if (this.count === 0) {
        this.resetScroll();
  
        if (!this.isTimePassed) {
          this.refreshPage(); // Refresh if needed
        }
      }
    } catch (error) {
      console.error("Error during scrollContent execution:", error);
    } finally {
      // Ensure `isProcessing` is reset after processing
      setTimeout(() => {
        this.isProcessing = false;
      }, 500);
    }
  }
  
  // Helper method to reset scroll and state
  private resetScroll() {
    if (this.scrollableContent.nativeElement && this.scrollableContent1.nativeElement) {
      this.scrollableContent.nativeElement.scrollLeft = 0;
      this.scrollableContent1.nativeElement.scrollLeft = 0;
    }
    this.changeColorBackgroung = false;
    this.toggleLeftArrow = false;
    this.go_Live = false;
    this.count = 0;
    this.isNextDay = false;
  }


  // getScheduleProgram: any;
  // getAllScheduleData:any;
  getScheduleChannelLogo: any = [];
  // filterProgram
  filteredPrograms: any = [];
  // Assuming 'start' and 'limit' are new parameters for lazy loading
  startIndex: number = 0;
  limit: number = 10;

  getScheduleData(genre?: string, dayFilter: string = this.dayfilter) {
    this.isLoading = true;
    this.active_categories(genre == '' || genre == 'All' ? 'All' : genre);

    const body = {
      typeurl: 'mpd',
      genre: genre == 'All' ? '' : genre,
      day: dayFilter,
      start: this.startIndex,
      limit: this.limit,
    };

    this.apiService.postRequest('auth/epg-guide', body).subscribe({
      next: (res: any) => {
        // Append new data to the existing list for lazy loading
        // console.log('getScheduleChannelLogo res  ===>',res.data);
        this.startTimeT = +res?.start
        this.getScheduleChannelLogo = [...this.filterchannelAnd(res.data)];
        // console.log('getScheduleChannelLogo  ===>',this.getScheduleChannelLogo);
        this.filteredPrograms = [...res.data];
        this.isLoading = false;
      },
      error: (err: any) => {
        console.error(err);
        this.isLoading = false;
      },
    });
  }
  getScheduleT(genre?: string, dayFilter: string = 'tomorrow') {
    this.active_categories(genre == '' || genre == 'All' ? 'All' : genre);

    const body = {
      typeurl: 'mpd',
      genre: genre == 'All' ? '' : genre,
      day: dayFilter,
      start: this.startIndex,
      limit: this.limit,
    };

    this.apiService.postRequest('auth/epg-guide', body).subscribe({
      next: (res: any) => {
        console.log('====>',res.start);
        this.startTimeT = +res?.start
        console.log(this.startTimeT)
      },
      error: (err: any) => {
        console.error(err);
        this.isLoading = false;
      },
    });
  }

  // Listen to scroll event
  onScroll() {
    if (this.isNearBottom()) {
      // Increase the startIndex for the next batch of data
      this.startIndex += this.limit;
      this.getScheduleData(this.selected_category, this.selectedDay);
    }
  }

  // Check if the user has scrolled near the bottom
  isNearBottom(): boolean {
    const scrollable = document.querySelector('.schedule_content');
    return (
      scrollable.scrollHeight - scrollable.scrollTop <=
      scrollable.clientHeight + 50
    );
  }

  // get the channels log and id
  filterchannelAnd(stations: any) {
    return stations
      .filter((station: any) => station.prgSvcId !== null)
      .map((station: any) => ({
        id: station.id,
        image: station.image,
        channel_url: station.channel_url,
        url: station.url,
      }));
  }

  // // function to change the time into width
  // calculateWidth(start: any, end: any,index:any) {
  //   if (!start || !end) {
  //     return {width:0,magin_left:0};
  //   }
  //   const startDate = new Date(start * 1000);
  //   const endDate = new Date(end * 1000);
  //   // const currentTime = new Date().getTime();
  //   const currentTime = this.getDateTimeWithProvidedTime(this.getCurrentTimeDataFirstIndex).getTime();

  //   // Check if start time is less than current time
  //   if (index == 0 && startDate.getTime() < currentTime) {
  //   // console.log('currentTime2 ==>',currentTime2,'currentTime ==>',currentTime)
  //   // const durationMs = endDate.getTime() - startDate.getTime();
  //   const timeDifferenceMs = currentTime - startDate.getTime();
  //   const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
  //   // const durationMs = endDate.getTime() - startDate.getTime();
  //   // const durationMinutes = Math.floor(durationMs / (1000 * 60));
  //   console.log(index,' =  isTrue =>',index == 0 && startDate.getTime() < currentTime,'  timeDifferenceMs ==>',timeDifferenceMs,'  timeDifferenceMinutes => ', timeDifferenceMinutes);
  //   return {width:timeDifferenceMinutes * 10,magin_left:0}
  // }
  // else if(index == 0 && startDate.getTime() > currentTime){
  //   const durationMs = endDate.getTime() - startDate.getTime();
  //   const durationMinutes = Math.floor(durationMs / (1000 * 60));
  //   const timeDifferenceMs = currentTime - startDate.getTime();
  //   const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
  //   return {width:durationMinutes * 10,magin_left:timeDifferenceMinutes}

  // }

  // // If start time is not less than current time, return difference in minutes multiplied by 5
  // // const timeDifferenceMs = currentTime - startDate.getTime();
  // // const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
  // // return timeDifferenceMinutes * 5;

  // const durationMs = endDate.getTime() - startDate.getTime();
  // const durationMinutes = Math.floor(durationMs / (1000 * 60));
  // return {width:durationMinutes * 10,magin_left:0}
  // }

  setLiveMinWith() {
    const currentTimeIndex = this.getDateTimeWithProvidedTime(
      this.getCurrentTimeDataFirstIndex
    ).getTime();
    const currentTime = new Date().getTime();
    const timeDifferenceMs = currentTime - currentTimeIndex;
    const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
    this.liveCurrentTime = timeDifferenceMinutes * 5;
  }

  // calculateWidth(start: any, end: any, index: any) {
  //   if (!start || !end) {
  //     return { width: 0, margin_left: 0 };
  //   }

  //   const startDate = new Date(start * 1000);
  //   const endDate = new Date(end * 1000);
  //   const currentTime = this.getDateTimeWithProvidedTime(this.getCurrentTimeDataFirstIndex).getTime();

  //   let width = 0;
  //   let margin_left = 0;

  //   if (index == 0 && startDate.getTime() < currentTime) {
  //     const timeDifferenceMs = currentTime - startDate.getTime();
  //     const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
  //     width = timeDifferenceMinutes * 10;
  //     margin_left = 0;
  //     console.log(index == 0 && startDate.getTime() < currentTime,'timeDifferenceMinutes=>',timeDifferenceMinutes,{ width, margin_left });
  //   } else if (index == 0 && startDate.getTime() > currentTime) {
  //     const durationMs = endDate.getTime() - startDate.getTime();
  //     const durationMinutes = Math.floor(durationMs / (1000 * 60));
  //     const timeDifferenceMs = startDate.getTime() - currentTime;
  //     const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
  //     width = durationMinutes * 10;
  //     margin_left = timeDifferenceMinutes * 10;
  //     console.log(index == 0 && startDate.getTime() > currentTime,'getTime() > currentTime=> ',timeDifferenceMinutes,{ width, margin_left })
  //   } else {
  //     const durationMs = endDate.getTime() - startDate.getTime();
  //     const durationMinutes = Math.floor(durationMs / (1000 * 60));
  //     width = durationMinutes * 10;
  //     margin_left = 0;
  //     // console.log('Without con =>> ',{ width, margin_left })
  //   }

  //   return { width, margin_left };
  // }

  // calculateWidth(start: any, end: any, index: any) {
  //   if (!start || !end) {
  //     return { width: 0, margin_left: 0 };
  //   }
  //   const startDate = new Date(start * 1000);
  //   const endDate = new Date(end * 1000);
  //   let currentTime = this.getDateTimeWithProvidedTime(
  //     this.getCurrentTimeDataFirstIndex
  //   ).getTime();

  //   // console.log('selectedDay before==>',this.selectedDay);
  //   if (this.selectedDay === 'Tomorrow') {
  //     // Adjust currentTime to 5:30 AM the next day if selectedDay is tomorrow
  //     // console.log('this.selectedDay==>',this.selectedDay);
  //     const tomorrow = new Date();
  //     tomorrow.setDate(tomorrow.getDate() + 1); // Move to the next day
  //     tomorrow.setHours(5, 30, 0, 0); // Set time to 5:30 AM
  //     currentTime = tomorrow.getTime(); // Use this as current time
  //   }
  //   // console.log('selectedDay after==>',this.selectedDay);

  //   let width = 0;
  //   let margin_left = 0;

  //   if (index === 0) {
  //     if (startDate.getTime() < currentTime) {
  //       // The event has already started
  //       if (endDate.getTime() < currentTime) {
  //         // The event has also ended
  //         width = 0; // No width because the event has passed
  //         margin_left = 0;
  //       } else {
  //         // The event is currently ongoing
  //         const durationMs = endDate.getTime() - currentTime;
  //         const durationMinutes = Math.floor(durationMs / (1000 * 60));
  //         width = durationMinutes * 5;
  //         margin_left = 0;
  //       }
  //     } else {
  //       // The event has not started yet
  //       const durationMs = endDate.getTime() - startDate.getTime();
  //       const durationMinutes = Math.floor(durationMs / (1000 * 60));
  //       const timeDifferenceMs = startDate.getTime() - currentTime;
  //       const timeDifferenceMinutes = Math.floor(
  //         timeDifferenceMs / (1000 * 60)
  //       );
  //       width = durationMinutes * 5;
  //       margin_left = timeDifferenceMinutes * 5;
  //     }
  //   } else {
  //     // For all other indices (not the first one)
  //     const durationMs = endDate.getTime() - startDate.getTime();
  //     const durationMinutes = Math.floor(durationMs / (1000 * 60));
  //     width = durationMinutes * 5;
  //     margin_left = 0;
  //   }

  //   return { width, margin_left };
  // }
  calculateWidth(start: any, end: any, index: any) {
    if (!start || !end) {
      return { width: 0, margin_left: 0 };
    }
  
    const startDate = new Date(start * 1000);
    const endDate = new Date(end * 1000);
  
    let currentTime = this.getDateTimeWithProvidedTime(
      this.getCurrentTimeDataFirstIndex
    ).getTime();
  
    // Adjust `currentTime` if `selectedDay` is 'Tomorrow'
    if (this.selectedDay === 'Tomorrow') {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1); // Move to the next day
      tomorrow.setHours(5, 30, 0, 0); // Set to 5:30 AM
      currentTime = tomorrow.getTime();
    }
  
    let width = 0;
    let margin_left = 0;
  
    if (index === 0) {
      if (startDate.getTime() <= currentTime) {
        // The event has already started or is ongoing
        if (endDate.getTime() <= currentTime) {
          // Event has ended
          
          width = 0;
          margin_left = 0;
        } else {
          // Event is currently ongoing
          const remainingDurationMs = endDate.getTime() - currentTime;
          const remainingDurationMinutes = Math.floor(remainingDurationMs / (1000 * 60));
          width = remainingDurationMinutes * 5; // Calculate width based on remaining time
          margin_left = 0;
        }
      } else {
        // Event has not started yet
        const totalDurationMs = endDate.getTime() - startDate.getTime();
        const totalDurationMinutes = Math.floor(totalDurationMs / (1000 * 60));
        const timeDifferenceMs = startDate.getTime() - currentTime;
        const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
  
        width = totalDurationMinutes * 5; // Full width of the event
        margin_left = timeDifferenceMinutes * 5; // Offset for the event
      }
    } else {
      // For all other indices
      const totalDurationMs = endDate.getTime() - startDate.getTime();
      const totalDurationMinutes = Math.floor(totalDurationMs / (1000 * 60));
      width = totalDurationMinutes * 5; // Full width of the event
      margin_left = 0; // No margin for other indices
    }
  
    // Prevent negative widths or margins
    width = Math.max(0, width);
    margin_left = Math.max(0, margin_left);
  
    return { width, margin_left };
  }
  

  getDateTimeWithProvidedTime(timeStr: string) {
    // Get the current date
    const currentDate = new Date();

    // Extract hours and minutes from the provided time string
    const timeParts = timeStr.match(/(\d+):(\d+) (\w+)/);
    let hours = parseInt(timeParts[1]);
    const minutes = parseInt(timeParts[2]);
    const period = timeParts[3];

    // Convert to 24-hour format if necessary
    if (period === 'PM' && hours !== 12) {
      hours += 12;
    } else if (period === 'AM' && hours === 12) {
      hours = 0;
    }

    // Create a new date object with the provided time for the current date in local time
    currentDate.setHours(hours, minutes, 0, 0);
    // console.log(currentDate);
    return currentDate;
  }

  // (timeStr) {
  //   // Get the current date
  //   const currentDate = new Date();

  //   // Extract hours and minutes from the provided time string
  //   const timeParts = timeStr.match(/(\d+):(\d+) (\w+)/);
  //   let hours = parseInt(timeParts[1]);
  //   const minutes = parseInt(timeParts[2]);
  //   const period = timeParts[3];

  //   // Convert to 24-hour format if necessary
  //   if (period === 'PM' && hours !== 12) {
  //     hours += 12;
  //   } else if (period === 'AM' && hours === 12) {
  //     hours = 0;
  //   }

  //   // Create a new date object with the provided time for the current date in local time
  //   currentDate.setHours(hours, minutes, 0, 0);

  //   // Convert the current date with provided time to a UTC timestamp in milliseconds
  //   const utcDate = new Date(Date.UTC(
  //     currentDate.getFullYear(),
  //     currentDate.getMonth(),
  //     currentDate.getDate(),
  //     currentDate.getHours(),
  //     currentDate.getMinutes(),
  //     currentDate.getSeconds(),
  //     currentDate.getMilliseconds()
  //   ));

  //   // Return the timestamp in milliseconds
  //   return utcDate.getTime();
  // }

  // program-details data

  programDetailData: any;
  programPlayable: boolean = false;
  programChannelId: string;
  programChannelUrl: string;

  getProgramDetailData(
    tmsId?: string,
    index?: any,
    channelId?: string,
    channelUrl?: string
  ) {
    this.isLoading = true;
    if (index == 0 && this.selectedDay == 'Today') {
      this.programPlayable = true;
      this.programChannelId = channelId;
      this.programChannelUrl = channelUrl;
    } else {
      this.programPlayable = false;
    }
    const body = {
      TMSId: tmsId,
      channel_id: channelId,
    };
    this.apiService.postRequest('auth/program-details', body).subscribe({
      next: (res: any) => {
        if (!!res?.data) {
          this.showPlaylist();
          this.programDetailData = res.data;
        } else {
          
          this.toaster.error('No Program details found.');
        }

        this.isLoading = false;
        // localStorage.setItem('playerChannelId',res.data);
      },
      error: (err: any) => {
        this.toaster.error(err.message);
        console.error(err);
        this.isLoading = false;
      },
    });
  }

  ngOnDestroy(): void {
    // this.clearInterval();
    if (this.intervalLiveWidth) {
      clearInterval(this.intervalLiveWidth);
    }
    this.tabInactivityService.resetTimer();
  }

  // // show live timer
  // private intervalId: any;
  // startTimer(): void {
  //   this.intervalId = setInterval(() => {
  //     this.scrollContent(100);
  //   }, 60000); // 60000 milliseconds = 1 minute
  // }

  // clearInterval(): void {
  //   if (this.intervalId) {
  //     clearInterval(this.intervalId);
  //   }
  // }

  setChannelId(id: string, url: string) {
    localStorage.setItem('playerChannelId', id);
    localStorage.setItem('playerId', url);
    this.router.navigateByUrl('user/playstream');
  }

  // filter according to days
  days: string[] = ['Today', 'Tomorrow'];
  selectedDay: string = 'Today';

  setDefaultDay() {
    const today = new Date().getDay();
    const dayNames = [
      'Sunday',
      'Monday',
      'Tuesday',
      'Wednesday',
      'Thursday',
      'Friday',
      'Saturday',
    ];
    const todayName = dayNames[today];
    this.days.push(todayName);
    this.selectedDay = todayName;
  }

  // filter according to today and tommorrow
  changeDays(day: string, isDataChange = true) {
    console.log(day);
    this.dayfilter = day != 'Tomorrow' ? 'today' : 'tomorrow';
    this.selectedDay = day != 'Tomorrow' ? 'Today' : 'Tomorrow';
    if (isDataChange) {
      if (this.selectedDay != 'Tomorrow') {
        this.getScheduleData(this.selected_category, this.dayfilter);
        this.getCurrentTimeSlot(this.dayfilter);
        this.scrollContent(0);
      } else {
        this.getScheduleData(this.selected_category, this.dayfilter);
        this.getCurrentTimeSlot(this.dayfilter);
      }
    }
  }

  filterProgramsByDay(day: string) {
    const today = new Date();
    let targetDate: Date;
    if (day === 'Tomorrow') {
      targetDate = new Date(today);
      targetDate.setDate(today.getDate() + 1);

      // getAllScheduleData
    } else {
      targetDate = today;
      const now = Math.floor(Date.now() / 1000);
    }
  }

  changeColorBackgroung: boolean = false;
  favoriteChannels: any;

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
    this.apiService
      .postRequest('auth/addtofavorite', { channelid: channelId })
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels.push(channelId); // Update local favorite list
          this.updateFavoriteChannels(); // Update local storage
          this.toaster.success(res.message);
        }
      });
  }
  // Remove channel from favorites
  removeFavoriteChannel(channelId: number): void {
    // Call API to remove channel from favorites
    this.apiService
      .postRequest('auth/unfavorite', { channelid: channelId })
      .subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels = this.favoriteChannels.filter(
            (id) => id !== channelId
          ); // Update local favorite list

          this.updateFavoriteChannels(); // Update local storage
          this.toaster.success(res.message);
        }
      });
  }

  favListData: any;
  // Fetch favorite list
  getFavoriteList(): void {
    this.isLoading = true;
    const storedFavoriteChannels = localStorage.getItem('favoriteChannels');
    if (storedFavoriteChannels) {
      this.favoriteChannels = JSON.parse(storedFavoriteChannels);
    } else {
      // Fetch from API if not found in local storage
      this.apiService.getRequest('auth/my_favorites').subscribe((res: any) => {
        if (res.response_code === 200) {
          // this.favListData = res;
          this.favoriteChannels = res.data.map((channel: any) => channel.id);
          this.updateFavoriteChannels(); // Update local storage
        }
        this.isLoading = false;
      });
    }
  }
  // Update local storage with current favorite channels
  updateFavoriteChannels(): void {
    localStorage.setItem(
      'favoriteChannels',
      JSON.stringify(this.favoriteChannels)
    );
  }

  // active_categories
  selected_category: string = 'All';
  active_categories(category: string) {
    this.selected_category = category;
  }

  async generateTimeArray(
    currentTime: string = new Date().toLocaleTimeString('en-US', {
      hour: '2-digit',
      minute: '2-digit',
      hour12: true,
    }),
    endTime: string,
    isBoolean: boolean = false
  ): Promise<string[]> {
    const timeArray: string[] = [];

    // Convert the time string to Date objects for easier manipulation
    let start: Date = isBoolean
      ? new Date()
      : new Date(`1970-01-01T${currentTime}`);
    const end: Date = new Date(`1970-01-01T${endTime}`);

    // Function to format time as "hh:mm AM/PM"
    const formatTime = (date: Date): string => {
      return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
      });
    };

    // Loop through time from start to end, adding 15 minutes on each iteration
    while (start <= end) {
      timeArray.push(formatTime(start));
      // Add 15 minutes to the start time
      start = new Date(start.getTime() + 30 * 60000); // 30 minutes = 900,000 ms
    }
    const timeData = await timeArray;
    return timeData;
  }

  checkIfNextDay(timeStr: string): boolean {
    // Get the current date and time
    const currentDate = new Date();
  
    // Extract hours, minutes, and period (AM/PM) from the provided time string
    const timeParts = timeStr.match(/(\d+):(\d+) (\w+)/);
    if (!timeParts) {
      throw new Error('Invalid time format');
    }
  
    let hours = parseInt(timeParts[1], 10);
    const minutes = parseInt(timeParts[2], 10);
    const period = timeParts[3].toUpperCase();
  
    // Convert to 24-hour format if necessary
    if (period === 'PM' && hours !== 12) {
      hours += 12;
    } else if (period === 'AM' && hours === 12) {
      hours = 0;
    }
  
    // Create a date object for the provided time on the current day
    const providedDate = new Date(currentDate);
    providedDate.setHours(hours, minutes, 0, 0);
  
    // If the provided time is earlier than the current time, consider it as the next day
    if (providedDate.getTime() <= currentDate.getTime()) {
      return true; // It is considered as the next day
    }
  
    // Otherwise, it is the same day
    return false;
  }
  
}
