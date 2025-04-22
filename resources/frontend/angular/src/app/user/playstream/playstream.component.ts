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
  @ViewChild('customBtnprev', { static: true }) customBtnprev!: ElementRef;

  @ViewChild('menuList', { static: true }) menuList!: ElementRef;

  player: any;

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
  totalchannels: string | Number;
  isMiniPlayer:boolean = false;

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

      // Add before existing code
  // window.addEventListener('beforeunload', () => {
  //   localStorage.setItem('shouldAutoPlay', 'true');
  // });

    if (!this.isIOS()) {
      this.getPlayerData();
    }
    this.setUpControlVisibility(); // Set up control visibility behavior
    this.getAllChannels();
    this.videoPlayer.nativeElement.addEventListener('play', () => {
      if (!this.videoPlayer.nativeElement.paused) {
        this.showMenu = false;
      } else {
        console.log('Not playing');
      }
    });

    // this.totalchannels = localStorage.getItem('').
  }

  ngAfterViewInit() {
    this.videoElement = this.videoPlayer.nativeElement;
    if (this.videoElement) {
      this.initializePlayer();
      this.videoElement.addEventListener(
        'error',
        this.handleVideoError.bind(this)
      );
      console.log('start');
    }

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

      // Add after video element initialization
  // if (localStorage.getItem('shouldAutoPlay') === 'true') {
  //   setTimeout(() => {
  //     try {
  //       // this.showMenu = true;
  //       // this.videoElement.;
  //       // console.log(this.videoElement.play);
  //       localStorage.removeItem('shouldAutoPlay');
  //     } catch (err) {
  //       console.error('Refresh autoplay failed:', err);
  //     }
  //   }, 1000);
  // }
  }

  private handleVideoError(event: Event) {
    const error = (event.target as HTMLVideoElement).error;
    if (error) {
      switch (error.code) {
        case MediaError.MEDIA_ERR_ABORTED:
          console.error('Video playback was aborted.');
          break;
        case MediaError.MEDIA_ERR_NETWORK:
          console.error('A network error caused the video download to fail.');
          break;
        case MediaError.MEDIA_ERR_DECODE:
          console.error(
            'The video playback was aborted due to a corruption issue or because the video used features your browser did not support.'
          );
          break;
        case MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED:
          console.error(
            'The video could not be loaded, either because the server or network failed or because the format is not supported.'
          );
          break;
        default:
          console.error('An unknown error occurred.');
          break;
      }
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
      console.log('Safari player');
      this.initializePlayertesr();
    } else {
      console.log('Chrome player');
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
    console.log('isIOSDevice ==>', isIOSDevice || isSafari);
    return isIOSDevice || isSafari;
  }

  private videoElement!: HTMLVideoElement;
  private certificateUrl =
    'https://fairplay.keyos.com/api/v4/getCertificate?certHash=49c8c068c152dfa009ef1c44b99410bb';
  private licenseUrl = 'https://fairplay.keyos.com/api/v4/getLicense';
  private playbackUrl =
    'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8';
  // private customData = 'PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDI0LTExLTA1IDA4OjM5OjEwLjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjQtMTEtMDYgMDg6Mzk6MTAuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZGY5MDJmOWE1YzNjMTI5ZGRiZWJmMzA0MDM0MzYwMDU8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+cXdCd2lMVE1GNitBTUpLVUQ2YTVQYXpyeVJjN1lnTllsYVFNT1hORVVyUzlDaWI4OTlGUlVGVGl1bFNRalUyTXlVY3JpWGZaN1Fkd3JzKzF2czFHR2pCc0UxeVh2SXZYMERuK21ackFwSThxWHpzZkpKTUdzNU9GODN4MHRJdUx6Y0tsMWg3RXNZWXF5MVo5YmFwS1g2dnFHYWpXSFJ2dWJ2OGV2Sy9mOFFtQlVlQ0lFKzFNWW5mQjFwcGpxNGNZaGNuY3JSRTV6a0ZWRlFUN1Vjbm1lQ3JBUmtCTHBFaEFON0RheFpkYXJlVGFPUHdnUm5ITmNsSnh2clNwcVNXVHppOE50d0RUeUF4QjNneTIvU3JMWk9TU0J6ZHhNNGwrRDl5dVIxdVVtK3dHZlhKSjBISzlNVkNVTjFpYjVVaDU3TjJDblBMV3gzM21mcktkQSs2V2NRPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=='

  private initializePlayertesr() {
    // Fetch DRM Key and Video Source in parallel
    Promise.all([this.getDRMKeys().toPromise(), this.getPlayerData()])
      .then(([drmKeyRes, playerDataRes]) => {
        const authKey: any = drmKeyRes.data; // Ensure this matches your API response
        const videoSrc: any = playerDataRes.data.channel.url; // Ensure this matches your API response
        console.log(authKey, videoSrc);
        this.startVideo(authKey, videoSrc);
      })
      .catch((error) => {
        console.error('Error initializing player:', error);
      });
  }

  private async loadCertificate(): Promise<ArrayBuffer | null> {
    try {
      const response = await fetch(this.certificateUrl);
      return await response.arrayBuffer();
    } catch (e) {
      console.error(`Could not load certificate at ${this.certificateUrl}`, e);
      return null;
    }
  }

  private async startVideo(authKey: any, videoSrc: any): Promise<void> {
    console.log('step 1');
    const certificate = await this.loadCertificate();
    if (!certificate) {
      console.error('Certificate could not be loaded.');
      return;
    }
    console.log('video url link ==> ', videoSrc);
    console.log('video element ==>', this.videoElement.src);
    this.videoElement.src = videoSrc;
    this.videoElement.addEventListener('encrypted', (event) =>
      this.onEncrypted(event, certificate, authKey, videoSrc)
    );
    this.videoElement.addEventListener('error', (e) => {
      console.error('Video error:', e);
      alert(
        'Due to some technical reasons, the channel gets interrupted. Kindly try again later.'
      );
      this.router.navigateByUrl('/user/playlistschedule');
    });

    console.log('working');
    this.setupPlayerEvents();
  }
  private async onEncrypted(
    event: MediaEncryptedEvent,
    certificate: ArrayBuffer,
    authKey: any,
    videoSrc: any
  ): Promise<void> {
    const initDataType = event.initDataType;
    if (initDataType !== 'skd') {
      console.error(
        `Received unexpected initialization data type "${initDataType}"`
      );
      return;
    }

    if (!this.videoElement.mediaKeys) {
      const access = await navigator.requestMediaKeySystemAccess(
        'com.apple.fps',
        [
          {
            initDataTypes: [initDataType],
            videoCapabilities: [
              { contentType: 'application/vnd.apple.mpegurl', robustness: '' },
            ],
            distinctiveIdentifier: 'not-allowed',
            persistentState: 'not-allowed',
            sessionTypes: ['temporary'],
          },
        ]
      );

      const mediaKeys = await access.createMediaKeys();
      await mediaKeys.setServerCertificate(certificate);
      await this.videoElement.setMediaKeys(mediaKeys);
    }

    const initData = event.initData;
    if (initData) {
      const contentId = this.extractContentIdFromInitData(initData);
      const session = this.videoElement.mediaKeys!.createSession();
      session.addEventListener('message', async (msgEvent) => {
        const license = await this.getLicenseResponse(
          msgEvent.message,
          contentId,
          authKey,
          videoSrc
        );
        if (license) await session.update(license);
      });
      session
        .generateRequest(initDataType, initData)
        .catch((e) => console.error('Failed to generate request:', e));
    }
  }
  private extractContentIdFromInitData(initData: ArrayBuffer): string {
    const uint8Array = new Uint8Array(initData);
    const initDataStr = new TextDecoder().decode(uint8Array);

    // Extract the part after "skd://"
    const contentIdMatch = initDataStr.match(/^skd:\/\/(.+)/);
    if (contentIdMatch && contentIdMatch[1]) {
      const contentId = contentIdMatch[1];
      console.log('Extracted Content ID:', contentId);
      return contentId;
    }

    console.warn('Content ID not found in initData.');
    return '';
  }
  private arrayBufferToBase64(buffer: ArrayBuffer): string {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
      binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary);
  }
  private async getLicenseResponse(
    spcMessage: ArrayBuffer,
    contentId: string,
    authKey: any,
    videoSrc: any
  ): Promise<Uint8Array | null> {
    const spcBase64 = this.arrayBufferToBase64(spcMessage);

    const bodyData = `spc=${spcBase64}&assetId=${contentId}`;
    console.log('Request Body Data:', bodyData);

    try {
      const response = await fetch(this.licenseUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          customdata: authKey, // Use dynamic customData here
        },
        body: bodyData,
      });

      if (!response.ok) {
        console.error(
          `License request failed with status ${response.status}: ${response.statusText}`
        );
        return null;
      }

      const responseText = await response.text();
      console.log('Response from License Server:', responseText);
      return Uint8Array.from(atob(responseText), (c) => c.charCodeAt(0));
    } catch (error) {
      console.error('License request failed due to exception:', error);
      return null;
    }
  }
  // ==============end of fairplay==============
  isAtLiveEdge: boolean = false;

  playerDash() {
    this.getDRMKeys()
      .pipe(takeUntil(this.destroy$))
      .subscribe((response: any) => {
        if (response.success && response.data) {
          this.player = dashjs.MediaPlayer().create();
          const data = atob(response.data);
          this.player.initialize(
            this.videoPlayer?.nativeElement,
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
          if (this.videoPlayer.nativeElement) {
            this.setupPlayerEvents();
          }
          // Listen for the 'pause' event
          this.videoPlayer.nativeElement.addEventListener('pause', () => {
            this.isAtLiveEdge = !this.isAtLiveEdge;
          });
          // Listen for the 'play' event
          this.videoPlayer.nativeElement.addEventListener('play', () => {
            this.seekToLiveEdge();
          });
          // Listen for dash.js errors
          this.player.on(dashjs.MediaPlayer.events.ERROR, (e: any) => {
            console.error('DASH error:', e);
            alert(
              'Due to some technical reasons, the channel gets interrupted. Kindly try again later.'
            );
            this.router.navigateByUrl('/user/playlistschedule');
          });
          this.player.on('error', (e) => {
            console.log('Error===>', e);
          });
        } else {
          console.error(
            'Error retrieving DRM authentication key:',
            response.message
          );
        }

        if (response.error) {
          console.log('erro', response.error);
        }

              // Add play() after initialization
      setTimeout(() => {
        try {
          this.videoPlayer.nativeElement.play();
        } catch (err) {
          console.error('Dash autoplay failed:', err);
        }
      }, 1000);

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

  getPlayerData(): Promise<any> {
    // this.isLoading = true;
  const zipcode = JSON.parse(localStorage.getItem('userLocation'))?.zipcode || '';

    const body = {
      channel_id: this.playerChannelId,
      current_datetime: this.getCurrentFormattedDateTime(),
      zipcode:zipcode
    };

    return new Promise((resolve, reject) => {
      this.crud.postRequest('auth/player-details', body).subscribe({
        next: (res: any) => {
          this.channelData = res?.data;
          this.nextChannelData = res.data.nextChannel;
          this.prevChannelData = res.data.prevChannel;
          // this.isLoading = false;
          resolve(res); // Resolve the promise once data is loaded
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
    const zipcode = JSON.parse(localStorage.getItem('userLocation'))?.zipcode || '';
    try {
      this.crud
        .postRequest('auth/addtorecent', { channelids: playerChannelId ,zipcode})
        .subscribe({
          next: (res) => {},
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

  toggleFullScreen1(): void {
    const container = document.getElementById('play_container_top');

    console.log('!document.fullscreenElement =>',!document.fullscreenElement)
    if (!document.fullscreenElement) {
      container?.requestFullscreen();
    } else {
      document.exitFullscreen();
    }
  }

  switchChannel(channelData: any) {
    // Save current scroll position before UI changes
    console.log('channel change');
    const scrollPosition = window.scrollY;
    this.ngOnDestroy();
    const channelID = channelData.id;
    this.addToRecentChannel(channelID);
    const channelURL = this.isIOS() ? channelData.url : channelData.dash_url;
    this.setChannelId(channelID, channelURL);
    this.playerChannelId = Number(localStorage.getItem('playerChannelId'));
    this.playerId = localStorage.getItem('playerId');

    if (this.isIOS()) {
      this.initializePlayertesr();
      window.location.reload();
    } else {
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
  }

  setupPlayerEvents() {
    console.log('Hello bro');
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
      console.log('Hii over');
      this.showControls();
      this.isMouseOverCustomBtn = true; // New flag
      this.cdr.detectChanges(); // Trigger change detection to show controls
    });
    this.customBtnNext.nativeElement.addEventListener('mouseover', () => {
      console.log('customBtnNext');
      this.showControls();
      this.isMouseOverCustomBtn = true; // New flag
      this.cdr.detectChanges(); // Trigger change detection to show controls
    });

    // customBtnprev
    this.customBtnprev?.nativeElement.addEventListener('mouseover', () => {
      console.log('customBtnprev');
      this.showControls();
      this.isMouseOverCustomBtn = true; // New flag
      this.cdr.detectChanges(); // Trigger change detection to show controls
    });

    // Detect if mouse leaves the custom button
    this.customBtn?.nativeElement.addEventListener('mouseleave', () => {
      console.log('customBtn leave');
      console.log('Move out');
      this.isMouseOverCustomBtn = false; // Reset the flag
      this.resetControlVisibilityTimeout(); // Reset the timeout after mouse leaves
    });

    // Detect if mouse leaves the custom button
    this.customBtn?.nativeElement.addEventListener('touchend', () => {
      this.isMouseOverCustomBtn = false; // Reset the flag
      this.resetControlVisibilityTimeout(); // Reset the timeout after mouse leaves
    });

    this.customBtnNext.nativeElement.addEventListener('mouseleave', () => {
      console.log('customBtnNext leave');
      this.isMouseOverCustomBtn = false; // Reset the flag
      this.resetControlVisibilityTimeout(); // Reset the timeout after mouse leaves
    });

    // customBtnprev
    this.customBtnprev.nativeElement.addEventListener('mouseleave', () => {
      console.log('customBtnprev leave');
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

  allChannelList: any;
  getAllChannels() {
    const zipcode = JSON.parse(localStorage.getItem('userLocation'))?.zipcode || '';
    const body = {
      typeurl: 'mpd',
      zipcode:zipcode
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
