import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MobileRolesPermissionsComponent } from './mobile-roles-permissions.component';

describe('MobileRolesPermissionsComponent', () => {
  let component: MobileRolesPermissionsComponent;
  let fixture: ComponentFixture<MobileRolesPermissionsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MobileRolesPermissionsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MobileRolesPermissionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
