import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PlaylistScheduleComponent } from './playlist-schedule.component';

describe('PlaylistScheduleComponent', () => {
  let component: PlaylistScheduleComponent;
  let fixture: ComponentFixture<PlaylistScheduleComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PlaylistScheduleComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(PlaylistScheduleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
