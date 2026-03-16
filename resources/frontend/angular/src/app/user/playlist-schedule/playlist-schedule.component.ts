import {
  animate,
  state,
  style,
  transition,
  trigger,
} from '@angular/animations';
import { JsonPipe, NgClass, NgFor, NgIf, NgStyle } from '@angular/common';
import {
  AfterViewInit,
  ChangeDetectorRef,
  Component,
  ElementRef,
  NgZone,
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
import { PlaylistCacheService } from '../../services/playlist-cache/playlist-cache.service';
import { RouteTrackingService } from '../../services/RouteTracking/route-tracking.service';
import { ExtractFirstTwoPipe } from '../../pipe/extract-first-two.pipe';

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
    ExtractFirstTwoPipe,
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

    trigger('scaleAnimation', [
      state('start', style({ transform: 'scale(1)' })),
      state('scaled', style({ transform: 'scale(1)' })),
      transition('void => *', [
        style({ transform: 'scale(0)' }),
        animate('300ms ease-out', style({ transform: 'scale(1.2)' })),
        animate('300ms ease-in', style({ transform: 'scale(1)' })),
      ]),
    ]),
  ],
})
export class PlaylistScheduleComponent
  implements OnInit, AfterViewInit, OnDestroy {
  playlist_details_show: boolean = false;
  isLoading: boolean = false;
  liveCurrentTime: number = 0;
  intervalLiveWidth: any;
  toggleLeftArrow: boolean = false;
  isMacChrome: boolean = false;
  isMobileChrom: boolean = false;
  currentWidth: number = 0;
  private screenSizeSubscription!: Subscription;

  previousRoute: string | null = null;

  @ViewChild('scrollablecategory') scrollablecategory: ElementRef;
  @ViewChild('scrollableContent') scrollableContent: ElementRef;
  @ViewChild('scrollableContent1') scrollableContent1: ElementRef;

  @ViewChildren('scrollByRight') scrollableElements: QueryList<ElementRef>;

  // focus previous
  @ViewChild('channelList', { static: false }) channelList1: ElementRef;

  scheduleHours = [
    '12:00 AM',
    '12:30 AM',
    '1:00 AM',
    '1:30 AM',
    '2:00 AM',
    '2:30 AM',
    '3:00 AM',
    '3:30 AM',
    '4:00 AM',
    '4:30 AM',
    '5:00 AM',
    '5:30 AM',
    '6:00 AM',
    '6:30 AM',
    '7:00 AM',
    '7:30 AM',
    '8:00 AM',
    '8:30 AM',
    '9:00 AM',
    '9:30 AM',
    '10:00 AM',
    '10:30 AM',
    '11:00 AM',
    '11:30 AM',
    '12:00 PM',
    '12:30 PM',
    '1:00 PM',
    '1:30 PM',
    '2:00 PM',
    '2:30 PM',
    '3:00 PM',
    '3:30 PM',
    '4:00 PM',
    '4:30 PM',
    '5:00 PM',
    '5:30 PM',
    '6:00 PM',
    '6:30 PM',
    '7:00 PM',
    '7:30 PM',
    '8:00 PM',
    '8:30 PM',
    '9:00 PM',
    '9:30 PM',
    '10:00 PM',
    '10:30 PM',
    '11:00 PM',
    '11:30 PM',
  ];
  dayfilter: string = 'today';
  categories: any;
  isTimePassed: boolean = true;

  // show_icon play button
  isPlayButtonVisible = false;
  programTMSID: string = '';
  channelID: string = '';

  // get category list
  getAllCategories() {
    this.isLoading = true;
    this.apiService.getRequest('auth/all-genre').subscribe({
      next: (res: any) => {
        this.categories = res.data;
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

        this.cacheService.setCategories(this.categories);
        this.isLoading = true;
      },
      error: (err: any) => {
        console.log(err);
        this.isLoading = true;
      },
    });
  }

  // this is not using anywhere nouse
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
  startTimeT: number;
  getCurrentTimeDate: any;
  getCurrentTimeDataFirstIndex: any;
  getClosetTime: any;
  getCurrentTimeSlot(dayFilter?: string) {
    // Check if current time slot is cached
    const cachedTimeSlot = this.cacheService.getCurrentTimeSlot();
    if (
      cachedTimeSlot &&
      (!dayFilter || cachedTimeSlot.dayFilter === dayFilter)
    ) {
      this.getCurrentTimeDate = cachedTimeSlot.data;
      this.getCurrentTimeDataFirstIndex = cachedTimeSlot.firstIndex;
      return;
    }
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
    let closestTime = `${this.padZero(currentHour % 12 || 12)}:${this.padZero(
      currentMinute < 30 ? 0 : 30
    )} ${currentHour >= 12 ? 'PM' : 'AM'}`;

    closestTime = closestTime.replace(/^0/, '');

    this.getClosetTime = closestTime;

    const closestIndex = this.scheduleHours.indexOf(closestTime);

    if (dayFilter != 'tomorrow') {
      // Current day schedule starting from the closest time slot
      const currentDaySchedule = this.scheduleHours.slice(closestIndex);

      // Next day schedule (full day)
      const nextDaySchedule = this.scheduleHours;

      // Day after tomorrow schedule (full day)

      const thirdDaySchedule = this.scheduleHours.slice(
        0,
        this.scheduleHours.indexOf('12:00 PM') + 1
      );

      // Combine all schedules
      this.getCurrentTimeDate = [
        ...currentDaySchedule,
        ...nextDaySchedule,
        ...thirdDaySchedule,
      ];
    } else {
      // If 'tomorrow' is selected, start from '05:30 AM' and include the next two full days
      this.getCurrentTimeDate = [
        ...this.scheduleHours.slice(this.scheduleHours.indexOf('05:30 AM')),
        ...this.scheduleHours,
        ...this.scheduleHours,
      ];
    }

    this.getCurrentTimeDataFirstIndex = this.getCurrentTimeDate[0];

    // Cache the current time slot data
    this.cacheService.setCurrentTimeSlot({
      dayFilter,
      data: this.getCurrentTimeDate,
      firstIndex: this.getCurrentTimeDataFirstIndex,
    });
    this.totalTomNumber =
      this.calculateIntervalsRemaining(this.getClosetTime) - 1;
  }

  totalTomNumber: any;

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
    private utilsService: UtilsService,
    private screenSizeService: ScreenSizeService,
    private ngZone: NgZone,
    private cacheService: PlaylistCacheService,
    private routeTrackingService: RouteTrackingService
  ) { }
  ngOnInit(): void {
    this.getCurrentTimeSlot();
    this.getFavoriteList();
    this.previousRoute = this.routeTrackingService.getPreviousUrl();
    if (this.previousRoute !== '/user/playstream') {
      this.cacheService.clearCache();
      localStorage.removeItem('programTMSID');
      localStorage.removeItem('showPlayerIcon');
    } else {
      this.programTMSID = localStorage.getItem('programTMSID') || '';
      this.channelID = localStorage.getItem('showPlayerIcon');
    }
    // Check for cached data
    const cachedCategories = this.cacheService.getCategories();
    const cachedSchedule = this.cacheService.getSchedule();
    const filterData = this.cacheService.getFilterData();

    if (cachedCategories) {
      this.categories = cachedCategories; // Load categories from cache
    } else {
      this.getAllCategories(); // Fetch categories if not cached
    }

    if (cachedSchedule && filterData) {
      this.getScheduleChannelLogo = cachedSchedule; // Load schedule from cache
      this.filteredPrograms = filterData;
    } else {
      this.getScheduleData('');
    }

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
      });

    // Subscribe to screen width changes
    this.screenSizeSubscription = this.screenSizeService
      .getScreenWidth()
      .subscribe((width) => {
        this.currentWidth = width;
      });

    this.isMacChrome =
      navigator.userAgent.includes('Mac') &&
      navigator.userAgent.includes('Chrome');
    if (this.isMacChrome) {
    } else {
      if (navigator.userAgent.includes('Chrome') && this.currentWidth < 450) {
        this.isMobileChrom = true;
      }
    }

    this.utilsService.timeDifferenceInMinutes('2:00 PM');

    setInterval(() => {
      this.isTimePassed = false;
    }, 1000 * 60 * this.utilsService.timeDifferenceInMinutes(this.getCurrentTimeDate[1]));

    this.tabInactivityService.startTabInactivityCheck();
  }

  convertUtcToLocalTime(utcTimestamp: number): string {
    // Convert UTC seconds to milliseconds
    const date = new Date(utcTimestamp * 1000);

    // Format the time to the local timezone
    const options: Intl.DateTimeFormatOptions = {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    };

    // Get the local time string
    let timeString = date.toLocaleTimeString(undefined, options);

    // Ensure AM/PM is uppercase
    timeString = timeString.replace(/\s*(am|pm)$/i, (match) =>
      match.toUpperCase()
    );

    return timeString;
  }

  ngAfterViewInit() {
    // Ensure elements are correctly referenced
    this.scrollableElements.forEach((element, index) => { });

    const savedScrollPos = localStorage.getItem('scrollPosition');
    if (savedScrollPos) {
      this.channelList1.nativeElement.scrollTop = parseInt(savedScrollPos, 10);
    }
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

  refreshPage() {
    window.location.reload();
  }

  showTomorrowDate: boolean = false;

  isNextDay: boolean = false;
  go_Live: boolean = false;
  count: number = 0;
  isProcessing: boolean = false;

  onScrollChannelPostion(): void {
    // Store the current scroll position whenever the user scrolls
    localStorage.setItem(
      'scrollPosition',
      this.channelList1.nativeElement.scrollTop.toString()
    );
  }

  scrollContent(position: number, isNextData?: boolean) {
    // alert("position => ");
    if (this.isProcessing) return;
    if (this.shouldResetScroll(position)) return;

    this.isProcessing = true;

    try {
      if (position !== 0) {
        this.handleScroll(position, isNextData);
      } else {
        this.resetScroll();
      }

      this.handlePostScrollActions();
    } catch (error) {
      console.error('Error during scrollContent execution:', error);
    } finally {
      this.resetProcessingFlag();
    }
  }

  // Extracted Method: Check if we should reset the scroll
  private shouldResetScroll(position: number): boolean {
    if (position === 0 && this.count === 0) {
      this.resetScroll();
      return true;
    }
    return false;
  }

  // Extracted Method: Handle scrolling logic
  private handleScroll(position: number, isNextValue?: boolean) {
    console.log('position => ', position);
    this.changeColorBackgroung = true;
    this.toggleLeftArrow = true;
    this.go_Live = true;

    if (
      !this.scrollableContent?.nativeElement ||
      !this.scrollableContent1?.nativeElement
    )
      return;

    // this.scrollableContent.nativeElement.scrollLeft += position;
    // this.scrollableContent1.nativeElement.scrollLeft += position;

    const scrollOffset = position;
    let test = position;
    this.scrollableContent.nativeElement.scrollLeft += scrollOffset;
    this.scrollableContent1.nativeElement.scrollLeft += scrollOffset;

    test += scrollOffset;
    console.log('test => ', test);

    this.updateCount(position, isNextValue);
  }

  // Extracted Method: Update count & boundary conditions
  private updateCount(position: number, isNextValue?: boolean) {
    this.count += position > 0 ? 1 : -1;
    this.count = Math.max(
      0,
      Math.min(this.count, this.getCurrentTimeDate.length - 1)
    );
    // alert(`1st = ${this.count}`);

    this.updateDayState(isNextValue);
  }

  // Extracted Method: Handle isNextDay & UI updates
  private updateDayState(isNextValue: boolean = false) {
    if (isNextValue) {
      this.count = this.totalScroll;
    }
    this.isNextDay = isNextValue
      ? isNextValue
      : this.count > this.totalTomNumber;
    if (this.isNextDay) {
      // this.changeDays('Tomorrow');
    } else {
      this.selectedDay = 'Today';
      setTimeout(() => {
        this.changeDays('Today', false);
        this.getCurrentTimeSlot('today');
        this.cdr.markForCheck();
      }, 100);
    }

    // this.cdr.markForCheck();
  }

  // Extracted Method: Handle actions after scrolling
  private handlePostScrollActions() {
    if (this.count === 0) {
      this.resetScroll();
      if (!this.isTimePassed) {
        this.refreshPage();
      }
    }
  }

  // Extracted Method: Reset processing flag with delay
  private resetProcessingFlag() {
    setTimeout(() => {
      this.isProcessing = false;
    }, 500);
  }

  private resetScroll() {
    if (
      this.scrollableContent.nativeElement &&
      this.scrollableContent1.nativeElement
    ) {
      this.scrollableContent.nativeElement.scrollLeft = 0;
      this.scrollableContent1.nativeElement.scrollLeft = 0;
    }
    this.changeColorBackgroung = false;
    this.toggleLeftArrow = false;
    this.go_Live = false;
    this.count = 0;
    this.isNextDay = false;
  }

  // getAllScheduleData:any;
  getScheduleChannelLogo: any = [];
  // filterProgram
  filteredPrograms: any = [];
  // Assuming 'start' and 'limit' are new parameters for lazy loading
  startIndex: number = 0;
  limit: number = 10;

  getScheduleData(genre?: string, dayFilter: string = this.dayfilter) {
    setTimeout(() => {
      this.isLoading = true;
      this.cdr.markForCheck(); // Force UI update
    }, 100);
    this.cdr.markForCheck();

    const zipcode =
      JSON.parse(localStorage.getItem('userLocation'))?.zipcode || '';
    const body = {
      typeurl: 'mpd',
      genre: genre === 'All' ? '' : genre,
      start: this.startIndex,
      limit: this.limit,
      zipcode: zipcode,
    };

    this.apiService.postRequest('auth/epg-guide', body).subscribe({
      next: (res: any) => {
        this.ngZone.run(() => {
          this.getScheduleChannelLogo = [...this.filterchannelAnd(res.data)];
          this.filteredPrograms = [...res.data];
          this.cacheService.setFilterData(this.filteredPrograms);
          this.cacheService.setSchedule(this.getScheduleChannelLogo);
          this.isLoading = false;
          this.cdr.detectChanges();
        });
      },
      error: (err: any) => {
        console.error(err);

        this.ngZone.run(() => {
          this.isLoading = false;
          this.cdr.detectChanges();
        });


      },
    });
  }

  changeCategory(genre?: string) {
    this.cacheService.clearCache();
    this.getScheduleData(genre);
    this.active_categories(genre);
    this.changeDays('Today');
    this.scrollContent(0);
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
    return stations.map((station: any) => ({
      id: station.id,
      image: station.image,
      channel_url: station.channel_url,
      url: station.url,
      premium: station.premium,
      phx_local: station.phx_local
    }));
  }

  setLiveMinWith() {
    const currentTimeIndex = this.getDateTimeWithProvidedTime(
      this.getCurrentTimeDataFirstIndex
    ).getTime();
    const currentTime = new Date().getTime();
    const timeDifferenceMs = currentTime - currentTimeIndex;
    const timeDifferenceMinutes = Math.floor(timeDifferenceMs / (1000 * 60));
    this.liveCurrentTime = timeDifferenceMinutes * 5;
  }

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
          const remainingDurationMinutes = Math.floor(
            remainingDurationMs / (1000 * 60)
          );
          width = remainingDurationMinutes * 5; // Calculate width based on remaining time
          margin_left = 0;
        }
      } else {
        // Event has not started yet
        const totalDurationMs = endDate.getTime() - startDate.getTime();
        const totalDurationMinutes = Math.floor(totalDurationMs / (1000 * 60));
        const timeDifferenceMs = startDate.getTime() - currentTime;
        const timeDifferenceMinutes = Math.floor(
          timeDifferenceMs / (1000 * 60)
        );

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

  isCurrentTimeBetween(startTime: number, endTime: number): boolean {
    // Convert Unix timestamps (seconds) to milliseconds
    const startDate = new Date(startTime * 1000);
    const endDate = new Date(endTime * 1000);
    const currentDate = new Date();

    // Check if current time is between start and end times
    return currentDate >= startDate && currentDate <= endDate;
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
    return currentDate;
  }

  programDetailData: any;
  programPlayable: boolean = false;
  programChannelId: string;
  programChannelUrl: string;

  getProgramDetailData(
    tmsId?: string,
    index?: any,
    channelId?: string,
    channelUrl?: string,
    programTMSID?: string,
    start?: number,
    end?: number
  ) {
    this.isLoading = true;
    if (this.isCurrentTimeBetween(start, end) && this.selectedDay == 'Today') {
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
          this.programTMSID = programTMSID;
          this.channelID = '';
        } else {
          this.toaster.error('No Program details found.');
        }

        this.isLoading = false;
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

  setChannelId(id: string, url: string, ischannel: boolean = true) {
    localStorage.setItem('playerChannelId', id);
    if (ischannel) {
      localStorage.removeItem('programTMSID');
      this.programTMSID = '';
      localStorage.setItem('showPlayerIcon', id);
    } else {
      localStorage.setItem('programTMSID', this.programTMSID);
      localStorage.removeItem('showPlayerIcon');
    }
    localStorage.setItem('programTMSID', this.programTMSID);
    localStorage.setItem('playerId', url);
    this.router.navigateByUrl('user/playstream', {
      state: { channelId: id, playerUrl: url },
    });
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
  totalScroll: number = 0;
  changeDays(day: string, isDataChange = true) {
    this.dayfilter = day != 'Tomorrow' ? 'today' : 'tomorrow';
    this.selectedDay = day != 'Tomorrow' ? 'Today' : 'Tomorrow';
    this.totalScroll = +this.calculateIntervalsRemaining(this.getClosetTime);
    if (isDataChange) {
      if (this.selectedDay != 'Tomorrow') {
        this.getCurrentTimeSlot(this.dayfilter);
        this.scrollContent(0);
      } else {
        if (!this.isNextDay) {
          if (this.count != 0) {
            this.isNextDay = true;
            this.scrollContent((this.totalScroll - this.count) * 150, true);
          }
          if (this.count == 0) {
            this.count =
              +this.calculateIntervalsRemaining(this.getClosetTime) - 1;
            console.log('count => ', this.count);
            this.isNextDay = true;
            this.scrollContent(this.totalScroll * 150);
          }
        }
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
  getFavoriteList(): void {
    this.isLoading = true;

    // Check if favorite list is cached
    const cachedFavorites = this.cacheService.getFavoriteList();
    if (cachedFavorites) {
      this.favoriteChannels = cachedFavorites;
      this.isLoading = false;
    } else {
      const storedFavoriteChannels = localStorage.getItem('favoriteChannels');
      if (storedFavoriteChannels) {
        this.favoriteChannels = JSON.parse(storedFavoriteChannels);
        this.cacheService.setFavoriteList(this.favoriteChannels); // Cache data
        this.isLoading = false;
      } else {
        // Fetch from API if not cached
        this.apiService
          .getRequest('auth/my_favorites')
          .subscribe((res: any) => {
            if (res.response_code === 200) {
              this.favoriteChannels = res.data.map(
                (channel: any) => channel.id
              );
              this.updateFavoriteChannels(); // Update local storage
              this.cacheService.setFavoriteList(this.favoriteChannels); // Cache data
            }
            this.isLoading = false;
          });
      }
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

  // nouse
  async generateTimeArray(
    currentTime: string = new Date().toLocaleTimeString('en-US', {
      hour: 'numeric',
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
        hour: 'numeric',
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
    if (
      providedDate.getTime() <= currentDate.getTime() &&
      this.totalTomNumber <= this.count
    ) {
      return true; // It is considered as the next day
    }
    // Otherwise, it is the same day
    return false;
  }

  calculateIntervalsRemaining(currentTime: string): number {
    // Parse the input time
    const timeParts = currentTime.match(/(\d+):(\d+) (AM|PM)/);
    if (!timeParts) {
      throw new Error('Invalid time format. Please use "hh:mm AM/PM".');
    }

    let [_, hoursStr, minutesStr, period] = timeParts;
    let hours = parseInt(hoursStr, 10);
    const minutes = parseInt(minutesStr, 10);

    // Convert 12-hour format to 24-hour format
    if (period === 'PM' && hours < 12) {
      hours += 12;
    }
    if (period === 'AM' && hours === 12) {
      hours = 0;
    }

    // Calculate the total minutes since the start of the day
    const currentTotalMinutes = hours * 60 + minutes;

    // Total minutes in a day
    const totalMinutesInDay = 24 * 60;

    // Calculate the remaining minutes
    const remainingMinutes = totalMinutesInDay - currentTotalMinutes;

    // Calculate the number of 30-minute intervals
    const intervals = Math.ceil(remainingMinutes / 30);

    return intervals;
  }
}
