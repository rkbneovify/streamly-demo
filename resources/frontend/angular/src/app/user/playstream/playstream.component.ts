  import { NgFor } from '@angular/common';
  import {
    Component,
    ElementRef,
    OnDestroy,
    OnInit,
    ViewChild,
  } from '@angular/core';
  import videojs from 'video.js';
  import 'video.js/dist/video-js.css';
  import { CrudService } from '../../services/crud.service';
  import { HttpHeaders } from '@angular/common/http';
  import 'videojs-contrib-eme';
  @Component({
    selector: 'app-playstream',
    standalone: true,
    imports: [NgFor],
    templateUrl: './playstream.component.html',
    styleUrl: './playstream.component.scss',
  })
  export class PlaystreamComponent implements OnInit, OnDestroy {
    @ViewChild('videoPlayer', { static: true }) videoPlayer!: ElementRef;
    player: any;
    playerId!: any;
    tokenkey: any;
    Bearer_token:any;

    constructor(private crud: CrudService) {
      // this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders()).subscribe({
      //   next: (res: any) => {
      //     this.tokenkey = res.data;
      //     console.log(this.tokenkey, 'token key');

      //     console.log(`${this.playerId}?access_key=${this.tokenkey}===8888`);
      //     this.player = videojs(this.videoPlayer.nativeElement, {
      //       autoplay: true,
      //       controls: true,
      //       sources: [
      //         {
      //           src: `https://d2t3s7h1wasu4l.cloudfront.net/CNBC/index.mpd`,
      //           type: 'application/dash+xml',
      //         },
      //       ],
      //       emeHeaders: {
      //         'Authorization': this.Bearer_token
      //       },

      //       emeOptions: {
      //         keySystems: {
      //           'com.widevine.alpha': {
      //             licenseUrl: res?.widevinelicense
      //           },
      //           'com.microsoft.playready': {
      //             licenseUrl: res?.playreadylicense
      //             ,
      //             url:this.tokenkey
      //           }
      //           // Add more DRM key systems as needed
      //         },
      //         // Add other DRM-specific options here
      //       }

      //     });
      //   },
      //   error: (err) => console.log(err),
      // });

      this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders()).subscribe({
        next: (res: any) => {
          this.tokenkey = res.data;
          console.log(this.tokenkey, 'token key');
      
          console.log(`${this.playerId}?access_key=${this.tokenkey}===8888`);
          // this.player = videojs(this.videoPlayer.nativeElement, {
          //   autoplay: true,
          //   controls: true,
          //   sources: [
          //     {
          //       src: `https://d2t3s7h1wasu4l.cloudfront.net/CNBC/index.mpd`,
          //       type: 'application/dash+xml',
          //     },
          //   ],
          //   emeHeaders: {
          //     'Authorization': this.Bearer_token
          //   },
          //   emeOptions: {
          //     keySystems: {
          //       'com.widevine.alpha': {
          //         licenseUrl: res?.widevinelicense
          //       },
          //       'com.microsoft.playready': {
          //         licenseUrl: res?.playreadylicense
          //       }
          //     },
          //     // Add other DRM-specific options here
          //   }
          // });
          this.player = videojs(this.videoPlayer.nativeElement, {
            plugins: {
              eme: {
                keySystems: {
                  'com.widevine.alpha': {
                    licenseUrl: res?.widevinelicense
                  },
                  'com.microsoft.playready': {
                    licenseUrl: res?.playreadylicense
                  }
                  // Add more DRM systems as needed
                }
              }
            },
            sources: [
              {
                src: atob(res.data), // Decode base64-encoded video URL
                type: 'video/mp4'
              }
            ]
          });
        },
        error: (err) => console.log(err),
      });
      

      this.playerId = localStorage.getItem('playerId');

      // this.tokenkey = JSON.parse(localStorage.getItem('deviceInfo') || '')
      
    }

    ngOnInit() {

    }

    getAuthHeaders(): HttpHeaders {
      const tokenData = JSON.parse(
        localStorage.getItem('deviceInfo') || ''
      ).access_token;
      this.Bearer_token= tokenData;
      return new HttpHeaders({
        Authorization: `Bearer ${tokenData}`,
        Accept: 'application/json',
      });
    }

    ngOnDestroy() {
      // Dispose Video.js player when component is destroyed
      if (this.player) {
        this.player.dispose();
      }
    }
  }
