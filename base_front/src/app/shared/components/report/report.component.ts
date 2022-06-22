import { Component, OnInit, Input } from '@angular/core';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';
import Swal from 'sweetalert2/dist/sweetalert2.js'; 
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

    openModal(info){
        
        let candidatos = info.candidatos_ganaron.length == 1 ? info.candidatos_ganaron[0] : info.candidatos_ganaron;
        let table = `<table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Candidato</th>
                                <th scope="col">Cantidad de Votos</th>
                            </tr>
                        </thead>
                        <tbody>`;

        candidatos.forEach(element => {
            table += `<tr>
                        <td>${element.nombre_candidato}</td>
                        <td>${element.cantidad_votos}</td>
                    </tr>`;
        });
        
        table += `</tbody>
                    </table>`;
        Swal.fire({
            title: `Partido ${info.nombre_partido}`,
            html: table,
            confirmButtonColor: '#c00d0d',
        });
    }
}
