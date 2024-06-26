import { NgFor } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { CrudService } from '../../../services/crud.service';

interface FaqData{
  question_key:string,
  question_answer:string,
  expanded:boolean
}

@Component({
  selector: 'app-faq',
  standalone: true,
  imports: [NgFor],
  templateUrl: './faq.component.html',
  styleUrl: './faq.component.scss'
})
export class FaqComponent implements OnInit{
  faqItems:FaqData[];

  constructor(private apiResponse:CrudService){}

  ngOnInit(): void {
    this.getFaq();
  }

  
  // = [
  //   { question: 'Question 1', answer: 'Lorem ipsum dolor sit amet consectetur. Mi dolor nunc neque adipiscing dis pretium cum erat. Diam sodales dignissim libero hendrerit vitae.Tortor amet etiam in varius proin. Sed sed commodo phasellus adipiscing quisque. Iaculis tempus quisque congue arcu diam enim. Quis non nibh dis quam in mattis. Id volutpat metus tincidunt neque. Massaamet eget odio imperdiet. Viverra aenean vitae aliquam odio magna egestas urna. Gravida cras aliquam nibh eget quis sagittis porta sagittis. Mi massa netus cras vitae volutpat.', expanded: false },
  //   { question: 'Question 2', answer: 'Answer to question 2', expanded: false },
  //   { question: 'Question 3', answer: 'Answer to question 3', expanded: false },
  //   { question: 'Question 4', answer: 'Answer to question 4', expanded: false },
  //   { question: 'Question 5', answer: 'Answer to question 5', expanded: false }
  // ];

  toggleAnswer(index: number) {
    this.faqItems[index].expanded = !this.faqItems[index].expanded;
  }

  getFaq(){
    this.apiResponse.getRequest('auth/faq').subscribe({
      next:(res:any)=>{
        console.log('data',res);
        this.faqItems = this.getCustomResponse(res.data);
      },
      error:(err:any)=>{
        console.log('error',err);
      }
    })
  }

  getCustomResponse(data:any){
    const res = data.map((item:any,index)=>{
      return {...item, expanded: index ==0?true:false}
    });

    return res;
  }
}
