import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ConnectedHousesComponent } from './connected-houses.component';

describe('ConnectedHousesComponent', () => {
  let component: ConnectedHousesComponent;
  let fixture: ComponentFixture<ConnectedHousesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ConnectedHousesComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(ConnectedHousesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
