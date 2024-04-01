import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PlaystreamComponent } from './playstream.component';

describe('PlaystreamComponent', () => {
  let component: PlaystreamComponent;
  let fixture: ComponentFixture<PlaystreamComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PlaystreamComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(PlaystreamComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
