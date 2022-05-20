import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { TechnicalRoutingModule } from './technical-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from '@shared/shared.module';
import { LayoutComponent } from './components/layout/layout.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { MobileHeaderComponent } from './mobile-header/mobile-header.component';
import { NotificationSideMenuComponent } from './components/notification-side-menu/notification-side-menu.component';
import { FormComponent } from './components/form/form.component';


@NgModule({
  declarations: [
    LayoutComponent,
    DashboardComponent,
    MobileHeaderComponent,
    NotificationSideMenuComponent,
    FormComponent
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    SharedModule,
    TechnicalRoutingModule
  ]
})
export class TechnicalModule { }
