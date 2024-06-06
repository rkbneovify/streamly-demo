import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CommonChannelComponent } from './common-channel.component';

describe('CommonChannelComponent', () => {
  let component: CommonChannelComponent;
  let fixture: ComponentFixture<CommonChannelComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CommonChannelComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CommonChannelComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
