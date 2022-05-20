import {Injectable} from '@angular/core';
import {HttpClient, HttpErrorResponse} from '@angular/common/http';
import {environment} from '@environments/environment';
import {Router} from '@angular/router';
import {Observable, throwError} from 'rxjs';
import {map, catchError} from 'rxjs/operators';
import {SingletonService} from './singleton.service';

import * as Sentry from '@sentry/browser';

@Injectable({
  providedIn: 'root'
})
export class ApirestService {

	apiUrl = '';

	constructor(public http: HttpClient,
				private router: Router,
				public singleton: SingletonService) {
		this.http = http;
		this.apiUrl = environment.apiUrl;
	}

	queryPostRegular(route: string, body: any) {
		return this.http.post(environment.apiUrl.concat(route), body)
		.pipe(
			catchError((error: any) => {
			this.handleError(error);
			throw('ups algo salió mal');
			})
		);
	}

	queryGet(route: string) {
		return this.http.get(environment.apiUrl.concat(route))
		.pipe(
			catchError((error: any) => {
			this.handleError(error);
			throw('ups algo salió mal');
			})
		);
	}

	queryPost(route: string, body: any) {
		return this.http.post(environment.apiUrl.concat(route), body)
		.pipe(
			catchError((error: any) => {
			this.handleError(error);
			throw('ups algo salió mal');
			})
		);
	}

	queryPut(route: string, body: any) {
		return this.http.put(environment.apiUrl.concat(route), body)
		.pipe(
			catchError((error: any) => {
			this.handleError(error);
			throw('ups algo salió mal');
			})
		);
	}

	queryDelete(route: string) {
		return this.http.delete(environment.apiUrl.concat(route))
		.pipe(
			catchError((error: any) => {
			this.handleError(error);
			throw('ups algo salió mal');
			})
		);
	}

	queryExternalApi(route: string) {
		return this.http.get(route)
		.pipe(
			catchError((error: any) => {
			this.handleError(error);
			throw('ups algo salió mal');
			})
		);
	}

	private handleError(error: any): void {
		// if (environment.production) {
		// 	Sentry.captureException(error);
		// }

		let message = '';
		let logout = false;
		let navigateTo = '';

		if (error.status == 404) {
			message = 'El servidor no pudo procesar la solicitud!';
			logout = true;
		} else if (error.status === 500) {
			this.router.navigate(['/error-500']);
		} else if (error.status === 422) {
			const result = error;

			if (result.error && result.error.errors) {
				const error = result.error;
				for (const key of Object.keys(error.errors)) {
					const errors = error.errors[key];
					if (Array.isArray(errors) == true) {
						errors.forEach((err) => {
						message += err + '<br>';
						});
					}
				}
			} else {
				message = result.message;
			}
		} else if (error.status == 401 || error.status == 403) {
			message = 'No está autorizado para realizar esta acción!';
			navigateTo = '/';
			localStorage.clear();
			//this.router.navigate(['/auth/login']);
		} else if (error.status == 503) {
			message = 'Algo no va bien, parece que no tiene conexión!';
			logout = true;
		}

		if (message) {
			this.singleton.showAlert({type: 'error', content: message});
		}
		if (logout) {
			this.logout();
		} else if (navigateTo) {
			this.router.navigate([navigateTo]);
		}
	}

	downloadExcel(route){
		window.open(environment.apiUrl.concat(route), '_blank');
	}

	/**
	 *
	 * Finish the session
	 *
	 */

	logout(): void {
		const body = new FormData();
		this.queryPost('logout', body).subscribe(
			(response: any) => {
				localStorage.clear();
				this.router.navigate(['/']);
			},
			err => { }
		);
	}

	/**
	 *
	 * Set a language to the app
	 *
	 */

	setLanguage(language: string): void {
		const url = 'set-locale/' + language;

		this.queryGet(url).subscribe(
			(response: any) => {
			},
			err => { }
		);
	}

	/**
	 *
	 * List the permissions of the user
	 *
	 */

	getPermissions(): void {
		const url = 'permissions-user';

		this.queryGet(url).subscribe(
			(response: any) => {
				if (response.success) {
					this.singleton.permissions = response.data;
				}
			},
			err => { }
		);
	}
}