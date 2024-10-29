import {
  CUSTOM_ELEMENTS_SCHEMA,
  Component,
} from '@angular/core';
import { CardComponent } from '../components/shared/card/card/card.component';
@Component({
  selector: 'app-dashjstest',
  standalone: true,
  imports: [CardComponent],
  templateUrl: './dashjstest.component.html',
  styleUrl: './dashjstest.component.css',
})
export class DashjstestComponent {
  public counter = 1;

  public handleOnClick(stateCounter: any) {
    this.counter++;
  }
}