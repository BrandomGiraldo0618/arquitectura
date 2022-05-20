import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from '@shared/shared.module';
import { PermissionsRoutingModule } from './permissions-routing.module';

import { IndexComponent } from './components/index/index.component';
import { IndexMobileComponent } from './mobile/index-mobile/index-mobile.component';

@NgModule({
  declarations: [
    IndexComponent,
    IndexMobileComponent
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    PermissionsRoutingModule,
    SharedModule
  ]
})
export class PermissionsModule { }
