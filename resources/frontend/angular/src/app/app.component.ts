import { HttpClientModule } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { loadStripe } from '@stripe/stripe-js';
import { NgxStripeModule } from 'ngx-stripe';
import { environment } from '../environments/environment';
import { BrowserModule } from '@angular/platform-browser';
import { GetLocationComponent } from './auth/userLocation/get-location/get-location.component';
import { LocationService } from './services/location/location.service';
import { UtilsService } from './services/utils/utils.service';
// import { LoaderComponent } from "./components/others/loader/loader.component";

@Component({
  selector: 'app-root',
  standalone: true,
  templateUrl: './app.component.html',
  styleUrl: './app.component.css',
  imports: [RouterOutlet, HttpClientModule, GetLocationComponent],
})
export class AppComponent {
  isLocaiton: boolean = false;
  title = 'angular';
  sripePromise = loadStripe(environment.stripe.publicKey);

  constructor(private utilsService: UtilsService) {
    // console.log('Environment:')
    // this.utilsService.get_all_device();
  }


  // ngOnInit(): void {
  //   const savedLocation = this.locationService.getSavedLocation();
  //   const data = JSON.parse(localStorage.getItem('deviceInfo'));
  //   console.log(!!savedLocation);
  //   if (!!savedLocation) {
  //     this.isLocaiton = false;
  //   } else {
  //     // alert('Hello')
  //     console.log(data.access_token && data.subscribed,data.access_Token ,data.subscribed)
  //     if(!!data.access_token && data.subscribed){
  //       this.isLocaiton = true;
  //     }
  //   }
  // }
  // refreshPage() {
  //   window.location.reload();
  // }
  

  // async getLocation() {
  //   const location = await this.locationService.getLocation();
  //   const zipcode = await this.locationService.getZipcode(
  //     location.latitude,
  //     location.longitude
  //   );
  //   this.locationService.saveLocation(location, zipcode);
  //   console.log(location);

  //   this.refreshPage();
  // }
}
