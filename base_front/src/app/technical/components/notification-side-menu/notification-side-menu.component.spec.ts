import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NotificationSideMenuComponent } from './notification-side-menu.component';

describe('NotificationSideMenuComponent', () => {
  let component: NotificationSideMenuComponent;
  let fixture: ComponentFixture<NotificationSideMenuComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NotificationSideMenuComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(NotificationSideMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
