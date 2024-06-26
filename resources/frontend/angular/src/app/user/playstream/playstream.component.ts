import { DatePipe, NgFor } from '@angular/common';
import {
  Component,
  ElementRef,
  OnDestroy,
  OnInit,
  ViewChild,
} from '@angular/core';
import { CrudService } from '../../services/crud.service';
import { HttpHeaders } from '@angular/common/http';

import * as dashjs from 'dashjs';
import { Router } from '@angular/router';
@Component({
  selector: 'app-playstream',
  standalone: true,
  imports: [NgFor,DatePipe],
  templateUrl: './playstream.component.html',
  styleUrl: './playstream.component.scss',
})
export class PlaystreamComponent implements OnInit, OnDestroy {
  @ViewChild('videoPlayer', { static: true }) videoPlayer!: ElementRef;
  player: any;
  playerId!: any;
  tokenkey: any;
  Bearer_token: any;
  channelData:any;

  constructor(private crud: CrudService,private router:Router) {
    this.playerId = localStorage.getItem('playerId');
    this.playerChannelId = +localStorage.getItem('playerChannelId');
  }

  ngOnInit() {


    this.getDRMKeys().subscribe((response:any) => {
      if (response.success && response.data) {
        const player = dashjs.MediaPlayer().create();
        const data = atob(response.data);
        // player.attachView(video);
        // var controlbar = new ControlBar(player); //Player is instance of Dash.js MediaPlayer;
        // controlbar.initialize();
        player.initialize(this.videoPlayer.nativeElement,this.playerId, true)

        // player.updateSettings({
        //   streaming: {
        //     utcSynchronization: {
        //       backgroundAttempts: 2
        //     }
        //   }
        // })
        // player.updateSettings({
        //   streaming: {
        //     utcSynchronization: {
        //       timeBetweenSyncAttempts: 30
        //     }
        //   }
        // })

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

    });

    this.getPlayerData();
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

  getDRMKeys(): any {
    return this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders());
  }

  ngOnDestroy() {
    // Dispose Video.js player when component is destroyed
    if (this.player) {
      this.player.dispose();
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

  playerChannelId:number= null;
  getPlayerData(){
    const body = {
      "channel_id": this.playerChannelId,
      "current_datetime": this.getCurrentFormattedDateTime()
  }
    this.crud.postRequest('auth/player-details',body).subscribe({
      next:(res:any)=>{
        console.log(res);
        this.channelData = res.data;
      },
      error:(err:any)=>{
        console.log(err);
      }
    })
  }

  setChannelId(id:string,url:string){
    localStorage.setItem('playerChannelId',id);
    localStorage.setItem('playerId',url);
    this.router.navigateByUrl('user/playstream')
  }
}