import { Component, OnInit, Input } from '@angular/core';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';

import { Chart } from 'frappe-charts';
import { BsLocaleService } from 'ngx-bootstrap/datepicker';
import { listLocales } from 'ngx-bootstrap/chronos';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import * as moment from 'moment';
import 'moment/locale/es';

@Component({
  selector: 'app-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.scss'],
})
export class ReportComponent implements OnInit {

    info_tipos:any;
    tipo = 0;
    constructor(
        public service: ApirestService,
        public singleton: SingletonService
    ) {}

    ngOnInit(): void {
        
    }

    getReporte(): void
	{
		this.singleton.updateLoading(true);
        let tipo = document.getElementById("tipo") as HTMLInputElement;
        
		let url = tipo.value == '1' ? 'info-camara' : 'info-senado';
        this.tipo = tipo.value == '1' ? 1 : 2;
		this.service.queryGet(url).subscribe(
			(response: any) => {
				this.info_tipos = response;
                console.log(this.info_tipos);
				this.singleton.updateLoading(false);
			},
			(err: any) => {
				console.log(err);
			}
		);
	}
}
