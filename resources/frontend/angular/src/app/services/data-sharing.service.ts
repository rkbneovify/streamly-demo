import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class DataSharingService {
  // Initialize BehaviorSubject with a default value
  private dataSource = new BehaviorSubject<any>(null);
  
  // Observable to share data
  currentData = this.dataSource.asObservable();

  constructor() {}

  // Method to update data
  changeData(data: any) {
    this.dataSource.next(data);
  }
}
