import { Component, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { DataSharingService } from '../../services/data-sharing.service';
import { NgIf } from '@angular/common';

@Component({
  selector: 'app-subscription-cancel',
  standalone: true,
  imports: [RouterLink,NgIf],
  templateUrl: './subscription-cancel.component.html',
  styleUrl: './subscription-cancel.component.scss'
})
export class SubscriptionCancelComponent implements OnInit {
  receivedData: string;

  constructor(private route:Router, private currentData:DataSharingService){}
  

  ngOnInit() {
    this.currentData.currentData.subscribe(data => {
      this.receivedData = data;
    });
  }
}
