import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PersonasRoutingModule } from './personas-routing.module';
import { SharedModule } from '@shared/shared.module';
import { ReactiveFormsModule , FormsModule} from '@angular/forms';
import { IndexComponent } from './components/index/index.component';
import { FormComponent } from './components/form/form.component';


@NgModule({
  declarations: [IndexComponent, FormComponent],
  imports: [
    CommonModule,
    PersonasRoutingModule,
    ReactiveFormsModule,
    SharedModule,
    FormsModule
  ]
})
export class PersonasModule { }
