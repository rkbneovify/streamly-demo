import { HttpClient } from '@angular/common/http';
import { Injectable, signal } from '@angular/core';
import { CrudService } from '../crud.service';


@Injectable({
  providedIn: 'root',
})
export class NotificationService {

  notificationCount = signal<number>(0);

  constructor(private api: CrudService) {

  }

  fetchCount() {
    this.api.getRequest('auth/notification/count').subscribe({
      next: (res: any) => {
        this.notificationCount.set(res.count);
      }
    });
  }

}
