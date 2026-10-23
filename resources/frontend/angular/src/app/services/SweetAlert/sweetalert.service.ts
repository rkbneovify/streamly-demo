import { Injectable } from '@angular/core';
import Swal from 'sweetalert2';
@Injectable({
  providedIn: 'root'
})
export class SweetalertService {
  private isLoading = false;
  constructor() { }



  get loadingState(): boolean {
    return this.isLoading;
  }

  toast(message: string, icon: any = 'success') {
    return Swal.fire({
      toast: true,
      position: 'top-right',
      icon: icon,
      title: message,
      showConfirmButton: false,
      showCloseButton: true,   // Show the X button
      timer: 5000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });
  }
  success(message: string, title: string = 'Success') {
    Swal.fire({
      title: title,
      text: message,
      icon: 'success',
      confirmButtonColor: '#3085d6'
    });
  }

  error(message: string, title: string = 'Error') {
    Swal.fire({
      title: title,
      text: message,
      icon: 'error',
      confirmButtonColor: '#d33'
    });
  }

  warning(message: string, title: string = 'Warning') {
    Swal.fire({
      title: title,
      text: message,
      icon: 'warning',
      confirmButtonColor: '#f6c23e'
    });
  }

  // ❗ Confirm Box
  confirm(title: string, message: string) {
    return Swal.fire({
      title: title,
      text: message,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Okay',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      allowOutsideClick: false,
      allowEscapeKey: false,
    });
  }

  loading(message: string = 'Please wait...') {
    this.isLoading = true;
    Swal.fire({
      title: message,
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      },
      willClose: () => {
        this.isLoading = false;
      }
    });

  }

  close() {
    Swal.close();
    this.isLoading = false;
  }

}
