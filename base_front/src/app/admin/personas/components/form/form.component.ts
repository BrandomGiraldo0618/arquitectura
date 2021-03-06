import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ApirestService} from '@core/services/apirest.service';
import {SingletonService} from '@core/services/singleton.service';
import {FormGroup, Validators, FormBuilder} from '@angular/forms';
import {Location} from '@angular/common';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.scss']
})
export class FormComponent implements OnInit {

	variable: any;
	personaId = 0;
	form!: FormGroup;
	
	roles = [];

  mesas = [];
	tipos_candidatos = [];
	partidos = [];
	puntos_votaciones = [];
	tipo_funcionario : 0;
	votante = false;
	jurado = false;
	candidato = false;

	constructor(public route: ActivatedRoute,
				public router: Router,
				private formBuilder: FormBuilder,
				public service: ApirestService,
				public singleton: SingletonService,
				public location: Location)
        {

		this.route.params.subscribe(params => {
			if (params['id']) {
				this.personaId = params['id'];
				this.getPersona();
			}
		});

		this.buildForm();
	}

	ngOnInit(): void {
		this.getTiposCandidatos();
		this.getPartidos();
		this.getPuntoVotacion();
	}

	/**
	 *
	 * It creates the variable form with validations
	 *
	 */

	private buildForm() {
		this.form = this.formBuilder.group({
			id: [0],
			tipo_Documento: ['', [Validators.required]],
			numero_Documento: ['', [Validators.required]],
			nombre: ['', [Validators.required]],
			apellido: ['', [Validators.required]],
			lugar_Nacimiento: ['', [Validators.required]],
			fecha_Nacimiento: ['', [Validators.required]],
			mesa_id: [''],
			tipo_funcionario: [''],
			partido_id: [''],
			tipo_candidato_id: [''],
			punto_votacion_id: ['']
		});
	}

	/**
	 *
	 * Get the variable to edit
	 *
	 */

   getMesas() {
	   let punto_id = document.getElementById('punto_votacion_id') as HTMLInputElement;

		this.service.queryGet(`mesa/consultar-por-punto-votacion/${punto_id.value}`).subscribe(
			(response: any) => {
				let result = response;
				this.mesas = result;
			},
			(err: any) => {
			}
		);
	}

   getTiposCandidatos() {
		this.service.queryGet('tipo').subscribe(
			(response: any) => {
				let result = response;
				this.tipos_candidatos = result;
			},
			(err: any) => {
			}
		);

	}

   getPartidos() {
		this.service.queryGet('partido').subscribe(
			(response: any) => {
				let result = response;
				this.partidos = result;
			},
			(err: any) => {
			}
		);
	}

   getPuntoVotacion() {
		this.service.queryGet('punto_vota').subscribe(
			(response: any) => {
				let result = response;
				this.puntos_votaciones = result;
			},
			(err: any) => {
			}
		);
	}

	TipoFuncinarioChange(){
		let tipo_funcionario = document.getElementById('tipo_funcionario') as HTMLInputElement;

		switch (tipo_funcionario.value) {
			case '1':
					this.votante = true;
					this.jurado = false;
					this.candidato = false;
					break;
			case '2':
				this.votante = false;
				this.jurado = true;
				this.candidato = false;
				break;
			case '3':
				this.votante = false;
				this.jurado = false;
				this.candidato = true;
				break;
			case '4':
				this.votante = false;
				this.jurado = false;
				this.candidato = false;
				break;

			default:
				this.votante = false;
				this.jurado = false;
				this.candidato = false;
				break;
		}
	}

	getPersona(){
		this.service.queryGet(`persona/${this.personaId}`).subscribe(
			(response: any) => {
				let result = response;
				this.form.setValue({
					id: result.id ,
					tipo_Documento: result.tipo_Documento ,
					numero_Documento: result.numero_Documento ,
					nombre: result.nombre ,
					apellido: result.apellido,
					lugar_Nacimiento: result.lugar_Nacimiento,
					fecha_Nacimiento: result.fecha_Nacimiento,
					mesa_id: '1',
					tipo_funcionario: '1',
					partido_id: '1',
					tipo_candidato_id: '1',
					punto_votacion_id: '1',
				});
			},
			(err: any) => {
			}
		);
	}

	/**
	 *
	 * Create or update function
	 *
	 */

	save(event: Event) {

		event.preventDefault();
		if (this.form.invalid) {
			this.form.markAllAsTouched();
			return;
		}

		let values = Object.assign({}, this.form.value);
		let body = new FormData();
		Object.keys(values).forEach(key => body.append(key, values[key]));

		let url = 'persona';
		if (this.personaId != 0)
		{
			url = `persona/${this.personaId}`;
			body.append('_method', 'PATCH');
		}

		//-- Open Loading
		this.service.queryPost(url, body).subscribe(
			(response: any) => {
				//-- Close Loading
				console.log(response);
				if (response.ok) {
					this.singleton.showAlert({type: 'success', content: 'Transacci??n exitosa'});
					this.router.navigate(['/admin/personas']);
				} else {
					this.singleton.showAlert({type: 'error', content: response.message});
				}
			},
			(err: any) => {
			}
		);
	}
}
