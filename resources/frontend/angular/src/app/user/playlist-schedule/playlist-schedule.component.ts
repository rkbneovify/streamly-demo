import { NgFor, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-playlist-schedule',
  standalone: true,
  imports: [NgFor,RouterLink,NgIf],
  templateUrl: './playlist-schedule.component.html',
  styleUrl: './playlist-schedule.component.scss'
})
export class PlaylistScheduleComponent {
  playlist_details_show:boolean=false;


  showPlaylist(){
    this.playlist_details_show= !this.playlist_details_show;
  }
}
