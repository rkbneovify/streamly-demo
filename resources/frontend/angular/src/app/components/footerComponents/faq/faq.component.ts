import { NgFor } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-faq',
  standalone: true,
  imports: [NgFor],
  templateUrl: './faq.component.html',
  styleUrl: './faq.component.scss'
})
export class FaqComponent {

  faqItems = [
    { question: 'Question 1', answer: 'Lorem ipsum dolor sit amet consectetur. Mi dolor nunc neque adipiscing dis pretium cum erat. Diam sodales dignissim libero hendrerit vitae.Tortor amet etiam in varius proin. Sed sed commodo phasellus adipiscing quisque. Iaculis tempus quisque congue arcu diam enim. Quis non nibh dis quam in mattis. Id volutpat metus tincidunt neque. Massaamet eget odio imperdiet. Viverra aenean vitae aliquam odio magna egestas urna. Gravida cras aliquam nibh eget quis sagittis porta sagittis. Mi massa netus cras vitae volutpat.', expanded: false },
    { question: 'Question 2', answer: 'Answer to question 2', expanded: false },
    { question: 'Question 3', answer: 'Answer to question 3', expanded: false },
    { question: 'Question 4', answer: 'Answer to question 4', expanded: false },
    { question: 'Question 5', answer: 'Answer to question 5', expanded: false }
  ];

  toggleAnswer(index: number) {
    this.faqItems[index].expanded = !this.faqItems[index].expanded;
  }

}
