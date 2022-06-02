import {Component, OnInit} from '@angular/core';
import {FormGroup, Validators, FormBuilder} from '@angular/forms';
import {ApirestService} from '@core/services/apirest.service';
import {SingletonService} from '@core/services/singleton.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

	form: FormGroup;

	constructor(private formBuilder: FormBuilder,
				private service: ApirestService,
				private singleton: SingletonService,
				private router: Router) {
		this.buildForm();
	}

	ngOnInit(): void {
	}

	/**
	 *
	 * It creates the login form with validations
	 *
	 */

	private buildForm(): void {
		this.form = this.formBuilder.group({
		email: ['', [Validators.required, Validators.email]],
		password: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(20)]],
		});
	}

	/**
	 *
	 * Login function
	 *
	 */

	login(event: Event): void 
	{
		event.preventDefault();
		if (this.form.invalid) 
		{
			this.form.markAsUntouched();
			return;
		}

		const body = Object.assign(this.form.value, {});

		this.service.queryPostRegular('login', body).subscribe(
			(response: any) => {
				if (response.success) 
				{
					const user = response.user;
					localStorage.setItem('token', 'Bearer ' + response.token);

					this.singleton.setUser(user);

					if (user.role_id == 1) 
					{
						this.router.navigate(['/admin/personas']);
					}
					else if(user.role_id == 2)
					{
						this.router.navigate(['/tecnico/personas']);
					}
				}
				else 
				{
					this.singleton.showAlert({type: 'error', content: response.message});
				}
			},
			(err: any) => {
			}
		);
	}
}
