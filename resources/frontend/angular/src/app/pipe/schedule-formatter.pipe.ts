import { Pipe, PipeTransform } from '@angular/core';

interface ScheduleItem {
  date: string;
  time: string;
  duration: string;
}

@Pipe({
  name: 'scheduleFormatter',
  standalone:true
})
export class ScheduleFormatterPipe implements PipeTransform {

  transform(schedule: ScheduleItem[]): any {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];

    const formatDate = (dateStr: string) => {
      const options: Intl.DateTimeFormatOptions = { weekday: 'long', day: '2-digit', month: 'short' };
      const date = new Date(dateStr);
      return date.toLocaleDateString('en-US', options);
    };

    const formatTime = (timeStr: string) => {
      const [hour, minute] = timeStr.split(':');
      const date = new Date();
      date.setHours(parseInt(hour), parseInt(minute));
      return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    };

    const filterAndFormat = (date: string) => {
      return schedule
        .filter(event => event.date === date)
        .map(event => formatTime(event.time))
        .join('  ,  ');
    };

    const formattedToday = filterAndFormat(today);
    const formattedTomorrow = filterAndFormat(tomorrow);

    return {
      today: `Today, ${formatDate(today)}`,
      todaySchedule:`${formattedToday}`,
      tomorrow: `Tomorrow, ${formatDate(tomorrow)}`,
      tomorrowSchedule:`${formattedTomorrow}`
    };
  }

}
