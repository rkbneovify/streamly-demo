import { NgFor } from '@angular/common';
  import {
    Component,
    ElementRef,
    OnDestroy,
    OnInit,
    ViewChild,
  } from '@angular/core';
  import { CrudService } from '../../services/crud.service';
  import { HttpHeaders } from '@angular/common/http';
  import videojs from 'video.js';
  import 'video.js/dist/video-js.css';
  import 'videojs-contrib-eme';
  import dashjs from 'dashjs';
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

    constructor(private crud: CrudService,private elementRef: ElementRef,) {
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
      
          console.log(`${this.playerId} ?access_key=${this.tokenkey}===8888`);
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
          // this.player = videojs(this.videoPlayer.nativeElement, {
          //   plugins: {
          //     eme: {
          //       keySystems: {
          //         'com.widevine.alpha': {
          //           licenseUrl: res?.widevinelicense
          //         },
          //         'com.microsoft.playready': {
          //           licenseUrl: res?.playreadylicense
          //         }
          //         // Add more DRM systems as needed
          //       }
          //     }
          //   },
          //   sources: [
          //     {
          //       src: atob(res.data), // Decode base64-encoded video URL
          //       type: 'application/dash+xml'
          //     }
          //   ],
          //   emeHeaders: {
          //     Authorization: this.Bearer_token,
          //   },
          // });
          
          // this.player = videojs(this.videoPlayer.nativeElement, {
          //   preload:"auto",
          //   controls: true,
          //   sources: [
          //     {
          //       src: `https://d2t3s7h1wasu4l.cloudfront.net/CNBC/index.mpd`,
          //       type: 'application/dash+xml',
          //     },
          //   ],
          //   emeHeaders: {
          //     Authorization: this.Bearer_token,
          //   },
          //   emeOptions: {
          //     keySystems: {
          //       'com.widevine.alpha': {
          //         // licenseUrl: res?.widevinelicense,
          //         url: `${res?.widevinelicense +'AcquireLicense'}`,
          //         audioContentType: 'audio/webm; codecs="vorbis"',
          //         videoContentType: 'video/webm; codecs="vp9"',
          //         licenseHeaders: {
          //           'X-AxDRM-Message':'https://wv-keyos.licensekeyserver.com/'
          //         }
          //       },
          //       'com.microsoft.playready': {
          //         licenseUrl: 'https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx',
          //       },
          //       // Add more DRM systems as needed
          //     },
          //     // Add other DRM-specific options here
          //   },
          // });


        },
        error: (err) => console.log(err),
      });
      

      this.playerId = localStorage.getItem('playerId');

      // this.tokenkey = JSON.parse(localStorage.getItem('deviceInfo') || '')
      
    }

    ngOnInit() {
      // this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders()).subscribe({
      //   next: (res: any) => {
      //     this.tokenkey = res.data;
      //     console.log(this.tokenkey, 'token key');
      //               this.player = videojs(this.videoPlayer.nativeElement, {
      //       preload:"auto",
      //       controls: true,
      //       sources: [
      //         {
      //           src: `https://d2t3s7h1wasu4l.cloudfront.net/CNBC/index.mpd`,
      //           type: 'application/dash+xml',
      //         },
      //       ],
      //       emeHeaders: {
      //         Authorization: this.Bearer_token,
      //       },
      //       emeOptions: {
      //         keySystems: {
      //           'com.widevine.alpha': {
      //             // licenseUrl: res?.widevinelicense,
      //             url: `${res?.widevinelicense +'AcquireLicense'}`,
      //             audioContentType: 'audio/webm; codecs="vorbis"',
      //             videoContentType: 'video/webm; codecs="vp9"',
      //             licenseHeaders: {
      //               'X-AxDRM-Message':res?.widevinelicense
      //             }
      //           },
      //           'com.microsoft.playready': {
      //             licenseUrl: res?.playreadylicense,
      //           },
      //           // Add more DRM systems as needed
      //         },
      //         // Add other DRM-specific options here
      //       },
      //     });
      //   },
      //   error: (err) => console.log(err),
      // });

      // const self = this;
      this.getDRMKeys().subscribe((response:any) => {
        if (response.success && response.data) {
          const player = dashjs.MediaPlayer().create();
          const data = atob(response.data);
          player.initialize(this.videoPlayer.nativeElement,this.playerId , true)
          //   keySystems: {
          //     'com.widevine.alpha': {
          //       licenseUrl: response.widevinelicense
          //     },
          //     'com.microsoft.playready': {
          //       licenseUrl: response.playreadylicense
          //     }
          //   }
          // });
        // Configure DRM license URLs
        // player.setProtectionData({
        //   'com.widevine.alpha': { serverURL: response.widevinelicense },
        //   'com.microsoft.playready': { serverURL: response.playreadylicense }
        // });
                // Configure DRM license URLs
                player.setProtectionData({
                  'com.widevine.alpha': {
                    serverURL: response.widevinelicense,
                    httpRequestHeaders: {
                      'customdata': response.data // Assuming message contains the token
                    }
                  }
                });
        } else {
          console.error('Error retrieving DRM authentication key:', response.message);
        }

        // if (response.success && response.data) {
        //   const player = dashjs.MediaPlayer().create();
        //   const data = atob(response.data);
          
        //   // Pass the DASH manifest URL and DRM configuration to initialize
        //   player.initialize(this.videoPlayer.nativeElement, 'https://d2t3s7h1wasu4l.cloudfront.net/CNBC/index.mpd', true);
          
        //   // Configure DRM license URLs
        //   player.setProtectionData({
        //     'com.widevine.alpha': { serverURL: response.widevinelicense },
        //     'com.microsoft.playready': { serverURL: response.playreadylicense }
        //   });
        // } else {
        //   console.error('Error retrieving DRM authentication key:', response.message);
        // }

        // if (response.success && response.data) {
        //   const player = dashjs.MediaPlayer().create();
          
          // Use customManifestLoader to send custom data
          // player.extend('customManifestLoader', function() {
          //   const originalLoad = self.manifestLoader.load.bind(self.manifestLoader);
          //   self.manifestLoader.load = function(url:any, cb:any, customData:any, headers:any) {
          //     const updatedUrl = url + '?customData=' + customData; // Example: Append custom data to URL
          //     originalLoad(updatedUrl, cb, headers);
          //   };
          // }, true); // Set override to true
                  // Use customManifestLoader to send custom data
        // player.extend('customManifestLoader', function() {
        //   const originalLoad = this.manifestLoader.load.bind(this.manifestLoader);
        //   this.manifestLoader.load = function(url:any, cb:any, customData:any, headers:any) {
        //     const updatedUrl = url + '?customData=' + customData; // Example: Append custom data to URL
        //     originalLoad(updatedUrl, cb, headers);
        //   };
        // }, true); // Set override to true
          
          // Pass the DASH manifest URL and DRM configuration to initialize
          // player.initialize(this.videoPlayer.nativeElement, 'https://d2t3s7h1wasu4l.cloudfront.net/CNBC/index.mpd', true);
          
          // Configure DRM license URLs
          // player.setProtectionData({
          //   'com.widevine.alpha': { serverURL: response.widevinelicense },
          //   'com.microsoft.playready': { serverURL: response.playreadylicense },
          //   "httpRequestHeaders": {
          //     "X-AxDRM-Message": licenseToken
          //   }
          // });

                  // Configure DRM license URLs
        // player.setProtectionData({
        //   'com.widevine.alpha': {
        //     serverURL: response.widevinelicense,
        //     httpRequestHeaders: {
        //       'X-AxDRM-Message': response.message // Assuming message contains the token
        //     }
        //   }
        // });
        // } else {
        //   console.error('Error retrieving DRM authentication key:', response.message);
        // }
      });

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

    getDRMKeys():any{
      return this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders());
    }

    ngOnDestroy() {
      // Dispose Video.js player when component is destroyed
      if (this.player) {
        this.player.dispose();
      }
    }
  }
