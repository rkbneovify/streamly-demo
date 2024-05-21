import { NgClass, NgFor } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-comedy',
  standalone: true,
  imports: [NgFor,NgClass],
  templateUrl: './comedy.component.html',
  styleUrl: './comedy.component.scss'
})
export class ComedyComponent {

}
