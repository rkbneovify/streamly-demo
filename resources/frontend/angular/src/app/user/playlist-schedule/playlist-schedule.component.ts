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
    JsonPipe,
    NgClass
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
  isLoading:boolean = false;
  liveCurrentTime: number = 0;
  intervalLiveWidth: any;

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
  dayfilter:string = 'today';
  categories: any;
  // get category list
  getAllCategories(){
  this.isLoading = true;
    this.apiService.getRequest('auth/all-genre').subscribe({
      next:(res:any)=>{
        this.categories=res.data;
        this.categories.unshift({name:'All',image:'../../../assets/dummy-img/All.svg'})
        this.categories.push({name:'Others',image:''})
        this.isLoading = true;
      },
      error:(err:any)=>{
        console.log(err);
  this.isLoading = true;
      }
    })
  }

  // filter time acc to currnet time
  getCurrentTimeDate:any;
  getCurrentTimeDataFirstIndex:any;
  getCurrentTimeSlot(dayFilter?:string) {
  if(dayFilter != 'tomorrow'){
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
  }else{
    this.getCurrentTimeDate = [...this.scheduleHours.slice(this.scheduleHours.indexOf('05:30 AM'))];
  }

  this.getCurrentTimeDataFirstIndex = this.getCurrentTimeDate[0];
  console.log(this.getCurrentTimeDataFirstIndex);

  }
  // 
  padZero(num: number): string {
    return num < 10 ? '0' + num : num.toString();
  }

  constructor(
    private apiService: CrudService,
    private toaster: ToastrService,
    private router:Router,
    private cdr: ChangeDetectorRef
  ) {}
  ngOnInit(): void {
    this.getAllCategories();
    this.getScheduleData('');
    this.getFavoriteList();
    this.getCurrentTimeSlot();
    // this.intervalLiveWidth()
    // setInterval(()=>{
    //   this.liveCurrentTime+=10
    // },1000)
        // Update marginLeft every 1 minute (60,000 milliseconds)
        this.setLiveMinWith();
        if(this.liveCurrentTime){
          this.intervalLiveWidth = setInterval(() => {
            this.liveCurrentTime += 10;
            this.cdr.detectChanges();
          }, 60000); // 60000 milliseconds = 1 minute
        }
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
      this.changeColorBackgroung = true;
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
      this.changeColorBackgroung = false;
    }
  }

  // getScheduleProgram: any;
  // getAllScheduleData:any;
  getScheduleChannelLogo: any;
  // filterProgram
  filteredPrograms: any = [];
  getScheduleData(genre?: string,dayFilter:string=this.dayfilter) {
  this.isLoading = true;
    this.active_categories((genre == '' || genre == 'All' ?'All':genre));
    const body = {
      typeurl: 'mpd',
      genre: genre == 'All'?'':genre,
      day: dayFilter
    };
    this.apiService.postRequest('auth/epg-guide', body).subscribe({
      next: (res: any) => {
        this.getScheduleChannelLogo = this.filterchannelAnd(res.data);
        this.filteredPrograms = res.data;
  this.isLoading = false;
        // this.getAllScheduleData = res.data;
        // this.getScheduleProgram = res.data;
        // this.filterPrograms(res);
      },
      error: (err: any) => {
        console.error(err);
  this.isLoading = false;
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

  setLiveMinWith(){
    const currentTimeIndex = this.getDateTimeWithProvidedTime(this.getCurrentTimeDataFirstIndex).getTime();
    const currentTime = new Date().getTime();
    const timeDifferenceMs = currentTime - currentTimeIndex;
      const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
       this.liveCurrentTime = timeDifferenceMinutes * 10;
       console.log('liveCurrentTime ==> ',this.liveCurrentTime)
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

  calculateWidth(start: any, end: any, index: any) {
    if (!start || !end) {
      return { width: 0, margin_left: 0 };
    }
  
    const startDate = new Date(start * 1000);
    const endDate = new Date(end * 1000);
    const currentTime = this.getDateTimeWithProvidedTime(this.getCurrentTimeDataFirstIndex).getTime();
  
    let width = 0;
    let margin_left = 0;
  
    if (index === 0) {
      if (startDate.getTime() < currentTime) {
        // The event has already started
        if (endDate.getTime() < currentTime) {
          // The event has also ended
          width = 0; // No width because the event has passed
          margin_left = 0;
        } else {
          // The event is currently ongoing
          const durationMs = endDate.getTime() - currentTime;
          const durationMinutes = Math.floor(durationMs / (1000 * 60));
          width = durationMinutes * 10;
          margin_left = 0;
        }
      } else {
        // The event has not started yet
        const durationMs = endDate.getTime() - startDate.getTime();
        const durationMinutes = Math.floor(durationMs / (1000 * 60));
        const timeDifferenceMs = startDate.getTime() - currentTime;
        const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
        width = durationMinutes * 10;
        margin_left = timeDifferenceMinutes * 10;
      }
    } else {
      // For all other indices (not the first one)
      const durationMs = endDate.getTime() - startDate.getTime();
      const durationMinutes = Math.floor(durationMs / (1000 * 60));
      width = durationMinutes * 10;
      margin_left = 0;
    }
  
    return { width, margin_left };
  }
  
  

  getDateTimeWithProvidedTime(timeStr:string) {
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
  
  programDetailData:any;
  programPlayable:boolean=false;
  programChannelId:string;
  programChannelUrl:string;

  getProgramDetailData(tmsId?: string,index?:any,channelId?:string,channelUrl?:string) {
  this.isLoading = true;
    if(index == 0){
      this.programPlayable = true;
      this.programChannelId=channelId;
      this.programChannelUrl=channelUrl;
    }else{
      this.programPlayable = false;
    }
    const body = {
      "TMSId": tmsId
    };
    this.apiService.postRequest('auth/program-details', body).subscribe({
      next: (res: any) => {
        if(!! res?.data){
          this.showPlaylist();
          this.programDetailData = res.data;
        }else{
          this.toaster.error('No Program details found.')
        }

  this.isLoading = false;
  // localStorage.setItem('playerChannelId',res.data)

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

  setChannelId(id:string,url:string){
    localStorage.setItem('playerChannelId',id);
    localStorage.setItem('playerId',url);
    this.router.navigateByUrl('user/playstream')
  }



  // filter according to days
  days: string[] = ['Today', 'Tomorrow'];
  selectedDay: string = 'Today';

  setDefaultDay() {
    const today = new Date().getDay();
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const todayName = dayNames[today];
    this.days.push(todayName);
    this.selectedDay = todayName;
    
  }

  // filter according to today and tommorrow
  changeDays(day: string) {
    this.dayfilter = day != 'Tomorrow'?'today':'tomorrow'
    if(this.selectedDay != 'Tomorrow'){
      this.getScheduleData(this.selected_category,this.dayfilter);
      this.getCurrentTimeSlot(this.dayfilter);
    }else{
      this.getScheduleData(this.selected_category,this.dayfilter);
      this.getCurrentTimeSlot(this.dayfilter);
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
    // const targetStart = new Date(targetDate.setHours(0, 0, 0, 0)).getTime() / 1000;
    // const targetEnd = new Date(targetDate.setHours(23, 59, 59, 999)).getTime() / 1000;

    // this.filteredPrograms = [];
    // this.apiService.getRequest('auth/epg-guide').subscribe({
    //   next: (res: any) => {
    //     res.data.forEach((service: any) => {
    //       if (service.prgSvcId) {
    //         const currentPrograms = service.program.filter((program: any) => {
    //           const programStart = new Date(program.start * 1000).getTime() / 1000;
    //           const programEnd = new Date(program.end * 1000).getTime() / 1000;
    //           return (
    //             (programStart >= targetStart && programStart <= targetEnd) ||
    //             (programEnd >= targetStart && programEnd <= targetEnd) ||
    //             (programStart <= targetStart && programEnd >= targetEnd)
    //           );
    //         });

    //         if (currentPrograms.length > 0) {
    //           this.filteredPrograms.push({
    //             ...service,
    //         program: currentPrograms,
    //           });
    //         }
    //       }
    //     });
    //   },
    //   error: (err: any) => {
    //     console.error(err);
    //   },
    // });
  }

  changeColorBackgroung:boolean= false;
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
    selected_category:string='All';

    active_categories(category:string){
      this.selected_category = category;
    }
}
