import { DatePipe, NgClass, NgFor, NgIf } from '@angular/common';
import {
  ChangeDetectorRef,
  Component,
  ElementRef,
  HostListener,
  OnDestroy,
  OnInit,
  ViewChild,
} from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { HttpHeaders } from '@angular/common/http';
import * as dashjs from 'dashjs';
import { NavigationEnd, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import Hls from 'hls.js'; // Import Hls.js
import { Subject, Subscription, catchError, takeUntil, throwError } from 'rxjs';

@Component({
  selector: 'app-playstream',
  standalone: true,
  imports: [NgFor, DatePipe, NgIf, FormsModule, NgClass],
  templateUrl: './playstream.component.html',
  styleUrls: ['./playstream.component.scss'],
})
export class PlaystreamComponent implements OnInit, OnDestroy {
  @ViewChild('videoPlayer', { static: true }) videoPlayer!: ElementRef;
  @ViewChild('customBtn', { static: true }) customBtn!: ElementRef;
  @ViewChild('customBtnNext', { static: true }) customBtnNext!: ElementRef;
  @ViewChild('menuList', { static: true }) menuList!: ElementRef;

  player: dashjs.MediaPlayerClass;

  playerId!: any;
  tokenkey: any;
  Bearer_token: any;
  channelData: any;
  playerChannelId: number = null;
  private subscription: Subscription = new Subscription();
  private routerSubscription: Subscription = new Subscription();
  private destroy$: Subject<void> = new Subject();
  isLoading: boolean = false;
  nextChannelData: any = null;
  prevChannelData: any = null;
  hideControls: boolean = false; // Track visibility of controls
  controlVisibilityTimeout: any; // Timeout for hiding controls
  showMenu = false;
  totalchannels:string | Number;

  constructor(
    private crud: CrudService,
    private router: Router,
    private cdr: ChangeDetectorRef
  ) {
    this.playerId = localStorage.getItem('playerId');
    this.playerChannelId = +localStorage.getItem('playerChannelId');
    if (this.playerChannelId) {
      this.addToRecentChannel(this.playerChannelId);
    }

    this.routerSubscription = this.router.events.subscribe((event) => {
      if (event instanceof NavigationEnd) {
        this.ngOnDestroy();
      }
    });
  }

  ngOnInit() {
    this.getPlayerData();
    this.initializePlayer();
    this.setUpControlVisibility(); // Set up control visibility behavior
    this.getAllChannels();
    this.videoPlayer.nativeElement.addEventListener('play',()=>{
      if(!this.videoPlayer.nativeElement.paused){
        this.showMenu = false;
      }else{
        console.log('Not playing')
      }
    })

    // this.totalchannels = localStorage.getItem('').
  }

  ngAfterViewInit() {
    this.videoPlayer.nativeElement.addEventListener('pause', () => {
      this.showMenu = true;
    });

    if (document.fullscreenElement) {
      const menuElement = this.menuList.nativeElement;

      // Example styles
      menuElement.style.backgroundColor = '#3498db';
      menuElement.style.color = 'white';
      menuElement.style.padding = '10px';
      menuElement.style.position = 'fixed';
      menuElement.style.top = '120px';
    }
  }

  // Function to toggle fullscreen for the video element
  toggleFullScreen() {
    const videoElement = this.videoPlayer.nativeElement;

    if (!document.fullscreenElement) {
      if (videoElement.requestFullscreen) {
        videoElement.requestFullscreen(); // Standard full-screen API
      } else if (videoElement.mozRequestFullScreen) {
        videoElement.mozRequestFullScreen(); // Firefox
      } else if (videoElement.webkitRequestFullscreen) {
        videoElement.webkitRequestFullscreen(); // Chrome and Safari
      } else if (videoElement.msRequestFullscreen) {
        videoElement.msRequestFullscreen(); // IE/Edge
      }
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen(); // Standard API to exit full-screen
      }
    }
  }

  // Listen for fullscreen changes
  @HostListener('document:fullscreenchange')
  @HostListener('document:webkitfullscreenchange')
  @HostListener('document:mozfullscreenchange')
  @HostListener('document:MSFullscreenChange')
  onFullScreenChange() {
    if (document.fullscreenElement) {
      console.log('Video is in fullscreen mode');
    } else {
      console.log('Video has exited fullscreen mode');
    }
  }

  // Function to check the current full-screen status
  checkFullScreenStatus() {
    if (document.fullscreenElement) {
      console.log('Video is currently in fullscreen mode');
    } else {
      console.log('Video is not in fullscreen mode');
    }
  }

  hideMenuList() {
    this.videoPlayer.nativeElement.play();
    this.seekToLiveEdge();
    this.showMenu = false;
  }

  initializePlayer() {
    if (this.isIOS()) {
      this.initializeHLSPlayer();
    } else {
      this.playerDash();
    }
  }
  isIOS(): boolean {
    const userAgent = navigator.userAgent;
    // Check if the device is iOS (iPhone, iPad, or iPod)
    const isIOSDevice = /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream;
    // Check if the browser is Safari (including Safari on macOS, iOS, and excluding Chrome/Android)
    const isSafari = /^((?!chrome|android).)*safari/i.test(userAgent);
    console.log('is Safari => ', isIOSDevice || isSafari);
    return isIOSDevice || isSafari;
  }

  initializeHLSPlayer() {
    if (Hls.isSupported()) {
      const hls = new Hls();
      hls.loadSource(this.playerId); // Assuming playerId is the HLS URL
      hls.attachMedia(this.videoPlayer.nativeElement);
      hls.on(Hls.Events.MANIFEST_PARSED, () => {
        this.videoPlayer.nativeElement.play();
      });
    } else if (
      this.videoPlayer.nativeElement.canPlayType(
        'application/vnd.apple.mpegurl'
      )
    ) {
      this.videoPlayer.nativeElement.src = this.playerId; // Assuming playerId is the HLS URL
      this.videoPlayer.nativeElement.addEventListener('loadedmetadata', () => {
        this.videoPlayer.nativeElement.play();
      });
    }
  }

  isAtLiveEdge: boolean = false;

  playerDash() {
    this.getDRMKeys()
      .pipe(takeUntil(this.destroy$))
      .subscribe((response: any) => {
        if (response.success && response.data) {
          this.player = dashjs.MediaPlayer().create();
          const data = atob(response.data);
          this.player.initialize(
            this.videoPlayer.nativeElement,
            this.playerId,
            true,
            response
          );
          this.player.setProtectionData({
            'com.widevine.alpha': {
              serverURL: response.widevinelicense,
              httpRequestHeaders: {
                customdata: response.data, // Assuming message contains the token
              },
            },
          });

          this.setupPlayerEvents();
          // Listen for the 'pause' event
          this.videoPlayer.nativeElement.addEventListener('pause', () => {
            this.isAtLiveEdge = !this.isAtLiveEdge;
          });
          // Listen for the 'play' event
          this.videoPlayer.nativeElement.addEventListener('play', () => {
            this.seekToLiveEdge();
          });
          // Listen for dash.js errors
          // this.player.on(dashjs.MediaPlayer.events.ERROR, (e: any) => {
          //   console.error('DASH error:', e);
          //   alert("Due to some technical reasons, the channel gets interrupted. Kindly try again later.");
          //   this.router.navigateByUrl('/user/playlistschedule');
          // });
        } else {
          console.error(
            'Error retrieving DRM authentication key:',
            response.message
          );
        }

        if (response.error) {
          console.log('erro', response.error);
        }
      });
  }

  // Function to seek to the live edge when the stream is resumed
  seekToLiveEdge() {
    this.isAtLiveEdge = !this.isAtLiveEdge;
    if (this.player.isDynamic()) {
      const liveEdge = this.player.getCurrentLiveLatency();
      const currentTime = this.player.time();
      const liveDelay = liveEdge - currentTime;
      if (liveDelay > 0) {
        this.player.seek(liveEdge);
        console.log('Seeked to live edge:', liveEdge);
      } else {
        console.log('Already at live edge');
      }
      console.log('Seeked to live edge:');
    }
  }
  goToLive() {
    this.videoPlayer.nativeElement.play();
    this.seekToLiveEdge();
  }
  // Monitor if the player is at the live edge and enable/disable the "Live" button
  checkIfLiveEdge() {
    this.player.on(dashjs.MediaPlayer.events.PLAYBACK_TIME_UPDATED, () => {
      const liveLatency = this.player.getCurrentLiveLatency();
      const currentTime = this.player.time();
      const liveThreshold = 1; // 1 second threshold for considering "live"
      this.isAtLiveEdge = liveLatency - currentTime <= liveThreshold;
    });
  }

  convertTZ(
    date: any,
    tzString = Intl.DateTimeFormat().resolvedOptions().timeZone
  ): Date {
    return new Date(
      new Date(date).toLocaleString('en-US', { timeZone: tzString })
    );
  }

  formatTime(timeStr: string): string {
    // Get current UTC date parts (year, month, day)
    const currentDate = new Date();
    const year = currentDate.getUTCFullYear();
    const month = (currentDate.getUTCMonth() + 1).toString().padStart(2, '0');
    const day = currentDate.getUTCDate().toString().padStart(2, '0');
    // Split the timeStr (HH:mm) into hours and minutes
    const [hours, minutes] = timeStr.split(':').map(Number);
    // Create a new date object for the current day with the given time (in UTC)
    const utcDate = new Date(
      Date.UTC(year, parseInt(month) - 1, parseInt(day), hours, minutes)
    );
    // Convert the UTC date to the local time zone
    const localDate = new Date(
      utcDate.toLocaleString('en-US', {
        timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
      })
    );
    // Format the time in 'en-US' format
    return localDate.toLocaleTimeString('en-US', {
      hour: '2-digit',
      minute: '2-digit',
    });
  }

  getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(
      localStorage.getItem('deviceInfo') || ''
    ).access_token;
    this.Bearer_token = tokenData;
    return new HttpHeaders({
      Authorization: `Bearer ${tokenData}`,
      Accept: 'application/json',
    });
  }

  // getDRMKeys(): any {
  //   return this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders());
  // }

  getDRMKeys(): any {
    return this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders()).pipe(
      catchError((error) => {
        if (error.status === 404) {
          alert('Error 404: The requested resource was not found.');
          this.router.navigateByUrl('/error-page'); // Redirect if needed
        }
        return throwError(() => new Error(error));
      })
    );
  }

  getCurrentFormattedDateTime() {
    const now = new Date();
    const year = now.getUTCFullYear();
    const month = String(now.getUTCMonth() + 1).padStart(2, '0');
    const day = String(now.getUTCDate()).padStart(2, '0');
    const hours = String(now.getUTCHours()).padStart(2, '0');
    const minutes = String(now.getUTCMinutes()).padStart(2, '0');
    const seconds = String(now.getUTCSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
  }

  // getPlayerData() {
  //   this.isLoading = true;
  //   const body = {
  //     channel_id: this.playerChannelId,
  //     current_datetime: this.getCurrentFormattedDateTime(),
  //   };
  //   this.crud.postRequest('auth/player-details', body).subscribe({
  //     next: (res: any) => {
  //       console.log('Player Data', res);
  //       this.channelData = res.data;
  //       this.isLoading = false;
  //       this.nextChannelData = res.data.nextChannel;
  //       this.prevChannelData = res.data.prevChannel;
  //       // if(!this.channelData.channel.prgSvcId && !this.channelData.program_details){
  //       //   alert('Due to some technical reasons, the channel gets interrupted. Kindly try again later.');
  //       //   this.router.navigateByUrl('/user/playlistschedule');
  //       //   console.log('Not Program details');
  //       // }
  //     },
  //     error: (err: any) => {
  //       console.error(err);
  //       this.isLoading = false;
  //     },
  //   });
  // }

  getPlayerData(): Promise<void> {
    // this.isLoading = true;
    const body = {
      channel_id: this.playerChannelId,
      current_datetime: this.getCurrentFormattedDateTime(),
    };

    return new Promise((resolve, reject) => {
      this.crud.postRequest('auth/player-details', body).subscribe({
        next: (res: any) => {
          this.channelData = res?.data;
          this.nextChannelData = res.data.nextChannel;
          this.prevChannelData = res.data.prevChannel;
          // this.isLoading = false;
          resolve(); // Resolve the promise once data is loaded
        },
        error: (err) => {
          console.error(err);
          // this.isLoading = false;
          reject(err); // Reject in case of an error
        },
      });
    });
  }

  setChannelId(id: string, url: string) {
    localStorage.setItem('playerChannelId', id);
    localStorage.setItem('playerId', url);
    // this.router.navigateByUrl('user/playstream');
  }

  addToRecentChannel(playerChannelId: any) {
    try {
      this.crud
        .postRequest('auth/addtorecent', { channelids: playerChannelId })
        .subscribe({
          next: (res) => {
          },
          error: (err) => {
            console.log(err);
          },
        });
    } catch (error) {
      throw new error();
    }
  }

  previousChannel() {
    if (this.prevChannelData) {
      this.switchChannel(this.prevChannelData);
    }
  }

  nextChannel() {
    if (this.nextChannelData) {
      this.switchChannel(this.nextChannelData);
    }
  }
  
  switchChannel(channelData: any) {
    // Save current scroll position before UI changes
    const scrollPosition = window.scrollY;
    this.ngOnDestroy();
    const channelID = channelData.id;
    this.addToRecentChannel(channelID);
    const channelURL = this.isIOS() ? channelData.url : channelData.dash_url;
    this.setChannelId(channelID, channelURL);
    this.playerChannelId = Number(localStorage.getItem('playerChannelId'));
    this.playerId = localStorage.getItem('playerId');
    this.getPlayerData().then(() => {
      this.initializePlayer();
      this.cdr.detectChanges();
      if (scrollPosition > 0) {
        window.scrollTo({
          top: 0,
          behavior: 'smooth',
        });
      }
    });
  }

  setupPlayerEvents() {
    // Show controls on play, and reset the timeout for hiding controls
    this.videoPlayer.nativeElement.addEventListener('play', () => {
      this.hideControls = true;
      this.resetControlVisibilityTimeout();
    });

    // Show controls when paused
    this.videoPlayer.nativeElement.addEventListener('pause', () => {
      this.hideControls = false;
      this.showControls();
    });

    // Reset the timeout to hide controls on mouse movement over the video player
    this.videoPlayer.nativeElement.addEventListener('mousemove', () => {
      this.showControls();
      this.resetControlVisibilityTimeout();
    });

        // Reset the timeout to hide controls on mouse movement over the video player
        this.videoPlayer.nativeElement.addEventListener('touchstart', () => {
          this.showControls();
          this.resetControlVisibilityTimeout();
        });

    // customBtnNext
    // Detect if mouse is over the custom button
    this.customBtn.nativeElement.addEventListener('mouseover', () => {
      this.showControls();
      this.isMouseOverCustomBtn = true; // New flag
      this.cdr.detectChanges(); // Trigger change detection to show controls
    });
    this.customBtnNext.nativeElement.addEventListener('mouseover', () => {
      this.showControls();
      this.isMouseOverCustomBtn = true; // New flag
      this.cdr.detectChanges(); // Trigger change detection to show controls
    });

    // Detect if mouse leaves the custom button
    this.customBtn.nativeElement.addEventListener('mouseleave', () => {
      this.isMouseOverCustomBtn = false; // Reset the flag
      this.resetControlVisibilityTimeout(); // Reset the timeout after mouse leaves
    });
    
        // Detect if mouse leaves the custom button
        this.customBtn.nativeElement.addEventListener('touchend', () => {
          this.isMouseOverCustomBtn = false; // Reset the flag
          this.resetControlVisibilityTimeout(); // Reset the timeout after mouse leaves
        });

    
    this.customBtnNext.nativeElement.addEventListener('mouseleave', () => {
      this.isMouseOverCustomBtn = false; // Reset the flag
      this.resetControlVisibilityTimeout(); // Reset the timeout after mouse leaves
    });
  }

  // Method to show the controls
  showControls() {
    this.hideControls = false;
    if (this.controlVisibilityTimeout) {
      clearTimeout(this.controlVisibilityTimeout);
    }
  }

  checkIfChildContainsClass(
    parentElement: ElementRef,
    className: string
  ): boolean {
    const children = parentElement.nativeElement.children;
    for (let i = 0; i < children.length; i++) {
      if (children[i].classList.contains(className)) {
        return true; // Found a child with the specified class
      }
    }
    return false; // No child contains the class
  }

  // New flag to track if mouse is over the custom button
  isMouseOverCustomBtn: boolean = false;

  resetControlVisibilityTimeout() {
    if (this.controlVisibilityTimeout) {
      clearTimeout(this.controlVisibilityTimeout);
    }

    // Hide controls after 3 seconds of inactivity if the video is playing
    this.controlVisibilityTimeout = setTimeout(() => {
      // Check if mouse is NOT over the custom button
      if (
        !this.isMouseOverCustomBtn &&
        !this.videoPlayer.nativeElement.paused
      ) {
        this.hideControls = true;
        this.cdr.detectChanges(); // Trigger change detection to hide controls
      }
    }, 3000); // Adjust the delay as necessary
  }

  resetControlVisibilityMouseOut() {
    if (this.controlVisibilityTimeout) {
      clearTimeout(this.controlVisibilityTimeout);
    }

    // Hide controls after 3 seconds of inactivity if the video is playing
    this.controlVisibilityTimeout = setTimeout(() => {
      if (!this.videoPlayer.nativeElement.paused) {
        this.hideControls = true;
        this.cdr.detectChanges();
      }
    }, 200); // Adjust this delay as necessary
  }

  // Method to initialize control visibility logic
  setUpControlVisibility() {
    this.videoPlayer.nativeElement.addEventListener('play', () => {
      this.resetControlVisibilityTimeout();
    });

    this.videoPlayer.nativeElement.addEventListener('pause', () => {
      this.showControls(); // Show controls on pause
    });

    this.videoPlayer.nativeElement.addEventListener('mousemove', () => {
      this.showControls(); // Show controls on mouse movement
    });

    this.videoPlayer.nativeElement.addEventListener('mouseleave', () => {
      this.resetControlVisibilityMouseOut();
    });
  }

  ngOnDestroy() {
    if (this.player) {
      this.player.reset(); // Reset the player
      this.player = null; // Ensure it's cleared for the next channel
    }

    this.destroy$.next();
    this.destroy$.complete();

    if (this.subscription) {
      this.subscription.unsubscribe();
    }
  }

  allChannelList:any;
  getAllChannels() {
    const body = {
      typeurl: 'mpd',
    };
    this.crud.postRequest('auth/all-channel-list', body).subscribe({
      next: (res: any) => {
        this.allChannelList = res.data;
        this.isLoading = false;
      },
      error: (err: any) => {
        console.log(err);
        this.isLoading = true;
      },
    });
  }
}
