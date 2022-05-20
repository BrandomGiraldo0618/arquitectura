import {Injectable} from '@angular/core';
import {TranslateService} from '@ngx-translate/core';
// import { ApirestService } from '@core/services/apirest.service';
import {environment} from '@environments/environment';
import {Router} from '@angular/router';
import {BehaviorSubject} from 'rxjs';
import * as CryptoJS from 'crypto-js';

let TOKEN = '';
let CLIENT_KEY = '';
let SECRET_KEY = '';

@Injectable({
  providedIn: 'root'
})
export class SingletonService {

	currentLang = 'en';
	// -- Permite crear un observable para validar el ingreso de datos en el campo búsqueda
	private search = new BehaviorSubject('');
	currentSearch = this.search.asObservable();
	// -- Indica en qué sección del sistema se genera la búsqueda
	private sectionSearch = new BehaviorSubject('');
	currentSectionSearch = this.sectionSearch.asObservable();
	// -- Muestra alerta sobre el componente app
	private alert = new BehaviorSubject({});
	currentAlert = this.alert.asObservable();

	private loading = new BehaviorSubject(false);
	currentLoading = this.loading.asObservable();

	permissions = [];

	constructor(public translate: TranslateService,
							// private service: ApirestService,
							private router: Router) {
		TOKEN = localStorage.getItem('token');
		// CLIENT_KEY = environment.clientSecret;
		// SECRET_KEY = environment.secretKey;
	}

	/**
	 *
	 * Update the search field
	 *
	 */

	updateSearch(search: string): void {
		this.search.next(search);
	}

	/**
	 *
	 * Update the search section
	 *
	 */

	updateSectionSearch(section: string): void {
		this.sectionSearch.next(section);
	}

	/**
	 *
	 * Update the alert message
	 *
	 */

	showAlert(alert: object): void {
		this.alert.next(alert);
	}

	updateLoading(loading: boolean): void {
		this.loading.next(loading);
	}

	/**
	 * Returns the pages to render in pagination
	 * @param array result
	 */
	 pagination(result)
	 {
		 let currentPage = result['meta']['current_page'];
		 let first_page_url = result['meta']['first'];
		 let lastPage = result['meta']['last_page'];
		 let pages = [];
		 let from = 1;
		 let to = lastPage;
 
		 if(result['data'].length > 0)
		 {
			 pages.push(['&laquo;', 1, '']);
			 if(currentPage == 1)
			 {
				 pages.push(['&lt;', 1, '']);
			 }
			 else if(currentPage > 1)
			 {
				 pages.push(['&lt;', currentPage - 1, '']);
				 if(currentPage > 3)
				 {
					 from = currentPage - 2;
				 }
			 }
 
			 if(from + 5 > lastPage)
			 {
				 to = lastPage;
			 }
			 else
			 {
				 to = from + 5;
			 }
 
			 for (var i = from; i <= to; ++i) 
			 {
				 if(i == currentPage)
				 {
					 pages.push([i+'', i, 'active']);
				 }
				 else
				 {
					 pages.push([i+'', i, '']);
				 }
			 }
 
			 if(currentPage + 1 < lastPage)
			 {
				 pages.push(['&gt;', currentPage + 1, '']);
			 }
			 else
			 {
				 pages.push(['&gt;', lastPage, '']);
			 }
 
			 pages.push(['&raquo;', lastPage, '']);
		 }
 
		 return pages;
	 }

	// The set method is use for encrypt the value.
	encrypt(data) {
		const ciphertext = CryptoJS.AES.encrypt(
			JSON.stringify(data),
			SECRET_KEY
		);
		return ciphertext;
	}

	// The get method is use for decrypt the value.
	decrypt(data) {
		const bytes = CryptoJS.AES.decrypt(data.toString(), SECRET_KEY);
		const decryptedData = bytes.toString(CryptoJS.enc.Utf8);
		return decryptedData;
	}

	/**
	 *
	 * It validates if the user has permission to
	 *
	 */

	hasPermission(permissions) {
		return this.permissions.some((p) => {
			return permissions.indexOf(p) >= 0;
		});
	}

	setUser(user) {
		const auth_value = this.encrypt(
			JSON.stringify(user)
		);
		sessionStorage.setItem("auth", auth_value);
		localStorage.setItem("auth", auth_value);
	}

	getSessionUser() {
		const user = localStorage.getItem('auth');
		if (user) {
			const authData = this.decrypt(user);
			const data = JSON.parse(JSON.parse(authData));
			return data;
		} else {
			return null;
		}
	}
}