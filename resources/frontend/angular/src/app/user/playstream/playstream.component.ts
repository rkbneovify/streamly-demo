import { NgFor } from '@angular/common';
import { Component, ElementRef, OnDestroy, OnInit, ViewChild } from '@angular/core';
import videojs  from 'video.js';
import 'video.js/dist/video-js.css';

@Component({
  selector: 'app-playstream',
  standalone: true,
  imports: [NgFor],
  templateUrl: './playstream.component.html',
  styleUrl: './playstream.component.scss'
})
export class PlaystreamComponent implements OnInit, OnDestroy {
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
