import { Injectable } from "@angular/core";

@Injectable({
  providedIn: 'root'
})
export class CacheService {

  private cache = new Map<string, any>();

  set(key: string, data: any): void {
    this.cache.set(key, data);
  }

  get(key: string): any {
    return this.cache.get(key);
  }

  clear(): void {
    this.cache.clear();
  }

  remove(key: string): void {
    this.cache.delete(key);
  }

   removeDuplicates<T extends { id: number | string }>(array: T[]): T[] {
    return Array.from(
      new Map(array.map(item => [item.id, item])).values()
    ).sort((a, b) => {
      if (typeof a.id === 'number' && typeof b.id === 'number') {
        return a.id - b.id;
      }

      return String(a.id).localeCompare(String(b.id));
    });
  }
}
