import { Component } from '@angular/core';
import { NgSelectModule } from '@ng-select/ng-select';

@Component({
  selector: 'app-schedule-chart',
  standalone: true,
  imports: [NgSelectModule,],
  templateUrl: './schedule-chart.component.html',
  styleUrl: './schedule-chart.component.scss'
})
export class ScheduleChartComponent {
//   daysData = [
//     {'day':'Today'},
//     {'day':'Tomorrow'},
//     {'day':'Monday'},
//     {'day':'Tuesday'},
//     {'day':'Wednesday'},
//     {'day':'Thursday'},
//     {'day':'Friday'},
//     {'day':'Saturday'},
//     {'day':'Sunday'},
// ]
}
