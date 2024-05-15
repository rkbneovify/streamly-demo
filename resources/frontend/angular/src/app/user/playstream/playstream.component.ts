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

import * as dashjs from 'dashjs';
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
  Bearer_token: any;

  constructor(private crud: CrudService) {
    this.playerId = localStorage.getItem('playerId');
  }

  ngOnInit() {


    this.getDRMKeys().subscribe((response:any) => {
      if (response.success && response.data) {
        const player = dashjs.MediaPlayer().create();
        const data = atob(response.data);
        // player.attachView(video);
        // var controlbar = new ControlBar(player); //Player is instance of Dash.js MediaPlayer;
        // controlbar.initialize();
        player.initialize(this.videoPlayer.nativeElement,this.playerId , true)

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
}