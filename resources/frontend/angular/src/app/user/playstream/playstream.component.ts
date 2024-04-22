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

  constructor(private crud: CrudService) {
    this.crud.getRequest('auth/getdrmkey', this.getAuthHeaders()).subscribe({
      next: (res: any) => {
        this.tokenkey = res.data;
        console.log(this.tokenkey, 'token key');

        console.log(`${this.playerId}?access_key=${this.tokenkey}`);
        this.player = videojs(this.videoPlayer.nativeElement, {
          autoplay: true,
          controls: true,
          // sources: [
          //   {
          //     src: `https://d2t3s7h1wasu4l.cloudfront.net/CNBC/index.mpd?access_key=${this.tokenkey}`,
          //     type: 'application/dash+xml',
          //   },
          // ],
        });
      },
      error: (err) => console.log(err),
    });
    this.playerId = localStorage.getItem('playerId');

    // this.tokenkey = JSON.parse(localStorage.getItem('deviceInfo') || '')
  }

  ngOnInit() {
    // Initialize Video.js player
    console.log(this.playerId);
    this.player = videojs(this.videoPlayer.nativeElement, {
      autoplay: true,
      controls: true,
      sources: [{
        src: `https://d2t3s7h1wasu4l.cloudfront.net/CNBC/index.mpd`,
        type: 'application/dash+xml'
      }]
    });
  }

  getAuthHeaders(): HttpHeaders {
    const tokenData = JSON.parse(
      localStorage.getItem('deviceInfo') || ''
    ).access_token;
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
