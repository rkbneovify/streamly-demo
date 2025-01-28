import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class LocationService {
  private storageKey = 'userLocation';

  async getLocation(): Promise<{ latitude: number; longitude: number }> {
    return new Promise((resolve, reject) => {
      if (navigator.geolocation) {
        console.log('get location')
        navigator.geolocation.getCurrentPosition(
          (position) => {
            resolve({
              latitude: position.coords.latitude,
              longitude: position.coords.longitude,
            });
          },
          (error) => {
            reject(error);
          }
        );
      } else {
        reject(new Error('Geolocation is not supported by this browser.'));
      }
    });
  }

  // async getZipcode(latitude: number, longitude: number): Promise<string> {
  //   const apiKey = 'YOUR_GEOCODING_API_KEY';
  //   const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${apiKey}`;
  //   const response = await fetch(url);
  //   const data = await response.json();
  //   const locationData = data.results.find((result: any) =>
  //     result.types.includes('postal_code')
  //   );
  //   return locationData?.address_components[0]?.short_name || 'Unknown';
  // }
  async getZipcode(latitude: number, longitude: number): Promise<string | null> {
  const url = `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`;
  try {
    const response = await fetch(url);
    const data = await response.json();
    // return data || null;
    return data.address.postcode || null;
  } catch (error) {
    console.error('Error fetching ZIP code:', error);
    return null;
  }
}


  saveLocation(location: { latitude: number; longitude: number }, zipcode: string): void {
    const data = { location, zipcode };
    localStorage.setItem(this.storageKey, JSON.stringify(data));
  }

  getSavedLocation(): { latitude: number; longitude: number; zipcode: string } | null {
    const data = localStorage.getItem(this.storageKey);
    return data ? JSON.parse(data) : null;
  }

  clearLocation(): void {
    localStorage.removeItem(this.storageKey);
  }
}
