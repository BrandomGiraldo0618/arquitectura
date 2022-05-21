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

	user: any = {
		id : 0
	};
	userId = 0;
	form!: FormGroup;

	roles = [];

	companyId = 0;

	constructor(public route: ActivatedRoute,
				public router: Router,
				private formBuilder: FormBuilder,
				public service: ApirestService,
				public singleton: SingletonService,
				public location: Location) {

		this.route.params.subscribe(params => {
			if (params['id']) {
				this.companyId = params['id'];
			}
			if(params['userId'])
			{
				this.userId = params['userId'];
				this.getUser();
			}
		});

		this.buildForm();
	}

	ngOnInit(): void {
	}

	/**
	 *
	 * It creates the user form with validations
	 *
	 */

	private buildForm() {
		let validatorsPassword = [Validators.minLength(6), Validators.maxLength(20)];

		if (this.userId == 0) {
			validatorsPassword.push(Validators.required);
		}

		this.form = this.formBuilder.group({
			id: [0],
			name: ['', [Validators.required]],
			address: ['', [Validators.required]],
			email: ['', [Validators.required, Validators.email]],
			cellphone: ['', [Validators.required]],
			role_id: [4],
			status: ['', [Validators.required]],
			password: ['', validatorsPassword],
			confirm_password: ['', validatorsPassword]
		});
	}

	/**
	 *
	 * Get the user to edit
	 *
	 */

	getUser() {
		this.service.queryGet('companies-users/' + this.userId).subscribe(
			(response: any) => {

				let result = response;

				
				let user = result.data.user;

				this.user = user;

				let active = 0;
				if(1 == user.active)
				{
					active = 1;
				}

				this.form.setValue({
					id: user.id,
					name: user.name,
					email: user.email,
					address: user.address,
					cellphone: user.cellphone,
					role_id: user.role_id,
					status: active,
					password : '',
					confirm_password : ''
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

		let url = 'companies-users';
		if (this.userId != 0) 
		{
			url = `companies-users/${this.userId}`;
			body.append('_method', 'PATCH');
		}

		if(null == this.user)
		{
			this.user = {
				id : 0
			}
		}

		body.append('user_id', this.user.id);
		body.append('company_id', this.companyId + '')

		//-- Open Loading

		this.service.queryPost(url, body).subscribe(
			(response: any) => {
				//-- Close Loading
				if (response.success) {
				this.singleton.showAlert({type: 'success', content: response.message});
				this.router.navigate([`/admin/empresas/${this.companyId}/usuarios`]);
				} else {
				this.singleton.showAlert({type: 'error', content: response.message});
				}
			},
			(err: any) => {
			}
		);
	}
}
