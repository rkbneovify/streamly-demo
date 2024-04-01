import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PaymentTestingComponent } from './payment-testing.component';

describe('PaymentTestingComponent', () => {
  let component: PaymentTestingComponent;
  let fixture: ComponentFixture<PaymentTestingComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PaymentTestingComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(PaymentTestingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
