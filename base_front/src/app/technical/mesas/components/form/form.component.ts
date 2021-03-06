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
	mesa_id = 0;
	form: FormGroup;

	roles = [];
  puntos_votaciones = [];

	constructor(public route: ActivatedRoute,
				public router: Router,
				private formBuilder: FormBuilder,
				public service: ApirestService,
				public singleton: SingletonService,
				public location: Location)
        {

      this.route.params.subscribe(params => {
        if (params['id']) {
          this.mesa_id = params['id'];
          this.getMesa();
        }
      });

		this.buildForm();
	}

	ngOnInit(): void {
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
			nombre: ['', [Validators.required]],
			punto_votacio_id: ['', [Validators.required]],
		});
	}


	/**
	 *
	 * Get the variable to edit
	 *
	 */

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

  getMesa(){
		this.service.queryGet(`mesa/${this.mesa_id}`).subscribe(
			(response: any) => {
				let result = response;
				this.form.setValue({
					id: result.id ,
					nombre: result.nombre ,
					punto_votacio_id: result.punto_votacio_id
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

		let url = 'mesa';
		if (this.mesa_id != 0)
		{
			url = `mesa/${this.mesa_id}`;
			body.append('_method', 'PATCH');
		}

		//-- Open Loading

		this.service.queryPost(url, body).subscribe(
			(response: any) => {
				//-- Close Loading
				if (response.ok) {
				this.singleton.showAlert({type: 'success', content: 'Transacci??n exitosa!'});
				this.router.navigate(['/tecnico/mesas']);
				} else {
				this.singleton.showAlert({type: 'error', content: response.message});
				}
			},
			(err: any) => {
			}
		);
	}
}
