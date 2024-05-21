import { JsonPipe, NgClass, NgFor } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../../services/crud.service';
import { HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-all-channel',
  standalone: true,
  imports: [NgFor,NgClass,JsonPipe],
  templateUrl: './all-channel.component.html',
  styleUrl: './all-channel.component.scss'
})
export class AllChannelComponent implements OnInit{
  Bearer_token:any;

  data:any = {
    "success": true,
    "data": [
      {
        "id": 1,
        "prgSvcId": 61340,
        "callSign": "TNTPHD",
        "stationName": "Turner Network TV HD (Pacific)",
        "image": "TNT.png",
        "url": "https://d36lryb1dm4mg4.cloudfront.net/TNT/index.mpd",
        "logo": "http://127.0.0.1:8000/uploads/channellogo/TNT.png",
        "genrename": [
          "Others"
        ],
        "gracenote_data": {
          "prgSvcId": "61340",
          "updateId": "74713015064",
          "updateDate": "2024-04-22T12:25:45Z",
          "name": "TNT HD (Pacific)",
          "videoQuality": {
            "signalType": "Digital",
            "truResolution": "HD 1080i",
            "videoType": "HDTV"
          },
          "timeZone": "Pacific Observing",
          "callSign": "TNTPHD",
          "enhancedCallSign": "TNT HD",
          "URL": "www.tntdrama.com",
          "assets": {
            "asset": [
              {
                "assetId": "s42642_h3_aa",
                "lastModified": "2020-04-06T19:30:10Z",
                "type": "image/png",
                "width": "360",
                "height": "270",
                "primary": "true",
                "category": "Parent Logo",
                "ratio": "4:3",
                "URI": "assets/s42642_h3_aa.png"
              },
              {
                "assetId": "s42642_h3_ba",
                "lastModified": "2020-04-06T19:30:11Z",
                "type": "image/png",
                "width": "360",
                "height": "270",
                "primary": "true",
                "category": "Parent Logo",
                "ratio": "4:3",
                "URI": "assets/s42642_h3_ba.png"
              },
              {
                "assetId": "s42642_ld_h8_ac",
                "lastModified": "2020-04-06T22:05:11Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - dark",
                "ratio": "16:9",
                "URI": "assets/s42642_ld_h8_ac.png"
              },
              {
                "assetId": "s42642_lw_h8_ac",
                "lastModified": "2020-04-07T01:15:16Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - white",
                "ratio": "16:9",
                "URI": "assets/s42642_lw_h8_ac.png"
              },
              {
                "assetId": "s42642_ll_h8_ac",
                "lastModified": "2020-04-07T01:15:16Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - light",
                "ratio": "16:9",
                "URI": "assets/s42642_ll_h8_ac.png"
              },
              {
                "assetId": "s42642_lg_h8_ac",
                "lastModified": "2020-04-07T01:15:17Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - gray",
                "ratio": "16:9",
                "URI": "assets/s42642_lg_h8_ac.png"
              },
              {
                "assetId": "s42642_ld_h15_ac",
                "lastModified": "2020-04-07T01:20:14Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - dark",
                "ratio": "4:3",
                "URI": "assets/s42642_ld_h15_ac.png"
              },
              {
                "assetId": "s42642_lg_h15_ac",
                "lastModified": "2020-04-07T01:20:14Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - gray",
                "ratio": "4:3",
                "URI": "assets/s42642_lg_h15_ac.png"
              },
              {
                "assetId": "s42642_ll_h15_ac",
                "lastModified": "2020-04-07T01:20:15Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - light",
                "ratio": "4:3",
                "URI": "assets/s42642_ll_h15_ac.png"
              },
              {
                "assetId": "s42642_lw_h15_ac",
                "lastModified": "2020-04-07T01:20:17Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - white",
                "ratio": "4:3",
                "URI": "assets/s42642_lw_h15_ac.png"
              }
            ]
          }
        }
      },
      {
        "id": 2,
        "prgSvcId": 75003,
        "callSign": "DJCHP",
        "stationName": "Disney Junior US (Pacific)",
        "image": "DJCH.png",
        "url": "https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.mpd",
        "logo": "http://127.0.0.1:8000/uploads/channellogo/DJCH.png",
        "genrename": [
          "Others"
        ],
        "gracenote_data": {
          "prgSvcId": "75003",
          "updateId": "75115778512",
          "updateDate": "2024-05-01T12:07:15Z",
          "name": "Disney Junior (Pacific)",
          "videoQuality": {
            "signalType": "Digital",
            "videoType": "SDTV"
          },
          "timeZone": "Pacific Observing",
          "callSign": "DJCHP",
          "enhancedCallSign": "Disney Jr",
          "URL": null,
          "assets": {
            "asset": [
              {
                "assetId": "s74796_lw_h15_aa",
                "lastModified": "2015-07-08T01:03:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - white",
                "ratio": "4:3",
                "URI": "assets/s74796_lw_h15_aa.png"
              },
              {
                "assetId": "s74796_lg_h15_aa",
                "lastModified": "2015-07-08T01:03:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - gray",
                "ratio": "4:3",
                "URI": "assets/s74796_lg_h15_aa.png"
              },
              {
                "assetId": "s74796_ll_h8_ac",
                "lastModified": "2019-10-16T19:41:28Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - light",
                "ratio": "16:9",
                "URI": "assets/s74796_ll_h8_ac.png"
              },
              {
                "assetId": "s74796_lg_h8_aa",
                "lastModified": "2019-10-16T19:41:51Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - gray",
                "ratio": "16:9",
                "URI": "assets/s74796_lg_h8_aa.png"
              },
              {
                "assetId": "s74796_lw_h8_aa",
                "lastModified": "2019-10-16T19:42:02Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - white",
                "ratio": "16:9",
                "URI": "assets/s74796_lw_h8_aa.png"
              },
              {
                "assetId": "s74796_ld_h8_ab",
                "lastModified": "2019-10-16T19:42:03Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - dark",
                "ratio": "16:9",
                "URI": "assets/s74796_ld_h8_ab.png"
              },
              {
                "assetId": "s74796_h3_aa",
                "lastModified": "2018-05-07T19:45:00Z",
                "type": "image/png",
                "width": "360",
                "height": "270",
                "primary": "true",
                "category": "Parent Logo",
                "ratio": "4:3",
                "URI": "assets/s74796_h3_aa.png"
              },
              {
                "assetId": "s74796_ld_h15_ab",
                "lastModified": "2018-05-07T19:45:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - dark",
                "ratio": "4:3",
                "URI": "assets/s74796_ld_h15_ab.png"
              },
              {
                "assetId": "s74796_ll_h15_ac",
                "lastModified": "2018-05-07T19:45:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - light",
                "ratio": "4:3",
                "URI": "assets/s74796_ll_h15_ac.png"
              }
            ]
          }
        }
      },
      {
        "id": 3,
        "prgSvcId": 60179,
        "callSign": "FNCHD",
        "stationName": "Fox News Channel HD",
        "image": "FNC.png",
        "url": "https://d36lryb1dm4mg4.cloudfront.net/FNC/index.mpd",
        "logo": "http://127.0.0.1:8000/uploads/channellogo/FNC.png",
        "genrename": [
          "Others"
        ],
        "gracenote_data": {
          "prgSvcId": "60179",
          "updateId": "75115895731",
          "updateDate": "2024-05-01T12:10:15Z",
          "name": "Fox News Channel HD",
          "videoQuality": {
            "signalType": "Digital",
            "truResolution": "HD 1080p",
            "videoType": "HDTV"
          },
          "timeZone": "Eastern Observing",
          "callSign": "FNCHD",
          "enhancedCallSign": "FoxNews HD",
          "URL": "www.foxnews.com",
          "assets": {
            "asset": [
              {
                "assetId": "s60179_ld_h15_ab",
                "lastModified": "2017-09-14T18:12:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Source Logo - dark",
                "ratio": "4:3",
                "URI": "assets/s60179_ld_h15_ab.png"
              },
              {
                "assetId": "s60179_lg_h15_ab",
                "lastModified": "2017-09-14T18:13:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Source Logo - gray",
                "ratio": "4:3",
                "URI": "assets/s60179_lg_h15_ab.png"
              },
              {
                "assetId": "s60179_ll_h15_ab",
                "lastModified": "2017-09-14T18:13:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Source Logo - light",
                "ratio": "4:3",
                "URI": "assets/s60179_ll_h15_ab.png"
              },
              {
                "assetId": "s60179_lw_h15_ab",
                "lastModified": "2017-09-14T18:13:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Source Logo - white",
                "ratio": "4:3",
                "URI": "assets/s60179_lw_h15_ab.png"
              },
              {
                "assetId": "s60179_h3_aa",
                "lastModified": "2017-09-14T20:52:00Z",
                "type": "image/png",
                "width": "360",
                "height": "270",
                "primary": "true",
                "category": "Logo",
                "ratio": "4:3",
                "URI": "assets/s60179_h3_aa.png"
              },
              {
                "assetId": "s60179_lw_h8_ab",
                "lastModified": "2019-10-16T19:42:25Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Source Logo - white",
                "ratio": "16:9",
                "URI": "assets/s60179_lw_h8_ab.png"
              },
              {
                "assetId": "s60179_lg_h8_ab",
                "lastModified": "2019-10-16T20:00:28Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Source Logo - gray",
                "ratio": "16:9",
                "URI": "assets/s60179_lg_h8_ab.png"
              },
              {
                "assetId": "s60179_ll_h8_ab",
                "lastModified": "2019-10-16T20:00:44Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Source Logo - light",
                "ratio": "16:9",
                "URI": "assets/s60179_ll_h8_ab.png"
              },
              {
                "assetId": "s60179_ld_h8_ab",
                "lastModified": "2019-10-16T20:20:48Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Source Logo - dark",
                "ratio": "16:9",
                "URI": "assets/s60179_ld_h8_ab.png"
              }
            ]
          }
        }
      },
      {
        "id": 4,
        "prgSvcId": 57390,
        "callSign": "SCIHD",
        "stationName": "Science Channel HD",
        "image": "SCIENCE.png",
        "url": "https://d1jomefbq4775j.cloudfront.net/Science/index.mpd",
        "logo": "http://127.0.0.1:8000/uploads/channellogo/SCIENCE.png",
        "genrename": [
          "Others"
        ],
        "gracenote_data": {
          "prgSvcId": "57390",
          "updateId": "72734700774",
          "updateDate": "2024-03-11T16:43:03Z",
          "name": "Science Channel HD",
          "videoQuality": {
            "signalType": "Digital",
            "truResolution": "HD 1080i",
            "videoType": "HDTV"
          },
          "timeZone": "Eastern Observing",
          "callSign": "SCIHD",
          "enhancedCallSign": "Science HD",
          "URL": "www.sciencechannel.com/",
          "assets": {
            "asset": [
              {
                "assetId": "s57390_lw_h15_ab",
                "lastModified": "2016-12-22T21:20:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Source Logo - white",
                "ratio": "4:3",
                "URI": "assets/s57390_lw_h15_ab.png"
              },
              {
                "assetId": "s57390_lg_h15_ac",
                "lastModified": "2018-01-02T17:22:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Source Logo - gray",
                "ratio": "4:3",
                "URI": "assets/s57390_lg_h15_ac.png"
              },
              {
                "assetId": "s57390_ll_h15_ac",
                "lastModified": "2018-01-02T17:22:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Source Logo - light",
                "ratio": "4:3",
                "URI": "assets/s57390_ll_h15_ac.png"
              },
              {
                "assetId": "s57390_ld_h15_ac",
                "lastModified": "2018-01-02T17:23:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Source Logo - dark",
                "ratio": "4:3",
                "URI": "assets/s57390_ld_h15_ac.png"
              },
              {
                "assetId": "s57390_h3_aa",
                "lastModified": "2018-01-03T22:04:00Z",
                "type": "image/png",
                "width": "360",
                "height": "270",
                "primary": "true",
                "category": "Logo",
                "ratio": "4:3",
                "URI": "assets/s57390_h3_aa.png"
              },
              {
                "assetId": "s57390_lg_h8_ac",
                "lastModified": "2019-10-16T20:41:15Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Source Logo - gray",
                "ratio": "16:9",
                "URI": "assets/s57390_lg_h8_ac.png"
              },
              {
                "assetId": "s57390_ll_h8_ac",
                "lastModified": "2019-10-16T20:41:16Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Source Logo - light",
                "ratio": "16:9",
                "URI": "assets/s57390_ll_h8_ac.png"
              },
              {
                "assetId": "s57390_ld_h8_ac",
                "lastModified": "2019-10-16T20:41:17Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Source Logo - dark",
                "ratio": "16:9",
                "URI": "assets/s57390_ld_h8_ac.png"
              },
              {
                "assetId": "s57390_lw_h8_ab",
                "lastModified": "2019-10-16T20:41:19Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Source Logo - white",
                "ratio": "16:9",
                "URI": "assets/s57390_lw_h8_ab.png"
              }
            ]
          }
        }
      },
      {
        "id": 5,
        "prgSvcId": 18151,
        "callSign": "TOONP",
        "stationName": "Cartoon Network (Pacific)",
        "image": "CARTOON.png",
        "url": "https://d36lryb1dm4mg4.cloudfront.net/TOON/index.mpd",
        "logo": "http://127.0.0.1:8000/uploads/channellogo/CARTOON.png",
        "genrename": [
          "Kids"
        ],
        "gracenote_data": {
          "prgSvcId": "18151",
          "updateId": "73281371293",
          "updateDate": "2024-03-18T12:28:33Z",
          "name": "Cartoon Network (Pacific)",
          "videoQuality": {
            "signalType": "Digital",
            "videoType": "SDTV"
          },
          "timeZone": "Pacific Observing",
          "callSign": "TOONP",
          "enhancedCallSign": "Cartoon",
          "URL": "www.cartoonnetwork.com",
          "assets": {
            "asset": [
              {
                "assetId": "s12131_h3_aa",
                "lastModified": "2013-09-06T17:11:00Z",
                "type": "image/png",
                "width": "360",
                "height": "270",
                "primary": "true",
                "category": "Parent Logo",
                "ratio": "4:3",
                "URI": "assets/s12131_h3_aa.png"
              },
              {
                "assetId": "s12131_h3_ba",
                "lastModified": "2013-09-06T17:11:00Z",
                "type": "image/png",
                "width": "360",
                "height": "270",
                "primary": "true",
                "category": "Parent Logo",
                "ratio": "4:3",
                "URI": "assets/s12131_h3_ba.png"
              },
              {
                "assetId": "s12131_ll_h15_aa",
                "lastModified": "2015-08-17T00:41:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - light",
                "ratio": "4:3",
                "URI": "assets/s12131_ll_h15_aa.png"
              },
              {
                "assetId": "s12131_ld_h15_aa",
                "lastModified": "2015-08-17T00:41:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - dark",
                "ratio": "4:3",
                "URI": "assets/s12131_ld_h15_aa.png"
              },
              {
                "assetId": "s12131_lw_h15_aa",
                "lastModified": "2015-08-17T00:42:00Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - white",
                "ratio": "4:3",
                "URI": "assets/s12131_lw_h15_aa.png"
              },
              {
                "assetId": "s12131_lg_h8_ad",
                "lastModified": "2023-07-10T14:05:38Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - gray",
                "ratio": "16:9",
                "URI": "assets/s12131_lg_h8_ad.png"
              },
              {
                "assetId": "s12131_lg_h15_ad",
                "lastModified": "2023-07-10T14:05:41Z",
                "type": "image/png",
                "width": "3200",
                "height": "2400",
                "primary": "true",
                "category": "Parent Source Logo - gray",
                "ratio": "4:3",
                "URI": "assets/s12131_lg_h15_ad.png"
              },
              {
                "assetId": "s12131_lw_h8_ac",
                "lastModified": "2021-04-29T13:56:14Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - white",
                "ratio": "16:9",
                "URI": "assets/s12131_lw_h8_ac.png"
              },
              {
                "assetId": "s12131_ll_h8_ac",
                "lastModified": "2021-04-29T13:56:17Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - light",
                "ratio": "16:9",
                "URI": "assets/s12131_ll_h8_ac.png"
              },
              {
                "assetId": "s12131_ld_h8_ac",
                "lastModified": "2021-04-29T13:56:22Z",
                "type": "image/png",
                "width": "3840",
                "height": "2160",
                "primary": "true",
                "category": "Parent Source Logo - dark",
                "ratio": "16:9",
                "URI": "assets/s12131_ld_h8_ac.png"
              }
            ]
          }
        }
      },    {
        "id": 141,
        "prgSvcId": null,
        "callSign": "YTA",
        "stationName": "Youtoo America",
        "image": "YTA.png",
        "url": "https://d16h32zy5psbn.cloudfront.net/YTA/index.mpd",
        "logo": "http://127.0.0.1:8000/uploads/channellogo/YTA.png",
        "genrename": [
          "Others"
        ],
        "gracenote_data": []
      }
    ],
    "message": "Channel with genre successfully fetched",
    "response_code": 200
  }

  constructor(private apiService:CrudService){}


  ngOnInit(): void {
    this.getPrivacyP();
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




  getPrivacyP() {
    this.apiService.getRequest2('auth/channelListWithGracenote', 'mpd', null, this.getAuthHeaders()).subscribe({
      next: (res: any) => {
        console.log(res);
      },
      error: (err: any) => {
        console.log(err);
      }
    });
  }
  
}
