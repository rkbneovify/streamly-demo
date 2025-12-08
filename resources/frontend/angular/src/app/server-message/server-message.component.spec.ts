import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ServerMessageComponent } from './server-message.component';

describe('ServerMessageComponent', () => {
  let component: ServerMessageComponent;
  let fixture: ComponentFixture<ServerMessageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ServerMessageComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(ServerMessageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
