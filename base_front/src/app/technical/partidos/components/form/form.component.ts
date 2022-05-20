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
	partidoId = 0;
	form!: FormGroup;

	roles = [];
	representantes = [];
	
	listas = [
		{id: true, nombre: 'Abierta'},
		{id: false, nombre: 'Cerrada'},
	];

	constructor(public route: ActivatedRoute,
				public router: Router,
				private formBuilder: FormBuilder,
				public service: ApirestService,
				public singleton: SingletonService,
				public location: Location) 
	{

		this.route.params.subscribe(params => {
			if (params['id']) {
				this.partidoId = params['id'];
				this.getPartido();
			}
		});

		this.getRepresentantes();
		this.buildForm();
	}

	ngOnInit(): void {
	}

	/**
	 *
	 * It creates the variable form with validations
	 *
	 */

	private buildForm() {

		this.form = this.formBuilder.group({
			id: [0],
			nombre: ['', [Validators.required]],
			personaId_Rlegal: ['', [Validators.required]],
			listaA_C: ['', [Validators.required]]
		});
	}

	/**
	 *
	 * Get the variable to edit
	 *
	 */

   getRepresentantes() {
		this.service.queryGet('persona').subscribe(
			(response: any) => {
				this.representantes = response;
			},
			(err: any) => {
			}
		);
	}


	getPartido(){
		this.service.queryGet(`partido/${this.partidoId}`).subscribe(
			(response: any) => {
				let result = response;
				this.form.setValue({
					id: result.id ,
					nombre: result.nombre,
					personaId_Rlegal: result.personaId_Rlegal ,
					listaA_C: result.listaA_C
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

		let url = 'partido';
		if (this.partidoId != 0) 
		{
			url = `partido/${this.partidoId}`;
			body.append('_method', 'PATCH');
		}

		//-- Open Loading

		this.service.queryPost(url, body).subscribe(
			(response: any) => {
				//-- Close Loading
				if (response.ok) {
					this.singleton.showAlert({type: 'success', content: 'Transacción exitosa'});
					this.router.navigate(['/tecnico/partidos']);
				} else {
				this.singleton.showAlert({type: 'error', content: response.message});
				}
			},
			(err: any) => {
			}
		);
	}
}
