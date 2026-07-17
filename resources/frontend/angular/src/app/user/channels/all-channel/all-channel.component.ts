import { JsonPipe, NgClass, NgFor, NgIf } from '@angular/common';
import { AfterViewInit, Component, ElementRef, OnDestroy, OnInit, QueryList, ViewChildren } from '@angular/core';
import { CrudService } from '../../../services/crud.service';
import { HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';
import { Toast, ToastrService } from 'ngx-toastr';
import { DurationISOFormatPipe, TruncatePipe } from '../../../pipe/duration-format.pipe';
import { ScreenSizeService } from '../../../services/screenSize/screen-size.service';
import { Subscription, zip } from 'rxjs';

@Component({
  selector: 'app-all-channel',
  standalone: true,
  imports: [NgFor, NgClass, JsonPipe, NgIf, DurationISOFormatPipe, TruncatePipe],
  templateUrl: './all-channel.component.html',
  styleUrl: './all-channel.component.scss'
})
export class AllChannelComponent implements OnInit, AfterViewInit, OnDestroy {
  Bearer_token: any;
  favChannelList: any;
  favoriteChannels: number[] = [];
  isLoading: boolean = false;
  private screenSizeSubscription!: Subscription;
  currentWidth: number = 0;
  expandedCardIndexRow: number | null = null;
  expandedCardIndex: number | null = null;
  @ViewChildren('card') cards!: QueryList<ElementRef>; // Access each card element


  data: any;
  constructor(private apiService: CrudService, private router: Router, private ts: ToastrService, private screenSizeService: ScreenSizeService) { }
  ngOnInit(): void {
    if (JSON.parse(localStorage.getItem('allChannel'))) {
      this.data = JSON.parse(localStorage.getItem('allChannel'));
    } else {
      this.getPrivacyP();
    }
    this.getFavoriteList();

    // Subscribe to screen width changes
    this.screenSizeSubscription = this.screenSizeService
      .getScreenWidth()
      .subscribe((width) => {
        this.currentWidth = width;
        console.log(`Screen width is: ${this.currentWidth}`);

        // Recalculate chunk size based on screen width
        if (this.data) {
          const chunkSize = Math.floor(this.currentWidth / 190);
          this.data = this.chunkArray(
            this.data.flat(),
            chunkSize
          );
        }
      });
  }
  ngOnDestroy(): void {
    this.isLoading = false;
    // Unsubscribe when the component is destroyed to avoid memory leaks
    if (this.screenSizeSubscription) {
      this.screenSizeSubscription.unsubscribe();
    }
  }


  ngAfterViewInit(): void {
    this.cards.forEach((card, index) => {
      const element = card.nativeElement as HTMLElement;
      element.addEventListener('mouseenter', () => this.adjustHoverPosition(element));
    });
  }

  adjustHoverPosition(cardElement: HTMLElement) {
    const cardRect = cardElement.getBoundingClientRect();
    const viewportWidth = window.innerWidth;
    const isNearRightEdge = cardRect.right + 266 > viewportWidth;

    if (isNearRightEdge) {
      cardElement.classList.add('open-left');
    } else {
      cardElement.classList.remove('open-left');
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

  getPrivacyP() {
    this.isLoading = true;
    const zipcode = JSON.parse(localStorage.getItem('userLocation'))?.zipcode || '';
    const body = {
      "typeurl": "mpd",
      zipcode: zipcode
    }
    this.apiService.postRequest('auth/all-channel-list', body).subscribe({
      next: (res: any) => {
        const list = this.chunkArray(res.data, this.currentWidth / 190);
        localStorage.setItem('allChannel', JSON.stringify(list));
        this.data = JSON.parse(localStorage.getItem('allChannel'));
        this.isLoading = false;
      },
      error: (err: any) => {
        console.log(err);
        this.isLoading = false;
      }
    });
  }
  //
  chunkArray(array: any, chunkSize: any): any {
    const result: any = [];
    for (let i = 0; i < array.length; i += chunkSize) {
      result.push(array.slice(i, i + chunkSize));
    }
    return result;
  }


  // Check if a channel is marked as favorite
  isFavorite(channelId: number): boolean {
    return this.favoriteChannels.includes(channelId);
  }
  // Toggle favorite status
  toggleFavorite(channelId: number): void {
    // If channel is already favorite, remove it
    if (this.isFavorite(channelId)) {
      this.removeFavoriteChannel(channelId);
    } else {
      // add it to favorites
      this.addFavoriteChannel(channelId);
    }
  }
  // Add channel to favorites
  addFavoriteChannel(channelId: number): void {
    this.isLoading = true;
    // Call API to add channel to favorites
    this.apiService.postRequest('auth/addtofavorite', { channelid: channelId }, this.getAuthHeaders()).subscribe((res: any) => {
      if (res.response_code === 200) {
        this.favoriteChannels.push(channelId); // Update local favorite list
        this.updateFavoriteChannels(); // Update local storage
        this.ts.success(res.message);
      }
      this.isLoading = false;
    });
  }
  // Remove channel from favorites
  removeFavoriteChannel(channelId: number): void {
    this.isLoading = true;
    // Call API to remove channel from favorites
    this.apiService.postRequest('auth/unfavorite', { channelid: channelId }, this.getAuthHeaders()).subscribe((res: any) => {
      if (res.response_code === 200) {
        this.favoriteChannels = this.favoriteChannels.filter(id => id !== channelId); // Update local favorite list
        this.updateFavoriteChannels(); // Update local storage
        this.ts.success(res.message);
      }
      this.isLoading = false;
    });
  }
  // Fetch favorite list
  getFavoriteList(): void {
    const storedFavoriteChannels = localStorage.getItem('favoriteChannels');
    if (storedFavoriteChannels) {
      this.favoriteChannels = JSON.parse(storedFavoriteChannels);
    } else {
      this.isLoading = true;
      // Fetch from API if not found in local storage
      this.apiService.getRequest('auth/my_favorites', this.getAuthHeaders()).subscribe((res: any) => {
        if (res.response_code === 200) {
          this.favoriteChannels = res.data.map((channel: any) => channel.id);
          this.updateFavoriteChannels(); // Update local storage
        }
        this.isLoading = false;

      });
    }
  }
  // Update local storage with current favorite channels
  updateFavoriteChannels(): void {
    localStorage.setItem('favoriteChannels', JSON.stringify(this.favoriteChannels));
  }

  // set player data in local
  setplayerData(url: string, id: any) {
    localStorage.setItem('playerId', url);
    localStorage.setItem('playerChannelId', id);
    this.router.navigateByUrl('/user/playstream')
  }


  // current Programs
  currentProgramDetail: boolean = false;
  programChannelId: string;
  programChannelUrl: string;
  currentprogramDetailData: any;

  formatTime(timeStr: any): string {
    const currentDate = new Date();
    const dateString = `${currentDate.getUTCFullYear()}-${(
      currentDate.getUTCMonth() + 1
    )
      .toString()
      .padStart(2, '0')}-${currentDate
        .getUTCDate()
        .toString()
        .padStart(2, '0')} ${timeStr} +0000`;
    const localDate = this.convertTZ(dateString);
    const hours = localDate.getHours();
    const minutes = localDate.getMinutes();
    const formattedDate = new Date();
    formattedDate.setHours(hours, minutes);
    return formattedDate.toLocaleTimeString('en-US', {
      hour: '2-digit',
      minute: '2-digit',
    });
  }

  convertTZ(
    date: any,
    tzString = Intl.DateTimeFormat().resolvedOptions().timeZone
  ): Date {
    return new Date(
      new Date(date).toLocaleString('en-US', { timeZone: tzString })
    );
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

  getCurrentProgram(url: string, id: string) {
    this.isLoading = true;
    this.programChannelId = id;
    this.programChannelUrl = url;
    const body = {
      channel_id: id,
      current_datetime: this.getCurrentFormattedDateTime(),
    };
    this.apiService.postRequest('auth/player-details', body).subscribe({
      next: (res: any) => {
        this.currentProgramDetail = !this.currentProgramDetail;
        this.currentprogramDetailData = res.data;
        this.isLoading = false;
      },
      error: (err: any) => {
        console.error(err);
        this.isLoading = false;
      },
    });
  }

  // Function to expand the last card
  expandLastCard(index: number, row) {
    this.expandedCardIndex = index;
    this.expandedCardIndexRow = row;
  }

  // Function to close the expanded card when another card is clicked
  closeExpandedCard() {
    this.expandedCardIndex = null;
    this.expandedCardIndexRow = null;

  }
}
