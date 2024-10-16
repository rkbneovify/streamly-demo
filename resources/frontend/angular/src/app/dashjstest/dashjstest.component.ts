import {
  CUSTOM_ELEMENTS_SCHEMA,
  Component,
} from '@angular/core';
@Component({
  selector: 'app-dashjstest',
  standalone: true,
  imports: [],
  templateUrl: './dashjstest.component.html',
  styleUrl: './dashjstest.component.css',
})
export class DashjstestComponent {
  public counter = 1;

  public handleOnClick(stateCounter: any) {
    this.counter++;
  }
}