import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ForgotPasscodeComponent } from './forgot-passcode.component';

describe('ForgotPasscodeComponent', () => {
  let component: ForgotPasscodeComponent;
  let fixture: ComponentFixture<ForgotPasscodeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ForgotPasscodeComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(ForgotPasscodeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
