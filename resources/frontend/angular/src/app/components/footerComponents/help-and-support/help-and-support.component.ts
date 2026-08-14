import { NgFor, NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink, Router } from '@angular/router';

interface Category {
  title: string;
  description: string;
  icon: string;
}

interface Actions {
  title: string;
  icon: string;
  url: string;
}

@Component({
  selector: 'app-help-and-support',
  standalone: true,
  imports: [RouterLink, NgFor, NgIf],
  templateUrl: './help-and-support.component.html',
  styleUrl: './help-and-support.component.scss'
})
export class HelpAndSupportComponent {
  constructor(private router: Router) {}
  searchText = '';

  categories: Category[] = [
    {
      title: 'Reports & Dashboards',
      description: 'Get insights and learn how to use reports.',
      icon: 'assets/icons/article.svg'
    },
    {
      title: 'How to get help',
      description: 'Contact our support system & find answers',
      icon: 'assets/icons/article.svg'
    },
    {
      title: 'Notification',
      description: 'Manage alerts & notification settings',
      icon: 'assets/icons/article.svg'
    },
    {
      title: 'Video Data',
      description: 'Learn about video data & usage.',
      icon: 'assets/icons/article.svg'
    },
    {
      title: 'Sports Data',
      description: 'Explore sports data options & formats.',
      icon: 'assets/icons/article.svg'
    },
    {
      title: 'Audio Data',
      description: 'Understand audio data & integrations.',
      icon: 'assets/icons/article.svg'
    },
    {
      title: 'Automotive Data',
      description: 'Discover automotive data solutions.',
      icon: 'assets/icons/article.svg'
    },
    {
      title: 'Studio System',
      description: 'Learn about studio system & setup',
      icon: 'assets/icons/article.svg'
    }
  ];

  actions: Actions[] = [
    {
      title: 'Support Ticket',
      icon: 'assets/icons/support-ticket.svg',
      url: '/user/support-ticket'
    },
    {
      title: 'Report A BUG',
      icon: 'assets/icons/bug.svg',
      url: '/user/report'
    },
    {
      title: 'FAQs',
      icon: 'assets/icons/faq.svg',
      url: '/user/faq'
    },
    {
      title: 'Privacy Policy',
      icon: 'assets/icons/policy.svg',
      url: '/user/privacy-policy'
    }
  ];

  generateTicket(): void {
    this.router.navigate(['/user/support-ticket']);
  }

  openCategory(category: Category): void {
    console.log('Category:', category);
  }

  openAction(action: Actions): void {
   this.router.navigate([action.url]);
    console.log(action.url);
  }
}
