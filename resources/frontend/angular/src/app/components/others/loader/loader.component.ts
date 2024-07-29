import { Component } from '@angular/core';
// import {LoaderService} from '../../../services/loaderService/loader.service';
import { NgIf } from '@angular/common';
@Component({
  selector: 'app-loader',
  standalone: true,
  imports: [NgIf],
  templateUrl: './loader.component.html',
  styleUrl: './loader.component.scss'
})
export class LoaderComponent {
  // isLoading: boolean = false;

  // constructor(private loaderService: LoaderService) { }

  // ngOnInit(): void {
  //   this.loaderService.isLoading.subscribe((value) => {
  //     this.isLoading = value;
  //     console.log(this.isLoading,'loader....');
  //   });
  // }
}
