import { Component } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { FormsModule } from '@angular/forms';

interface TicketAttachment {
  name: string;
  url: string;
}

interface TicketMessage {
  id: number;
  author: string;
  role: string;
  date: Date;
  content: string;
  attachments: TicketAttachment[];
}

@Component({
  selector: 'app-ticket-chat',
  standalone: true,
  imports: [CommonModule, FormsModule, DatePipe],
  templateUrl: './ticket-chat.component.html',
  styleUrl: './ticket-chat.component.scss'
})
export class TicketChatComponent {
  ticket = {
    subject: 'Testing',
    ticketNo: 'TCK-20260811-0001',
    priority: 'Medium',
    status: 'InProgress',
    platform: 'Apple Tv'
  };

  messages: TicketMessage[] = [
    {
      id: 1,
      author: 'User',
      role: 'Customer',
      date: new Date('2026-08-11T09:30:00'),
      content: 'This is a testing',
      attachments: [
        {
          name: 'rhino.jpg',
          url: 'assets/images/rhino.jpg'
        },
        {
          name: 'tiger.jpg',
          url: 'assets/images/tiger.jpg'
        }
      ]
    },
    {
      id: 2,
      author: 'Ram Kumar Bedia',
      role: 'Admin',
      date: new Date('2026-08-11T10:43:00'),
      content:
        "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset's Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software like Aldus PageMaker and Microsoft Word including versions of Lorem Ipsum.",
      attachments: [
        {
          name: 'rhino.jpg',
          url: 'assets/images/rhino.jpg'
        },
        {
          name: 'tiger.jpg',
          url: 'assets/images/tiger.jpg'
        }
      ]
    }
  ];

  showReplyForm = false;
  replyMessage = '';

  openReply(): void {
    this.showReplyForm = true;
  }

  cancelReply(): void {
    this.showReplyForm = false;
    this.replyMessage = '';
  }

  sendReply(): void {
    const content = this.replyMessage.trim();

    if (!content) {
      return;
    }

    this.messages.push({
      id: Date.now(),
      author: 'Ram Kumar Bedia',
      role: 'Admin',
      date: new Date(),
      content,
      attachments: []
    });

    this.replyMessage = '';
    this.showReplyForm = false;
  }
}
