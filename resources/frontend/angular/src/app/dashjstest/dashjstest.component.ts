import {
  Component,
  ElementRef,
  Input,
  OnDestroy,
  OnInit,
  ViewChild,
} from '@angular/core';
// // import * as dashjs from 'dashjs';
// import { CrudService } from '../services/crud.service';
// import { HttpHeaders } from '@angular/common/http';
// import dashjs, { MediaPlayer, MediaPlayerClass } from 'dashjs';
// import { Component, ElementRef, OnDestroy, OnInit, ViewChild } from '@angular/core';
import videojs  from 'video.js';
import 'video.js/dist/video-js.css';
// import 'videojs-contrib-dash';
// import { Component, ElementRef, OnDestroy, OnInit, ViewChild } from '@angular/core';
// import dashjs from 'dashjs';
// import * as dashjs from 'dashjs';
@Component({
  selector: 'app-dashjstest',
  standalone: true,
  imports: [],
  templateUrl: './dashjstest.component.html',
  styleUrl: './dashjstest.component.css',
})
// export class DashjstestComponent 
// implements OnDestroy,OnInit
//  {
//   initPlayer(mpdUrl: string) {
//     const videoElement:any = document.querySelector('video');
//     const player = dashjs.MediaPlayer().create();
//     player.initialize(videoElement, mpdUrl, true);
//     }

//     ngOnInit() {
//       this.initPlayer('https://d36lryb1dm4mg4.cloudfront.net/TNT/index.mpd');
//       }




  // public player!: MediaPlayerClass;
  // // private _src: string='https://d36lryb1dm4mg4.cloudfront.net/TNT/index.mpd';

  // @ViewChild('videoPlayer') videoPlayer!: ElementRef;
  // @Input() autoload: boolean = true;
  // @Input() set src(value: string) {
  // // this._src = value;
  // if (this.player && this.player.isPaused()) {
  // this.play();
  // }
  // }

  // constructor(private crud: CrudService) { }

  // ngOnInit() {
  // this.player = MediaPlayer().create();
  // this.player.initialize(this.videoPlayer.nativeElement, "https://dash.akamaized.net/envivio/EnvivioDash3/manifest.mpd", this.autoload);
  // }

  // ngOnDestroy() {
  // this.player.reset();
  // }

  // public getPlayer(): MediaPlayerClass {
  // return this.player;
  // }

  // public play(): void {
  // this.player.play();
  // }

  // public pause(): void {
  // this.player.pause();
  // }


  // constructor(private elementRef: ElementRef
    // , private crud: CrudService
    // ) {}
  // private _src: string = '	';
  // ngOnInit(): void {
  //   const videoElement =
  //     this.elementRef.nativeElement.querySelector('#videoPlayer');
  //   const player = MediaPlayer().create();
    // const playerConfig = {
    //   keySystemConfigs: {
    //     'com.widevine.alpha': {
    //       serverURL: '',
    //     },
    //   },
    // };
    // player.initialize(videoElement, 'https://dash.akamaized.net/envivio/EnvivioDash3/manifest.mpd', true);
    // this.getFRM();
  // }

  // headers = new HttpHeaders({
  //   'Content-Type': 'application/json',
  //   Authorization:
  //     'Bearer ' +
  //     'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTU5NzM4NDgxNSwiZXhwIjoxNjMzMzg0ODE1LCJuYmYiOjE1OTczODQ4MTUsImp0aSI6ImxwaHo5cURFcEQ4T2xxY2wiLCJzdWIiOjksInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEiLCJuYW1lIjoiU2FoaWwiLCJyb2xlcyI6W3siaWQiOjMsIm5hbWUiOiJ1c2VyIiwiZGlzcGxheV9uYW1lIjoidXNlciIsImRlc2NyaXB0aW9uIjoibm90IGFkbWluIHBlcm1pc2lvbiIsImNyZWF0ZWRfYXQiOm51bGwsInVwZGF0ZWRfYXQiOm51bGwsInBpdm90Ijp7InVzZXJfaWQiOjksInJvbGVfaWQiOjN9fV19.lpsK9RV9qJZYY5LUUzSpdv0FtpMStXtqxH0V2-mmaMU',
  // });

  // getFRM(): void 
  //   this.crud
  //     .getRequest('auth/getdrmkey', { headers: this.headers })
  //     .subscribe({
  //       next: (res) => console.log('DRM ->', res),
  //       error: (err) => console.log('DRM err ->', err),
  //     });
  // }
// }

// export class DashjstestComponent implements OnInit, OnDestroy {
//   @ViewChild('videoPlayer', { static: true }) videoPlayer!: ElementRef;

//   private player: any;

//   ngOnInit(): void {
//     this.player = dashjs.MediaPlayer().create();
//     this.player.initialize(this.videoPlayer.nativeElement, 'URL_TO_YOUR_DASH_MANIFEST.mpd', true);
//   }

//   ngOnDestroy(): void {
//     if (this.player) {
//       this.player.reset();
//     }
//   }
// }

export class DashjstestComponent implements OnInit, OnDestroy {
  @ViewChild('videoPlayer', { static: true }) videoPlayer!: ElementRef;
  player: any;

  ngOnInit() {
    // Initialize Video.js player
    this.player = videojs(this.videoPlayer.nativeElement, {
      autoplay: true,
      controls: true,
      sources: [{
        src: 'https://dash.akamaized.net/akamai/bbb_30fps/bbb_30fps.mpd',
        type: 'application/dash+xml'
      }]
    });
  }

  ngOnDestroy() {
    // Dispose Video.js player when component is destroyed
    if (this.player) {
      this.player.dispose();
    }
  }
}