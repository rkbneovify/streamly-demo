import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'extractFirstTwo',
  standalone:true
})
export class ExtractFirstTwoPipe implements PipeTransform {
  transform(value: string): string {
    if (!value || typeof value !== 'string') return '';
    return value.substring(0, 2); // Extract first two characters
  }
}
