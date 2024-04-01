import { NgFor } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-schedule-detail',
  standalone: true,
  imports: [NgFor],
  templateUrl: './schedule-detail.component.html',
  styleUrl: './schedule-detail.component.scss'
})
export class ScheduleDetailComponent {

}
