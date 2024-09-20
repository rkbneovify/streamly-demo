import { DatePipe, NgClass, NgFor, NgIf } from '@angular/common';
import {
  Component,
  ElementRef,
  OnDestroy,
  OnInit,
  Renderer2,
  ViewChild,
} from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { HttpHeaders } from '@angular/common/http';
import * as dashjs from 'dashjs';
import { NavigationEnd, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import videojs from 'video.js';
import * as $ from 'jquery';
import Hls from 'hls.js'; // Import Hls.js
import { Subject, Subscription, catchError, takeUntil, throwError } from 'rxjs';

@Component({
  selector: 'app-playstream',
  standalone: true,
  imports: [NgFor, DatePipe, NgIf, FormsModule,NgClass],
  templateUrl: './playstream.component.html',
  styleUrls: ['./playstream.component.scss'],
})
export class PlaystreamComponent implements OnInit, OnDestroy {
  @ViewChild('videoPlayer', { static: true }) videoPlayer!: ElementRef;
  @ViewChild('videoPlayeVjs', { static: true }) videoElement!: ElementRef;
  player: dashjs.MediaPlayerClass;
  playervideojs:any;


  playerId!: any;
  tokenkey: any;
  Bearer_token: any;
  channelData: any;
  playerChannelId: number = null;
  private subscription: Subscription = new Subscription();
  private routerSubscription: Subscription = new Subscription();
  private destroy$: Subject<void> = new Subject();
  isLoading:boolean = false;

  constructor(private crud: CrudService, private router: Router,private renderer:Renderer2) {
    this.playerId = localStorage.getItem('playerId');
    this.playerChannelId = +localStorage.getItem('playerChannelId');
    if (this.playerChannelId) {
      this.addToRecentChannel(this.playerChannelId);
    }

    this.routerSubscription = this.router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        this.ngOnDestroy();
      }
    });

  }
  ngOnInit() {
    this.getPlayerData();
    this.initializePlayer();
  }

  initializePlayer() {
    if (!this.isIOS()) {
      this.playerDash();
    }
  }
  isIOS(): boolean {
    const userAgent = navigator.userAgent;
  
    // Check if the device is iOS (iPhone, iPad, or iPod)
    const isIOSDevice = /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream;
  
    // Check if the browser is Safari (including Safari on macOS, iOS, and excluding Chrome/Android)
    const isSafari = /^((?!chrome|android).)*safari/i.test(userAgent);
  
    return isIOSDevice || isSafari;
  }
  

// ====================== Safari Player Initialization =============
initializeSafariPlayer(dynamicSrc: string): void {
  // Initialize the player
  this.playervideojs = videojs(this.videoElement.nativeElement, {
    preload: true,
    autoplay: true,
    controls: true,
    responsive: true,
    controlBar: {
      liveDisplay: true,
      pictureInPictureToggle: true,
      fullscreenToggle: true,
    },
  });

  this.playervideojs.httpSourceSelector();
  this.playervideojs.eme();

  this.playervideojs.on('ready', () => {
    const show_on_start = true;
    const parent = this.videoElement.nativeElement.parentNode;
    const closeBtn = parent.querySelector('.over-close');
    const overlay = parent.querySelector('.div-over');

    // Close overlay on button click
    this.renderer.listen(closeBtn, 'click', () => {
      overlay.style.display = 'none';
      this.playervideojs.play();
    });

    // Show overlay on Pause event
    this.playervideojs.on('pause', () => {
      if (!this.playervideojs.seeking() && this.playervideojs.paused()) {
        overlay.style.display = 'block';
      }
    });

    // Hide overlay on Play event
    this.playervideojs.on('play', () => {
      overlay.style.display = 'none';
    });

    if (show_on_start) {
      this.playervideojs.pause();
      overlay.style.display = 'block';
    } else {
      overlay.style.display = 'none';
    }

    // Helper functions for base64 encoding/decoding
    const base64DecodeUint8Array = (a: string) => {
      const b = window.atob(a);
      const c = b.length;
      const d = new Uint8Array(new ArrayBuffer(c));
      for (let i = 0; i < c; i++) d[i] = b.charCodeAt(i);
      return d;
    };

    const base64EncodeUint8Array = (a: Uint8Array) => {
      let b = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
      let c = '';
      let k = 0;
      while (k < a.length) {
        let d = a[k++];
        let e = k < a.length ? a[k++] : NaN;
        let f = k < a.length ? a[k++] : NaN;
        let g = d >> 2;
        let h = ((d & 3) << 4) | (e >> 4);
        let i = ((e & 15) << 2) | (f >> 6);
        let j = f & 63;
        if (isNaN(e)) i = j = 64;
        else if (isNaN(f)) j = 64;
        c += b.charAt(g) + b.charAt(h) + b.charAt(i) + b.charAt(j);
      }
      return c;
    };

    const arrayToString = (a: Uint8Array) => {
      const b = new Uint16Array(a.buffer);
      return String.fromCharCode.apply(null, b);
    };

    // Setup FairPlay HLS with dynamic source
    const fairplayHlsSrc = {
      src: dynamicSrc,
      type: 'application/x-mpegURL',
      keySystems: {
        'com.apple.fps.1_0': {
          certificateUri: 'https://fp-keyos.licensekeyserver.com/cert/9754e0cfddc682c21216de3eb4d11885.der',

          getContentId: (emeOptions: any, initData: Uint8Array) => {
            const contentId = arrayToString(initData);
            if (contentId.indexOf('skd://') > -1) {
              return contentId.split('skd://')[1].substr(0, 32);
            }
            throw new Error(
              'Invalid Content ID format. It must follow skd://xxx where xxx is the Key ID in hex format.'
            );
          },

          getLicense: (emeOptions: any, contentId: string, keyMessage: Uint8Array, callback: any) => {
            const data =
              'spc=' + base64EncodeUint8Array(keyMessage) + '&assetId=' + contentId;
            $.ajax({
              url: 'https://fp-keyos.licensekeyserver.com/getkey',
              type: 'POST',
              data: data,
              headers: {
                customdata: '{{ $encoded_auth_xml }}',
              },
              contentType: 'application/x-www-form-urlencoded',
              success: (result: string) => {
                callback(null, base64DecodeUint8Array(result));
              },
              error: (err: any) => {
                callback(err);
              },
            });
          },
        },
      },
    };

    // Set the dynamic source to the player
    this.playervideojs.src(fairplayHlsSrc);

    this.playervideojs.on('play', () => {
      console.log("it's playing bro");
      document.title = 'play...';
    });

    this.playervideojs.on('pause', () => {
      console.log("it's paused bro");
    });
  });
}

// ============ Safari Player End =========

// Usage Example:
// Pass the dynamic source URL when initializing the player
// this.initializeSafariPlayer('https://example.com/path/to/dynamic.m3u8');


  isAtLiveEdge:boolean = false;

  playerDash() {
    this.getDRMKeys().pipe(takeUntil(this.destroy$)).subscribe((response: any) => {
      if (response.success && response.data) {
        this.player = dashjs.MediaPlayer().create();
        const data = atob(response.data);
        this.player.initialize(this.videoPlayer.nativeElement, this.playerId, true, response);
        this.player.setProtectionData({
          'com.widevine.alpha': {
            serverURL: response.widevinelicense,
            httpRequestHeaders: {
              'customdata': response.data // Assuming message contains the token
            }
          }
        });

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
        console.error('Error retrieving DRM authentication key:', response.message);
      }

      if(response.error){
        console.log('erro',response.error);
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
    goToLive(){
      console.log('Live stream resumed',this.isAtLiveEdge);
      this.videoPlayer.nativeElement.play();
        this.seekToLiveEdge();
  
    }
  // Monitor if the player is at the live edge and enable/disable the "Live" button
  checkIfLiveEdge() {
    this.player.on(dashjs.MediaPlayer.events.PLAYBACK_TIME_UPDATED, () => {
      const liveLatency = this.player.getCurrentLiveLatency();
      const currentTime = this.player.time();
      const liveThreshold = 1; // 1 second threshold for considering "live"
      this.isAtLiveEdge = (liveLatency - currentTime) <= liveThreshold;
    });
  }

  convertTZ(date: any, tzString = Intl.DateTimeFormat().resolvedOptions().timeZone): Date {
    return new Date(new Date(date).toLocaleString("en-US", { timeZone: tzString }));
  }

  formatTime(timeStr: string): string {
    // Get current UTC date parts (year, month, day)
    const currentDate = new Date();
    const year = currentDate.getUTCFullYear();
    const month = (currentDate.getUTCMonth() + 1).toString().padStart(2, "0");
    const day = currentDate.getUTCDate().toString().padStart(2, "0");
    // Split the timeStr (HH:mm) into hours and minutes
    const [hours, minutes] = timeStr.split(":").map(Number);
    // Create a new date object for the current day with the given time (in UTC)
    const utcDate = new Date(Date.UTC(year, parseInt(month) - 1, parseInt(day), hours, minutes));
    // Convert the UTC date to the local time zone
    const localDate = new Date(utcDate.toLocaleString('en-US', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone }));
    // Format the time in 'en-US' format
    return localDate.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit" });
  }
  

  showRecommendedChannels(playerURL: string, channelID: any) {
    this.playerId = playerURL;
    this.playerChannelId = channelID;
    console.log('showRecommendedChannels =>', this.playerId, this.playerChannelId);
    if (playerURL && channelID) {
      localStorage.setItem('playerChannelId', channelID);
      localStorage.setItem('playerId', playerURL);
      window.location.reload();
    }
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
        alert("Error 404: The requested resource was not found.");
        this.router.navigateByUrl('/error-page'); // Redirect if needed
      }
      return throwError(() => new Error(error));
    })
  );
}

  ngOnDestroy() {
    if (this.player) {
      this.player.reset();
    }

    // Emit a value to complete the observable and unsubscribe
    this.destroy$.next();
    this.destroy$.complete();

    if (this.subscription) {
      console.log('unSub')
      this.subscription.unsubscribe();
    }
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

  getPlayerData() {
  this.isLoading= true;
    const body = {
      "channel_id": this.playerChannelId,
      "current_datetime": this.getCurrentFormattedDateTime()
    }
    this.crud.postRequest('auth/player-details', body).subscribe({
      next: (res: any) => {
        console.log('Player Data',res);
        this.channelData = res.data;
        this.isLoading= false;

        if(this.isIOS() && this.channelData.channel.url){
          this.initializeSafariPlayer(this.channelData.channel.url);
        }

        // if(!this.channelData.channel.prgSvcId && !this.channelData.program_details){
        //   alert('Due to some technical reasons, the channel gets interrupted. Kindly try again later.');
        //   this.router.navigateByUrl('/user/playlistschedule');
        //   console.log('Not Program details');
        // }
      },
      error: (err: any) => {
        console.error(err);
        this.isLoading= false;
      }
    })
  }

  setChannelId(id: string, url: string) {
    localStorage.setItem('playerChannelId', id);
    localStorage.setItem('playerId', url);
    this.router.navigateByUrl('user/playstream')
  }

  addToRecentChannel(playerChannelId: any) {
    try {
      this.crud.postRequest('auth/addtorecent', { 'channelids': playerChannelId }).subscribe({
        next: (res) => {
          console.log('hello addToRecentChannel =>')
          console.log(res);
        },
        error: (err) => {
          console.log(err)
        }
      })
    } catch (error) {
      throw new error;
    }
  }
}
