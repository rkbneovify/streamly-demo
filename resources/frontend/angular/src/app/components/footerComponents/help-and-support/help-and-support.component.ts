import { CommonModule, NgFor, NgIf } from '@angular/common';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { RouterLink, Router } from '@angular/router';
import { CrudService } from '../../../services/crud.service';
import {
  debounceTime,
  distinctUntilChanged,
  exhaustMap,
  finalize,
  Subject,
  switchMap,
  takeUntil
} from 'rxjs';
import { SweetalertService } from '../../../services/SweetAlert/sweetalert.service';
import { InfiniteScrollDirective } from 'ngx-infinite-scroll';
import { FormControl, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { CacheService } from '../../../services/cacheService/cacheservice.service';

interface QuickLink {
  icon: string;
  title: string;
  description: string;
  color: string;
}

interface Category {
  icon: string;
  title: string;
  color: string;
  articles: string[];
}


@Component({
  selector: 'app-help-and-support',
  standalone: true,
  imports: [
    RouterLink,
    NgFor,
    NgIf,
    InfiniteScrollDirective,
    CommonModule,
    FormsModule,
    ReactiveFormsModule
  ],
  templateUrl: './help-and-support.component.html',
  styleUrl: './help-and-support.component.scss'
})
export class HelpAndSupportComponent implements OnInit, OnDestroy {
  constructor(
    private router: Router,
    private api: CrudService,
    private alert: SweetalertService,
    private myCache: CacheService
  ) { }

   popularSearches = [
    'Subscription & Billing',
    'Login Issues',
    'App on TV',
    'Buffering',
    'Supported Devices'
  ];

  quickLinks: QuickLink[] = [
    {
      icon: 'bi-credit-card',
      title: 'Manage Subscription',
      description: 'Update or cancel',
      color: 'blue'
    },
    {
      icon: 'bi-person-check',
      title: 'Account Settings',
      description: 'Profile & security',
      color: 'green'
    },
    {
      icon: 'bi-tv',
      title: 'Watch on TV',
      description: 'Setup & activate',
      color: 'purple'
    },
    {
      icon: 'bi-phone',
      title: 'Supported Devices',
      description: 'See all devices',
      color: 'pink'
    },
    {
      icon: 'bi-grid',
      title: 'App & Features',
      description: 'Explore features',
      color: 'cyan'
    },
    {
      icon: 'bi-headset',
      title: 'Contact Support',
      description: 'We’re here 24 / 7',
      color: 'yellow'
    }
  ];

  categories: Category[] = [
    {
      icon: 'bi-credit-card',
      title: 'Subscription & Billing',
      color: 'blue',
      articles: [
        'Plans and pricing',
        'Payment methods',
        'How to cancel subscription'
      ]
    },
    {
      icon: 'bi-person-check',
      title: 'Account Settings',
      color: 'green',
      articles: [
        'Create Account',
        'Update Account Information',
        'Reset Password'
      ]
    },
    {
      icon: 'bi-grid',
      title: 'App & Features',
      color: 'cyan',
      articles: [
        'Download the app',
        'Features overview',
        'Content recommendations'
      ]
    },
    {
      icon: 'bi-tv',
      title: 'Devices and Compatibility',
      color: 'purple',
      articles: [
        'Supported Devices',
        'How to install on TV',
        'Device Requirements'
      ]
    },
    {
      icon: 'bi-broadcast',
      title: 'Playback & Streaming',
      color: 'pink',
      articles: [
        'Buffering issues',
        'Video Quality',
        'Playback error messages'
      ]
    },
    {
      icon: 'bi-bug',
      title: 'Troubleshooting',
      color: 'dark-blue',
      articles: [
        'App not working',
        'Login issues',
        'Error codes explained'
      ]
    },
    {
      icon: 'bi-shield-check',
      title: 'Safety & Privacy',
      color: 'brown',
      articles: [
        'Privacy policy',
        'Data & security',
        'Report inappropriate content'
      ]
    },
    {
      icon: 'bi-handshake',
      title: 'Partner & Promotions',
      color: 'navy',
      articles: [
        'Partner Offers',
        'Redeem promo code',
        'Promotions FAQ'
      ]
    },
    {
      icon: 'bi-file-earmark-text',
      title: 'General',
      color: 'red',
      articles: [
        'Terms of use',
        'Refund Policy',
        'Accessibility'
      ]
    }
  ];

  search(): void {
    console.log('Search clicked');
  }

  selectPopular(item: string): void {
    console.log('Selected:', item);
  }

  ngOnInit(): void {

  }

  ngOnDestroy(): void {

  }
}
