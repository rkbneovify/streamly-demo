import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { SweetalertService } from '../services/SweetAlert/sweetalert.service';

@Component({
  selector: 'app-server-message',
  standalone: true,
  imports: [],
  templateUrl: './server-message.component.html',
  styleUrl: './server-message.component.css'
})
export class ServerMessageComponent {
  type!: string;
  message!: string;

  constructor(private route: Router, private activeRoute: ActivatedRoute, private sweetAlert: SweetalertService) {

  }

  ngOnInit() {
    this.activeRoute.queryParams.subscribe(params => {
      this.type = params['type'];
      this.message = params['message'];
      switch (this.type.toString().toLocaleLowerCase()) {
        case 'success':
          this.sweetAlert.success(this.message);
          break;

        default:
          this.sweetAlert.error(this.message);

          break;
      }



    });

    this.route.navigate(['/'])

  }

}
