import { Component, OnInit } from '@angular/core';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';

@Component({
  selector: 'app-info',
  templateUrl: './info.component.html',
  styleUrls: ['./info.component.scss']
})
export class InfoComponent implements OnInit {

  totalVotos: 0;
  totalVotosCamara:0;
  totalVotosSenado:0;
  totalHabilitados:0;
  constructor(
		public singleton: SingletonService,
		public service: ApirestService
	  ) { 
      this.getTotalVotos();
    }

  ngOnInit(): void 
  {
		this.getTotalVotos();
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
