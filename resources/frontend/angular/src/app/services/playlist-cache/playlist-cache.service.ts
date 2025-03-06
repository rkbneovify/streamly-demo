import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root', // Automatically provided at the root level
})
export class PlaylistCacheService {
  private categoriesCache: any = null;
  private scheduleCache: any = null;
  private favoriteListCache: any = null;
  private currentTimeSlotCache: any = null;
  private filterData:any = null;

  // Categories
  setCategories(data: any) {
    this.categoriesCache = data;
  }

  getCategories() {
    return this.categoriesCache;
  }

  // Schedule Data
  setSchedule(data: any) {
    this.scheduleCache = data;
  }

  getSchedule() {
    return this.scheduleCache;
  }

  // Favorite List
  setFavoriteList(data: any) {
    this.favoriteListCache = data;
  }

  getFavoriteList() {
    return this.favoriteListCache;
  }

  // Current Time Slot
  setCurrentTimeSlot(data: any) {
    this.currentTimeSlotCache = data;
  }

  getCurrentTimeSlot() {
    return this.currentTimeSlotCache;
  }

  setFilterData(data:any){
    this.filterData = data;
  }

  getFilterData(){
    return this.filterData;
  }

  // Clear all cached data
  clearCache() {
    this.categoriesCache = null;
    this.scheduleCache = null;
    this.favoriteListCache = null;
    this.currentTimeSlotCache = null;
    this.filterData = null;
    if (localStorage.getItem('scrollPosition')) {
      localStorage.removeItem('scrollPosition');
    }    
  }
}
