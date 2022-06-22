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
    totalVotos: 0;
    totalVotosCamara:0;
    totalVotosSenado:0;
    totalHabilitados:0;

    constructor(
        public service: ApirestService,
        public singleton: SingletonService
    ) {}

    ngOnInit(): void {
        this.getTotalVotos();
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
                this.info_tipos.forEach(element => {
                    element['width_style'] = `width: ${Math.round(element.total_votos_partido / (this.tipo == 1 ? this.totalVotosCamara : this.totalVotosSenado) * 100)}%`;
                    element['width'] = `${Math.round(element.total_votos_partido / (this.tipo == 1 ? this.totalVotosCamara : this.totalVotosSenado) * 100)}%`;
                });
                console.log(this.info_tipos);
				this.singleton.updateLoading(false);
			},
			(err: any) => {
				console.log(err);
			}
		);
	}

    getTotalVotos(){
		this.service.queryGet('total-votos').subscribe(
			(response: any) => {
				this.totalVotos = response.total_votos;
                this.totalVotosCamara = response.total_votos_camara;
                this.totalVotosSenado = response.total_votos_senado;
                this.totalHabilitados = response.total_habilitados;
				this.singleton.updateLoading(false);
			},
			(err: any) => {
				console.log(err);
			}
		);
	}
}
