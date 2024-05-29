import { Component } from '@angular/core';
import { RouterLink,Router } from '@angular/router';

@Component({
  selector: 'app-help-and-support',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './help-and-support.component.html',
  styleUrl: './help-and-support.component.scss'
})
export class HelpAndSupportComponent {
  constructor(private router:Router){}
}
