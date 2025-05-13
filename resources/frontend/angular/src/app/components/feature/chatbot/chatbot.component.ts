import { Component, ElementRef, ViewChild, AfterViewChecked, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { CommonModule, NgIf } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CrudService } from '../../../services/crud.service';

interface ChatMessage {
  type: string;
  text: string;
  isHtml?: boolean;
  attachment?: any;
  additionalParameters?: any[];
  actions?: ChatAction[];
  isUser?: boolean;
}


interface ChatAction {
  name: string;
  text: string;
  type: string;
  value: string;
  image_url?: string;
  additional?: any[];
}

@Component({
  selector: 'app-chatbot',
  standalone: true,
  templateUrl: './chatbot.component.html',
  styleUrls: ['./chatbot.component.css'],
  imports: [CommonModule, FormsModule, NgIf]
})
export class ChatbotComponent implements AfterViewChecked, OnInit {
  @ViewChild('chatMessages') private chatMessagesContainer!: ElementRef;

  messages: ChatMessage[] = [{ type: 'text', text: 'Welcome! Type "Hi" or "Hello" to get started.' }];
  newMessage = '';
  loadingMessageIndex: number | null = null;
  interactiveMode = 0; // Dynamic interactive flag
  isChatbotShow:boolean = false;
  userId:string = null;
  showTextBubble = false;

  constructor(private http: HttpClient,private apiService:CrudService) {}

  ngOnInit(): void {
    this.userId = this.generateUserId();

        // Show the text bubble after 10 seconds
        setTimeout(() => {
          this.showTextBubble = true;
        }, 10000);
  }

  ngAfterViewChecked() {
    this.scrollToBottom();
  }

  sendMessage(value: string = this.newMessage) {
    if (!value.trim()) return;

    // Add user's message to chat
    const userMessage: ChatMessage = { type: 'text', text: value, isUser: true };
    this.messages.push(userMessage);

    // Add loader message
    const loadingMessage: ChatMessage = { type: 'loading', text: 'Loading...', isUser: false };
    this.messages.push(loadingMessage);
    this.loadingMessageIndex = this.messages.length - 1;

    // Send request with current interactive mode
    this.sendToBotManAPI(value, this.interactiveMode);

    this.newMessage = '';
  }

  private sendToBotManAPI(message: string, interactive: number) {
    const payload = {
      driver: 'web',
      userId: this.userId, // Replace with actual user ID if needed
      message,
      attachment: null,
      interactive
    };
    this.apiService.postRequest('botman', payload)
      .subscribe({
        next: (response:any) => {
          // Remove loading message
          if (this.loadingMessageIndex !== null) {
            this.messages.splice(this.loadingMessageIndex, 1);
            this.loadingMessageIndex = null;
          }

          // Check for actions in the response and adjust interactive mode
          response.messages.forEach(message => {
            // Detect if message contains HTML by looking for tags (simple check)
            const isHtml = /<[^>]+>/.test(message.text);
            this.messages.push({ ...message, isUser: false, isHtml });
            
            if (message.type === 'actions') {
              this.interactiveMode = 2;
            } else {
              this.interactiveMode = 0;
            }
          });
        },
        error: error => {
          // Remove loading message
          if (this.loadingMessageIndex !== null) {
            this.messages.splice(this.loadingMessageIndex, 1);
            this.loadingMessageIndex = null;
          }

          // Add an error message to messages array
          const errorMessage: ChatMessage = {
            type: 'text',
            text: 'An error occurred. Please try again later.',
            isUser: false
          };
          this.messages.push(errorMessage);

          console.error('Error sending message:', error);
        }
      });
  }


  handleAction(action: ChatAction) {
    // Locate the original message with actions and clear its actions
    const actionMessageIndex = this.messages.findIndex(message => message.actions && message.actions.length > 0);
    if (actionMessageIndex !== -1) {
      this.messages[actionMessageIndex].actions = []; // Clear actions array to hide the buttons
    }

    // Send the action's value as the message to the API without adding it to the chat
    this.sendMessage(action.value);
  }

  closeChat() {
    this.isChatbotShow = false
    this.showTextBubble = false;
    console.log('Chat closed');
  }

  // Method to scroll to the bottom of the chat container
  private scrollToBottom() {
    try {
      if(this.chatMessagesContainer){
        this.chatMessagesContainer.nativeElement.scrollTop = this.chatMessagesContainer?.nativeElement?.scrollHeight;
      }
    } catch (err) {
      console.error('Scroll to bottom error:', err);
    }
  }

  generateUserId(): string {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let userId = '';
    for (let i = 0; i < 6; i++) {
      const randomIndex = Math.floor(Math.random() * characters.length);
      userId += characters[randomIndex];
    }
    return userId;
  }
  
}
