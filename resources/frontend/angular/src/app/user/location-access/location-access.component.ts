import { Component } from '@angular/core';
import { GetLocationComponent } from '../../auth/userLocation/get-location/get-location.component';

@Component({
  selector: 'app-location-access',
  standalone: true,
  imports: [GetLocationComponent],
  templateUrl: './location-access.component.html',
  styleUrl: './location-access.component.css'
})
export class LocationAccessComponent {

}
