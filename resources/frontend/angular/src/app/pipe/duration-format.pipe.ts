import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'durationFormat',
  standalone: true
})
export class DurationFormatPipe implements PipeTransform {

  transform(start: number, end: number): string {
    if (!start || !end) {
      return '';
    }

    const startDate = new Date(start * 1000); // Convert to milliseconds
    const endDate = new Date(end * 1000); // Convert to milliseconds
    const durationMs = endDate.getTime() - startDate.getTime(); // Calculate the duration in milliseconds
    const durationMinutes = Math.floor(durationMs / (1000 * 60)); // Convert duration to minutes

    const hours = Math.floor(durationMinutes / 60);
    const minutes = durationMinutes % 60;

    if (hours > 0) {
      return `${hours} hour${hours !== 1 ? 's' : ''} ${minutes > 0?minutes+' min':''}`;
    } else {
      return `${minutes} minute${minutes !== 1 ? 's' : ''}`;
    }

    // return `${hours} hour${hours !== 1 ? 's' : ''} ${minutes} minute${minutes !== 1 ? 's' : ''}`;
  }

}

@Pipe({
  name: 'durationISOFormat',
  standalone: true
})
export class DurationISOFormatPipe implements PipeTransform {

  transform(value: string): string {
    if (!value) {
      return '';
    }

    // Regular expression to parse ISO 8601 duration format
    const regex = /P(?:T(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?)?/;
    const matches = value.match(regex);

    if (!matches) {
      return '';
    }

    const hours = matches[1] ? parseInt(matches[1], 10) : 0;
    const minutes = matches[2] ? parseInt(matches[2], 10) : 0;

    let result = '';
    if (hours > 0) {
      result += hours + ' hour' + (hours > 1 ? 's' : '') + ' ';
    }
    if (minutes > 0) {
      result += minutes + ' minute' + (minutes > 1 ? 's' : '');
    }

    return result.trim();
  }

}


@Pipe({
  name: 'truncate',
  standalone:true
})
export class TruncatePipe implements PipeTransform {

  transform(value: string, limit: number = 15): string {
    if (!value) {
      return '';
    }

    return value.length > limit ? value.substring(0, limit) + '...' : value;
  }

}