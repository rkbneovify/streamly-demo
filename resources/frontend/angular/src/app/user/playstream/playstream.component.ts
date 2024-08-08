import { DatePipe, NgFor, NgIf } from '@angular/common';
import {
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
import { Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
@Component({
  selector: 'app-playstream',
  standalone: true,
  imports: [NgFor,DatePipe,NgIf,FormsModule],
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

  // video
  @ViewChild('video') videoRef: ElementRef<HTMLVideoElement>;
  @ViewChild('customControls') customControlsRef: ElementRef<HTMLDivElement>;
  @ViewChild('videoContainer') videoContainerRef: ElementRef<HTMLDivElement>;
  playPauseText: string = 'Play';
  muteText: string = 'Mute';
  liveText: string = 'Live';
  progress: number = 0;

  constructor(private crud: CrudService,private router:Router) {
    this.playerId = localStorage.getItem('playerId');
    this.playerChannelId = +localStorage.getItem('playerChannelId');
    if(this.playerChannelId){
      this.addToRecentChannel(this.playerChannelId);
    }
  }

  ngOnInit() {
    this.playerDash();
    this.getPlayerData();
  }

// ======  video player cusmtom Start  =======

togglePlayPause(): void {
  const video = this.videoRef.nativeElement;
  if (video.paused) {
    video.play();
    this.playPauseText = 'Pause';
    this.liveText = 'Live';
  } else {
    video.pause();
    this.playPauseText = 'Play';
    this.liveText = 'Go Live';
  }
}

toggleMute(): void {
  const video = this.videoRef.nativeElement;
  video.muted = !video.muted;
  this.muteText = video.muted ? 'Unmute' : 'Mute';
}

updateProgress(): void {
  const video = this.videoRef.nativeElement;
  this.progress = (video.currentTime / video.duration) * 100;
}

seek(event: MouseEvent): void {
  const progressBar = this.customControlsRef.nativeElement.querySelector('.progress-bar');
  const rect = progressBar.getBoundingClientRect();
  const clickX = event.clientX - rect.left;
  const newTime = (clickX / rect.width) * this.videoRef.nativeElement.duration;
  this.videoRef.nativeElement.currentTime = newTime;
}

toggleFullscreen(): void {
  const videoContainer = this.videoContainerRef.nativeElement;
  if (document.fullscreenElement) {
    document.exitFullscreen();
  } else {
    if (videoContainer.requestFullscreen) {
      videoContainer.requestFullscreen();
    }
    //  else if (videoContainer.webkitRequestFullscreen) {
    //   videoContainer.webkitRequestFullscreen();
    // } else if (videoContainer.mozRequestFullScreen) {
    //   videoContainer.mozRequestFullScreen();
    // } else if (videoContainer.msRequestFullscreen) {
    //   videoContainer.msRequestFullscreen();
    // }
  }
}

goLive(): void {
  const video = this.videoRef.nativeElement;
  video.currentTime = video.duration;
  video.play();
  this.playPauseText = 'Pause';
  this.liveText = 'Live';
}

@HostListener('document:fullscreenchange')
@HostListener('document:webkitfullscreenchange')
@HostListener('document:mozfullscreenchange')
@HostListener('document:msfullscreenchange')
onFullscreenChange(): void {
  if (document.fullscreenElement) {
    this.customControlsRef.nativeElement.style.display = 'flex';
  } else {
    this.customControlsRef.nativeElement.style.display = 'none';
  }
}

// ======  Video player custom end   ========

  // Method to convert a date to the specified timezone
  convertTZ(date: any, tzString = Intl.DateTimeFormat().resolvedOptions().timeZone): Date {
    return new Date(new Date(date).toLocaleString("en-US", { timeZone: tzString }));
  }

  // Method to format time
  formatTime(timeStr: any): string {
    // Extract the current date
    const currentDate = new Date();
    // Combine with the provided time
    const dateString = `${currentDate.getUTCFullYear()}-${(currentDate.getUTCMonth() + 1)
      .toString()
      .padStart(2, "0")}-${currentDate.getUTCDate().toString().padStart(2, "0")} ${timeStr} +0000`;

    // Convert the combined date string to the local timezone
    const localDate = this.convertTZ(dateString);
    // Extract hours and minutes
    const hours = localDate.getHours();
    const minutes = localDate.getMinutes();

    // Create a new date object with the extracted time
    const formattedDate = new Date();
    formattedDate.setHours(hours, minutes);

    // Return the formatted time string
    return formattedDate.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit" });
  }

  playerDash(){
    this.getDRMKeys().subscribe((response:any) => {
      if (response.success && response.data) {
        const player = dashjs.MediaPlayer().create();
        const data = atob(response.data);
        // player.attachView(video);
        // var controlbar = new ControlBar(player); //Player is instance of Dash.js MediaPlayer;
        // controlbar.initialize();
        player.initialize(this.videoPlayer.nativeElement,this.playerId, true)
        // this.videoPlayer.nativeElement.volume = this.volume;
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

  // togglePlayPause() {
  //   if (this.videoPlayer.nativeElement.paused) {
  //     this.videoPlayer.nativeElement.play();
  //   } else {
  //     this.videoPlayer.nativeElement.pause();
  //   }
  // }

  // toggleMute() {
  //   this.videoPlayer.nativeElement.muted = !this.videoPlayer.nativeElement.muted;
  // }

  // toggleFullscreen() {
  //   if (document.fullscreenElement) {
  //     document.exitFullscreen();
  //   } else {
  //     this.videoPlayer.nativeElement.requestFullscreen();
  //   }
  // }

  // volume: number = 1; // Default volume
  // changeVolume(event: Event) {
  //   const input = event.target as HTMLInputElement;
  //   this.volume = parseFloat(input.value);
  //   this.videoPlayer.nativeElement.volume = this.volume;
  //   if (this.volume === 0) {
  //     this.videoPlayer.nativeElement.muted = true;
  //   } else {
  //     this.videoPlayer.nativeElement.muted = false;
  //   }
  // }

  showRecommendedChannels(playerURL:string,channelID:any){
    this.playerId = playerURL;
    this.playerChannelId = channelID;
    console.log('showRecommendedChannels =>',this.playerId,this.playerChannelId);
    if(playerURL && channelID){
      // this.playerDash();
      // this.getPlayerData();
      // this.addToRecentChannel(channelID);
      localStorage.setItem('playerChannelId',channelID);
      localStorage.setItem('playerId',playerURL);
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
    // Dispose Video.js player when component is destroyed
    if (this.player) {
      this.player.dispose();
    }
    if(this.playerChannelId){
      localStorage.setItem('playerChannelId','');
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

  // add to recent channels
  addToRecentChannel(playerChannelId:any){
    try {
      this.crud.postRequest('auth/addtorecent',{'channelids':playerChannelId}).subscribe({
        next:(res)=>{
          console.log('hello addToRecentChannel =>')
          console.log(res);
        },
        error:(err)=>{
          console.log(err)
        }
      })
    } catch (error) {
      throw new error;
    }
  }
}