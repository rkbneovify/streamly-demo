import {
  CUSTOM_ELEMENTS_SCHEMA,
  Component,
} from '@angular/core';
import { CustomReactWrapperComponent } from '../components/reactComponent/CustomReactWrapperComponent';
@Component({
  selector: 'app-dashjstest',
  standalone: true,
  imports: [CustomReactWrapperComponent],
  templateUrl: './dashjstest.component.html',
  styleUrl: './dashjstest.component.css',
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
})
export class DashjstestComponent {
  public counter = 1;

  public handleOnClick(stateCounter: any) {
    this.counter++;
  }
}