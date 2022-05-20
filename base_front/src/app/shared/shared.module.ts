import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

// -- Plugins
import {TranslateModule} from '@ngx-translate/core';
import {CKEditorModule} from '@ckeditor/ckeditor5-angular';

import {Error500Component} from './components/error500/error500.component';
import {Error404Component} from './components/error404/error404.component';
import {ModalConfirmComponent} from './components/modal-confirm/modal-confirm.component';
import {ModalMessageComponent} from './components/modal-message/modal-message.component';
import {ValidationErrorComponent} from './components/validation-error/validation-error.component';
import {NgxDatatableModule} from '@swimlane/ngx-datatable';
import { NgxUploaderModule } from 'ngx-uploader';
import { ReactiveFormsModule,FormsModule } from '@angular/forms';
import { MapComponent } from './components/map/map.component'
import { AgmCoreModule } from '@agm/core';
import { LandingComponent } from './components/landing/landing.component';
import { DetailDeviceComponent } from './components/detail-device/detail-device.component';
import { ModalBitacoraComponent } from './components/modal-bitacora/modal-bitacora.component';
import { ReportComponent } from './components/report/report.component';
import { BsDatepickerModule } from 'ngx-bootstrap/datepicker';
import { defineLocale, esLocale } from 'ngx-bootstrap/chronos';
import { deLocale, frLocale, plLocale } from 'ngx-bootstrap/locale';
import { InfoComponent } from './components/info/info.component';
import { ModalCommentComponent } from './components/modal-comment/modal-comment.component';
import { ModalImportComponent } from './components/modal-import/modal-import.component';
 defineLocale('es', esLocale);


@NgModule(
  {
  declarations: [
    Error500Component,
    Error404Component,
    ModalConfirmComponent,
    ModalMessageComponent,
    ValidationErrorComponent,
    MapComponent,
    LandingComponent,
    DetailDeviceComponent,
    ModalBitacoraComponent,
    ReportComponent,
    InfoComponent,
    ModalCommentComponent,
    ModalImportComponent
  ],
  imports: [
    CommonModule,
    TranslateModule,
    CKEditorModule,
    NgxUploaderModule,
    FormsModule,
    AgmCoreModule.forRoot({
      apiKey: 'AIzaSyCrxe4yirskGPWA1SeFIijsUa9vheHvGwI',
    }),
    ReactiveFormsModule,
    BsDatepickerModule.forRoot(),
  ],
  exports: [
    TranslateModule,
    CKEditorModule,
    Error500Component,
    Error404Component,
    ModalConfirmComponent,
    ModalMessageComponent,
    ValidationErrorComponent,
    NgxDatatableModule,
    NgxUploaderModule,
    FormsModule,
    MapComponent,
    AgmCoreModule,
    DetailDeviceComponent,
    ModalBitacoraComponent,
    ReportComponent,
    InfoComponent,
    ModalImportComponent
  ]
})
export class SharedModule {
}
