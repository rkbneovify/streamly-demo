import { Directive, HostListener } from '@angular/core';

@Directive({
  selector: '[appOnlyDigits]',
  standalone:true,
})
export class OnlyDigitsDirective {
  @HostListener('keypress', ['$event'])
  onKeyPress(event: KeyboardEvent): void {
    const charCode = event.which || event.keyCode;
    if (charCode < 48 || charCode > 57) { // Allow only numbers (ASCII 48-57)
      event.preventDefault();
    }
  }
}
