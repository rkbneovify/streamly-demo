import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SubUserEditVerificationComponent } from './sub-user-edit-verification.component';

describe('SubUserEditVerificationComponent', () => {
  let component: SubUserEditVerificationComponent;
  let fixture: ComponentFixture<SubUserEditVerificationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SubUserEditVerificationComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(SubUserEditVerificationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
