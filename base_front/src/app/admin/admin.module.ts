import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ReactiveFormsModule} from '@angular/forms';

import {SharedModule} from '@shared/shared.module';

import {AdminRoutingModule} from '@admin/admin-routing.module';
import {LayoutComponent} from '@admin/components/layout/layout.component';
import {DashboardComponent} from '@admin/dashboard/dashboard.component';
import {MobileHeaderComponent} from './mobile/mobile-header/mobile-header.component';
import { NotificationSideMenuComponent } from './components/notification-side-menu/notification-side-menu.component';


@NgModule({
  declarations: [
    LayoutComponent,
    DashboardComponent,
    MobileHeaderComponent,
    NotificationSideMenuComponent,
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    SharedModule,
    AdminRoutingModule
  ]
})
export class AdminModule {
}
