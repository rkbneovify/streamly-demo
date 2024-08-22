import { DatePipe, NgFor, NgIf } from '@angular/common';
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
import { NavigationEnd, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import Hls from 'hls.js'; // Import Hls.js
import { Subject, Subscription, takeUntil } from 'rxjs';

@Component({
  selector: 'app-playstream',
  standalone: true,
  imports: [NgFor, DatePipe, NgIf, FormsModule],
  templateUrl: './playstream.component.html',
  styleUrls: ['./playstream.component.scss'],
})
export class PlaystreamComponent implements OnInit, OnDestroy {
  @ViewChild('videoPlayer', { static: true }) videoPlayer!: ElementRef;
  player: any;
  playerId!: any;
  tokenkey: any;
  Bearer_token: any;
  channelData: any;
  playerChannelId: number = null;
  private subscription: Subscription = new Subscription();
  private routerSubscription: Subscription = new Subscription();
  private destroy$: Subject<void> = new Subject();

  constructor(private crud: CrudService, private router: Router) {
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
    this.initializePlayer();
    this.getPlayerData();
  }

  initializePlayer() {
    if (this.isIOS()) {
      this.initializeHLSPlayer();
    } else {
      this.playerDash();
    }
  }

  isIOS(): boolean {
    return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  }

  initializeHLSPlayer() {
    if (Hls.isSupported()) {
      const hls = new Hls();
      hls.loadSource(this.playerId); // Assuming playerId is the HLS URL
      hls.attachMedia(this.videoPlayer.nativeElement);
      hls.on(Hls.Events.MANIFEST_PARSED, () => {
        this.videoPlayer.nativeElement.play();
      });
    } else if (this.videoPlayer.nativeElement.canPlayType('application/vnd.apple.mpegurl')) {
      this.videoPlayer.nativeElement.src = this.playerId; // Assuming playerId is the HLS URL
      this.videoPlayer.nativeElement.addEventListener('loadedmetadata', () => {
        this.videoPlayer.nativeElement.play();
      });
    }
  }

  playerDash() {
    this.getDRMKeys().pipe(takeUntil(this.destroy$)).subscribe((response: any) => {
      if (response.success && response.data) {
        const player = dashjs.MediaPlayer().create();
        const data = atob(response.data);
        player.initialize(this.videoPlayer.nativeElement, this.playerId, true, response);
        player.setProtectionData({
          'com.widevine.alpha': {
            serverURL: response.widevinelicense,
            httpRequestHeaders: {
              'customdata': response.data // Assuming message contains the token
            }
          }
        });

      // Listen for dash.js errors
      player.on(dashjs.MediaPlayer.events.ERROR, (e: any) => {
        console.log('DASH error: ', e);
        alert('Due to some technical reasons, the channel gets interrupted. Kindly try again later.');
        this.router.navigateByUrl('/user/playlistschedule')
      });
      } else {
        console.error('Error retrieving DRM authentication key:', response.message);
      }

      if(response.error){
        console.log('erro',response.error);
      }
    });
  }

  convertTZ(date: any, tzString = Intl.DateTimeFormat().resolvedOptions().timeZone): Date {
    return new Date(new Date(date).toLocaleString("en-US", { timeZone: tzString }));
  }

  formatTime(timeStr: any): string {
    const currentDate = new Date();
    const dateString = `${currentDate.getUTCFullYear()}-${(currentDate.getUTCMonth() + 1)
      .toString()
      .padStart(2, "0")}-${currentDate.getUTCDate().toString().padStart(2, "0")} ${timeStr} +0000`;
    const localDate = this.convertTZ(dateString);
    const hours = localDate.getHours();
    const minutes = localDate.getMinutes();
    const formattedDate = new Date();
    formattedDate.setHours(hours, minutes);
    return formattedDate.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit" });
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

  getDRMKeys(): any {
    return this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders());
  }

  ngOnDestroy() {
    if (this.player) {
      this.player.dispose();
      this.player = null;
    }

    // Emit a value to complete the observable and unsubscribe
    this.destroy$.next();
    this.destroy$.complete();

    if (this.subscription) {
      console.log('unSub')
      this.subscription.unsubscribe();
    }

    if (this.routerSubscription) {
      console.log('Router unSub');
      this.routerSubscription.unsubscribe();
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
    const body = {
      "channel_id": this.playerChannelId,
      "current_datetime": this.getCurrentFormattedDateTime()
    }
    this.crud.postRequest('auth/player-details', body).subscribe({
      next: (res: any) => {
        console.log('Player Data',res);
        this.channelData = res.data;
      },
      error: (err: any) => {
        console.error(err);
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
