import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LocationAccessComponent } from './location-access.component';

describe('LocationAccessComponent', () => {
  let component: LocationAccessComponent;
  let fixture: ComponentFixture<LocationAccessComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LocationAccessComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(LocationAccessComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
