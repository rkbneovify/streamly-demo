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
import videojs from 'video.js';
import * as base64js from 'base64-js';

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
  totalchannels:string | Number;

  constructor(
    private crud: CrudService,
    private router: Router,
    private cdr: ChangeDetectorRef,
    private elRef: ElementRef
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
    this.videoElement = this.elRef.nativeElement.querySelector('video');
    this.initializePlayer();
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
      console.log('Safari player')
      this.startVideo()
    } else {
      console.log('Chrome player')
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
    console.log('isIOSDevice ==>',isIOSDevice || isSafari);
    return isIOSDevice || isSafari;
  }

  // initializeHLSPlayer() {
  //   this.player = videojs(this.videoPlayer.nativeElement, {
  //     autoplay: true,
  //     controls: true,
  //     sources: [
  //       {
  //         src: 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8', //"https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8"
  //         type: 'application/x-mpegURL'
  //       }
  //     ]
  //   });

  //   // Configure FairPlay DRM for Safari
  //   this.player.ready(() => {
  //     // this.player.eme();
  //     this.player.src({
  //       src: 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8',
  //       type: 'application/x-mpegURL',
  //       keySystems: {
  //         'com.apple.fps.1_0': {
  //           certificateUri: 'https://fairplay.keyos.com/api/v4/getCertificate?certHash=9754e0cfddc682c21216de3eb4d11885.der', // https://fairplay.keyos.com/api/v4/getCertificate?certHash=9754e0cfddc682c21216de3eb4d11885.der
  //           licenseUri: 'https://fairplay.keyos.com/api/v4/getLicense'
  //         }
  //       }
  //     });
  //   });
  // }

//   initializeHLSPlayer() {
//     this.player = videojs(this.videoPlayer.nativeElement, {
//       autoplay: true,
//       controls: true,
//       sources: [
//         {
//           src: 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8',
//           type: 'application/x-mpegURL'
//         }
//       ]
//     });
  
//     // Configure FairPlay DRM for Safari using video.js and HLS.js
//     this.player.ready(() => {
//       // Set up FairPlay DRM configurations
//       this.player.src({
//         src: 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8',
//         type: 'application/x-mpegURL',
//         keySystems: {
//           'com.apple.fps.1_0': {
//             certificateUri: 'https://fairplay.keyos.com/api/v4/getCertificate?certHash=9754e0cfddc682c21216de3eb4d11885.der',
//             licenseUri: 'https://fairplay.keyos.com/api/v4/getLicense',
//             getLicense: (emeOptions, callback) => {
//               const contentId = emeOptions.keyMessage; // Content ID for FairPlay
//               fetch('https://fairplay.keyos.com/api/v4/getLicense', {
//                 method: 'POST',
//                 headers: {
//                   'Content-Type': 'application/octet-stream',
//                   Authorization: `PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDI0LTExLTAxIDA5OjEyOjUxLjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjQtMTEtMDIgMDk6MTI6NTEuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+YjgyMWJkM2ViMDgzZjk3OGNlMzIwM2JjOGI1ZGZiMzk8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+VlVYTWpMenhieGQ4QTUvY1FRY0oyNDdkbjUxYUZnNXVNNWxjT2Z4eVE3ME5neGtIWHhsN2ppS3ROQTVkc3hKdWE1TlhZVnptQXlnb2pNdU9DTnlMc05LR3NFS29qMks3WXVxMnZqY0pzYnVVZnNtZ1JPTWlkaFEzK2k3NlJJMXNQR2RWQlI1dWZsS1ZHcEh4anFWMHVLcXE3QU0xWDRReWJ0djg5WGhDYkU5SzBPS1BmU1hDN2ZyMW5QUHNDYmc3SitEMEFIYkFRZkN6bFhHdk9ubjU2bkNTcm9GNDFWcGRmVE9Gc2NBcVRLcmFiTlNoTHJNOGRnYWUwR3JpUEFMTWRTbmZKK3ZJTlUvNDMwZ3NRSEM2RFJvcWlPR3RML3hlSVNWeExnQm1JdVNtaXZzZnlRaEg0b3VOSDBZczB6bExVRjhkVzlVRDRuU3kvOHRXWVpYU1hnPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg==
// `
//                 },
//                 body: emeOptions.keyMessage // Use keyMessage as the FairPlay challenge
//               })
//               .then(response => response.arrayBuffer())
//               .then(license => callback(null, new Uint8Array(license)))
//               .catch(error => callback(error, null));
//             }
//           }
//         }
//       });
  
//       this.player.on('error', (error) => {
//         console.error('Video.js player error:', error);
//       });
//     });
//   }
  

// initializeHLSPlayer() {
//   this.player = videojs(this.videoPlayer.nativeElement, {
//     autoplay: true,
//     controls: true,
//     sources: [
//       {
//         src: 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8',
//         type: 'application/x-mpegURL'
//       }
//     ]
//   });

//   this.player.ready(() => {
//     const decodedToken = base64js.toByteArray('PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDI0LTExLTAxIDEyOjE2OjA4LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjQtMTEtMDIgMTI6MTY6MDguMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+YmVlMGYyZTZhNzA0NjQzM2Q5YmI2MTJkYmI0MmY2YmM8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+bllONURYUzAvL1BkMk5HN3lCOUp5YTZpTWhpazhpaE5JdHdKM0ZvVXkxbzRGeWw0aDhrQjZ5aEYrSHkxVVc1eUdhOERHaU01WGpUNXJ4R3RXVTVNK05GcXlORTUrb3c3L3Jhb1BTVnptRUF4N01XczQ0eTZod1BVWVJxVDFsSVhrTVA3MHhoQmxOdHkrVXJPbCtkaXFNNjVCeEpPdHpkOGQxbTZCeWlOSTFPaG9Wc3Z2c1dYVkxadEJtcUdrL0hNV1hDR0E2dFM1Tktid1JEa29YS2xZelhxZnh2QU5iVVNXbVNHT2szRXZjTEUyeGgvZWZaUFc3NjkwOGVWMlk0dU9mL1Y3U1pPK1AvcGhYK0JLVFVaaDEzeEN1ek03NkNsamNGWmhONmNRWHJZMXQzbWs1bmMwUG1NRTJvcFJZbnJLTCsxUEw2V2lwWlRwTlgzZEl6c3V3PT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg==');

//     this.player.src({
//       src: 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8',
//       // src: 'https://d36lryb1dm4mg4.cloudfront.net/MTV2/index.m3u8',
//       type: 'application/x-mpegURL',
//       keySystems: {
//         'com.apple.fps.1_0': {
//           certificateUri: 'https://fairplay.keyos.com/api/v4/getCertificate?certHash=49c8c068c152dfa009ef1c44b99410bb.der',
//           // certificateUri: 'https://fp-keyos.licensekeyserver.com/cert/9754e0cfddc682c21216de3eb4d11885.der',
//           licenseUri: 'https://fairplay.keyos.com/api/v4/getLicense',
//           // licenseUri:'https://fp-keyos.licensekeyserver.com/getkey',
//           getLicense: (emeOptions, callback) => {
            
//              fetch('https://fairplay.keyos.com/api/v4/getLicense', {
//             // fetch('https://fp-keyos.licensekeyserver.com/getkey', {
//               method: 'POST', //https://fp-keyos.licensekeyserver.com/getkey
//               headers: {
//                 'Content-Type': 'application/octet-stream',
//                 Authorization: `Bearer ${decodedToken}`
//               },
//               body: emeOptions.keyMessage
//             })
//             .then(response => response.arrayBuffer())
//             .then(license => callback(null, new Uint8Array(license)))
//             .catch(error => callback(error, null));
//           }

//         }
//       }
//     });

//     this.player.on('error', (error) => {
//       console.error('Video.js player error:', error);
//     });
//   });
// }

// private initializePlayer1(): void {
//   this.player = videojs(this.videoPlayer.nativeElement.querySelector('#videoPlayer'), {
//     techOrder: ['html5'],
//     controls: true,
//     autoplay: false,
//     sources: [{ src: 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8', type: 'application/x-mpegURL' }],
//   });

//   this.setupFairPlayDRM();
// }

// private setupFairPlayDRM(): void {
//   if (!this.player) return;

//   // Set up FairPlay DRM settings
//   this.player.ready(() => {
//     this.player.eme();

//     this.player.src({
//       src: 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8',
//       type: 'application/x-mpegURL',
//       keySystems: {
//         'com.apple.fps.1_0': {
//           certificateUri: 'https://fairplay.keyos.com/api/v4/getCertificate?certHash=49c8c068c152dfa009ef1c44b99410bb.der',
//           licenseUri: 'https://fairplay.keyos.com/api/v4/getLicense',
//           getContentId: (url: string) => {
//             // Custom function to generate content ID
//             return 'my_content_id';
//           },
//           getLicense: async (spcMessage: ArrayBuffer) => {
//             try {
//               const response = await fetch('https://fairplay.keyos.com/api/v4/getLicense', {
//                 method: 'POST',
//                 headers: {
//                   'Content-Type': 'application/x-www-form-urlencoded',
//                   'customdata': 'PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDI0LTA5LTI0IDA1OjQ2OjA4LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjQtMDktMjUgMDU6NDY6MDguMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZmI4NTQzZGI0N2ZlNjM0MzdlNTJlNTI2YWY1YTI3Zjk8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+WlowYnRiT2ZyeWt4ZXFhVURJSS95QWNRU3RHU21mSSs0VXpRbXdhVnV3RmpEU2NRU1BiV2JzNHl0dTNLTkpPL2dqUm1GTDVXTWlJcHplRklPOW8vZk1pb2hRMXhQTFJhTXFhYm5BNzNuUGdlOWxrV2huTnR1OTRpRkZOcUVwVmdXZU81bTQ5SC9xSlB2VnhqaGVNRHhPWU0vUTFydzNtc0RIdXk1N3BHRmpGVVRaUGdFb2M0UmY1K3lVMExYYkkreGhOc2tiZ2RBUFBnQmJqRjhQNUF0SmFlVjdzZWUvVFpmbEt1OGNwZFliNVhyK0VUbXdHbDJ4N1ZGT2RMZ045cXo4QTIrTUlJWGM1dWMwcEk4c1JWYnFyU3NmYkQzWUw0NUpsZGZGcUZxZWZOT1dkUTdnSnh0REQ1Z3hHbUtpMDFwamc5SmRuRGNXQU8xaXRpK3JoWlNBPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg==',
//                 },
//                 body: spcMessage,
//               });
//               const licenseData = await response.arrayBuffer();
//               console.log(licenseData);
//               return new Uint8Array(licenseData);
//             } catch (error) {
//               console.error('Error retrieving FairPlay license:', error);
//               return null;
//             }
//           },
//         },
//       },
//     });
//   });
// }
private videoElement!: HTMLVideoElement;
private certificateUrl = 'https://fairplay.keyos.com/api/v4/getCertificate?certHash=49c8c068c152dfa009ef1c44b99410bb';
private licenseUrl = 'https://fairplay.keyos.com/api/v4/getLicense';
 private customData = 'PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDI0LTExLTA1IDA4OjM5OjEwLjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjQtMTEtMDYgMDg6Mzk6MTAuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZGY5MDJmOWE1YzNjMTI5ZGRiZWJmMzA0MDM0MzYwMDU8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+cXdCd2lMVE1GNitBTUpLVUQ2YTVQYXpyeVJjN1lnTllsYVFNT1hORVVyUzlDaWI4OTlGUlVGVGl1bFNRalUyTXlVY3JpWGZaN1Fkd3JzKzF2czFHR2pCc0UxeVh2SXZYMERuK21ackFwSThxWHpzZkpKTUdzNU9GODN4MHRJdUx6Y0tsMWg3RXNZWXF5MVo5YmFwS1g2dnFHYWpXSFJ2dWJ2OGV2Sy9mOFFtQlVlQ0lFKzFNWW5mQjFwcGpxNGNZaGNuY3JSRTV6a0ZWRlFUN1Vjbm1lQ3JBUmtCTHBFaEFON0RheFpkYXJlVGFPUHdnUm5ITmNsSnh2clNwcVNXVHppOE50d0RUeUF4QjNneTIvU3JMWk9TU0J6ZHhNNGwrRDl5dVIxdVVtK3dHZlhKSjBISzlNVkNVTjFpYjVVaDU3TjJDblBMV3gzM21mcktkQSs2V2NRPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=='

 private async loadCertificate(): Promise<ArrayBuffer | null> {
  try {
    const response = await fetch(this.certificateUrl);
    return await response.arrayBuffer();
  } catch (e) {
    console.error(`Could not load certificate at ${this.certificateUrl}`, e);
    return null;
  }
}

private async startVideo(): Promise<void> {
  console.log('url ==>',this.playerId)
  const certificate = await this.loadCertificate();
  if (!certificate) {
    console.error('Certificate could not be loaded.');
    return;
  }

  this.videoElement.src = this.playerId;
  this.videoElement.addEventListener('encrypted', (event) => this.onEncrypted(event, certificate));
  this.videoElement.addEventListener('error', (e) => console.error('Video error:', e));
}

private async onEncrypted(event: MediaEncryptedEvent, certificate: ArrayBuffer): Promise<void> {
  const initDataType = event.initDataType;
  if (initDataType !== 'skd') {
    console.error(`Received unexpected initialization data type "${initDataType}"`);
    return;
  }

  if (!this.videoElement.mediaKeys) {
    const access = await navigator.requestMediaKeySystemAccess('com.apple.fps', [{
      initDataTypes: [initDataType],
      videoCapabilities: [{ contentType: 'application/vnd.apple.mpegurl', robustness: '' }],
      distinctiveIdentifier: 'not-allowed',
      persistentState: 'not-allowed',
      sessionTypes: ['temporary'],
    }]);

    const mediaKeys = await access.createMediaKeys();
    await mediaKeys.setServerCertificate(certificate);
    await this.videoElement.setMediaKeys(mediaKeys);
  }

  const initData = event.initData;
  if (initData) {
    const session = this.videoElement.mediaKeys!.createSession();
    session.addEventListener('message', async (msgEvent) => {
      const license = await this.getLicenseResponse(msgEvent.message);
      if (license) await session.update(license);
    });
    session.generateRequest(initDataType, initData).catch(e => console.error('Failed to generate request:', e));
  }
}

private arrayBufferToBase64(buffer: ArrayBuffer): string {
const bytes = new Uint8Array(buffer);
let binary = '';
for (let i = 0; i < bytes.byteLength; i++) {
    binary += String.fromCharCode(bytes[i]);
}
const base64 = btoa(binary); // Encode the binary string as Base64
console.log('Base64-encoded SPC:', base64); // Log the Base64-encoded SPC
return base64;
}

private async getLicenseResponse(spcMessage: ArrayBuffer): Promise<Uint8Array | null> {
const spcBase64 = this.arrayBufferToBase64(spcMessage); // Convert SPC to Base64

const bodyData = new URLSearchParams({
    spc: spcBase64,
    customdata: this.customData,
}).toString();

console.log('Request Body Data:', bodyData); // Log the body data being sent

try {
    const response = await fetch(this.licenseUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'customdata': this.customData,
        },
        body: bodyData,
    });

    if (!response.ok) {
        console.error(`License request failed with status ${response.status}: ${response.statusText}`);
        return null;
    }

    const responseText = await response.text();
    console.log('Response from License Server:', responseText); // Log the response
    return Uint8Array.from(atob(responseText), c => c.charCodeAt(0));
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
          this.player.on(dashjs.MediaPlayer.events.ERROR, (e: any) => {
            console.error('DASH error:', e);
            alert("Due to some technical reasons, the channel gets interrupted. Kindly try again later.");
            this.router.navigateByUrl('/user/playlistschedule');
          });
          this.player.on('error',(e)=>{
            console.log('Error===>',e)
          })
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
