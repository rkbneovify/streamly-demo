import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SubPaymentComponent } from './sub-payment.component';

describe('SubPaymentComponent', () => {
  let component: SubPaymentComponent;
  let fixture: ComponentFixture<SubPaymentComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SubPaymentComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(SubPaymentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
