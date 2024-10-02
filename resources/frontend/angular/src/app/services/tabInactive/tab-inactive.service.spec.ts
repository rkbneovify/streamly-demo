import { TestBed } from '@angular/core/testing';

import { TabInactiveService } from './tab-inactive.service';

describe('TabInactiveService', () => {
  let service: TabInactiveService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(TabInactiveService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
