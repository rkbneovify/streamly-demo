import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DashjstestComponent } from './dashjstest.component';

describe('DashjstestComponent', () => {
  let component: DashjstestComponent;
  let fixture: ComponentFixture<DashjstestComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DashjstestComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(DashjstestComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
