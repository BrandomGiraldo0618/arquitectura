import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PartidoRoutingModule } from './partidos-routing.module';
import { SharedModule } from '@shared/shared.module';
import { ReactiveFormsModule , FormsModule} from '@angular/forms';
import { IndexComponent } from './components/index/index.component';
import { FormComponent } from './components/form/form.component';


@NgModule({
  declarations: [IndexComponent, FormComponent],
  imports: [
    CommonModule,
    PartidoRoutingModule,
    ReactiveFormsModule,
    SharedModule,
    FormsModule
  ]
})
export class PartidosModule { }
