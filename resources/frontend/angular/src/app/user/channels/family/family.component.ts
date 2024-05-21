import { NgClass, NgFor } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-family',
  standalone: true,
  imports: [NgFor,NgClass],
  templateUrl: './family.component.html',
  styleUrl: './family.component.scss'
})
export class FamilyComponent {

}
