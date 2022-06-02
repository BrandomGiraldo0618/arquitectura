import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { VotosRoutingModule } from './votos-routing.module';
import { LoginComponent } from './components/login/login.component';
import { LayoutComponent } from './components/layout/layout.component';
import { ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from '@shared/shared.module';


@NgModule({
  declarations: [LoginComponent, LayoutComponent],
  imports: [
    CommonModule,
    VotosRoutingModule,
    ReactiveFormsModule,
    SharedModule
  ]
})
export class VotosModule { }
