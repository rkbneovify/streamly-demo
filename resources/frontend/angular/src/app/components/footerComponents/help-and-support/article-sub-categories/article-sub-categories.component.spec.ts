import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ArticleSubCategoriesComponent } from './article-sub-categories.component';

describe('ArticleSubCategoriesComponent', () => {
  let component: ArticleSubCategoriesComponent;
  let fixture: ComponentFixture<ArticleSubCategoriesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ArticleSubCategoriesComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(ArticleSubCategoriesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
