import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TotalaccountComponent } from './totalaccount.component';

describe('TotalaccountComponent', () => {
  let component: TotalaccountComponent;
  let fixture: ComponentFixture<TotalaccountComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TotalaccountComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(TotalaccountComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
