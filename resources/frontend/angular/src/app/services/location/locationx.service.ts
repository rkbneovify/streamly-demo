import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class LocationxService {

  constructor(private http: HttpClient) { }

  // 🔹 Step 1: Get coordinates (with permission)
  getCoordinates(): Promise<{ lat: number; lng: number }> {
    return new Promise((resolve, reject) => {
      if (!navigator.geolocation) {
        reject('Geolocation not supported');
        return;
      }

      navigator.geolocation.getCurrentPosition(
        (position) => {
          resolve({
            lat: position.coords.latitude,
            lng: position.coords.longitude
          });
        },
        (error) => reject(error),
        {
          enableHighAccuracy: true,
          timeout: 10000
        }
      );
    });
  }

  // 🔹 Step 2: Reverse geocode → PIN code
  getPincode(lat: number, lng: number): Promise<string> {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;

    return new Promise((resolve, reject) => {
      this.http.get<any>(url).subscribe({
        next: (res) => {
          const pin = res?.address?.postcode;
          resolve(pin || 'Not found');
        },
        error: (err) => reject(err)
      });
    });
  }

  // 🔹 Step 3: Main method (permission + cache + API)
  async getCurrentPincode(): Promise<string> {
    try {
      // ✅ 1. Check cache first
      const cached = localStorage.getItem('pincode');
      if (cached) {
        return cached;
      }

      // ✅ 2. Check permission state
      const permission = await (navigator as any).permissions.query({
        name: 'geolocation'
      });

      if (permission.state === 'denied') {
        throw 'Location permission denied. Enable it in browser settings.';
      }

      // ✅ 3. Get coordinates (will ask only first time)
      const coords = await this.getCoordinates();

      // ✅ 4. Get PIN code
      const pin = await this.getPincode(coords.lat, coords.lng);

      // ✅ 5. Cache result
      localStorage.setItem('pincode', pin);

      return pin;

    } catch (error: any) {
      if (error.code === 1) {
        throw 'Permission denied by user';
      }
      throw error;
    }
  }
}