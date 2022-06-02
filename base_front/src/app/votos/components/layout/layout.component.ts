import {Component, OnInit, ViewChild, ElementRef} from '@angular/core';
import {fromEvent} from 'rxjs';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import {debounceTime, map, distinctUntilChanged, filter} from 'rxjs/operators';
import {SingletonService} from '@core/services/singleton.service';
import {ApirestService} from '@core/services/apirest.service';
import Swal from 'sweetalert2/dist/sweetalert2.js'; 

import * as $ from 'jquery';

@Component({
  selector: 'app-layout',
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.scss']
})
export class LayoutComponent implements OnInit {

	@ViewChild('searchInput', {static: true}) searchInput: ElementRef;
  form: FormGroup;
	collapseMenu = false;
    openMenu = '';

	user: any;
	openProfile = false;
	mediaQuery = 'lg';
	sectionSearch = '';
	placeholderSearch = '';
	titlePage = '¡Te damos la bienvenida!';

  partidos = [];
  candidatos = [];
  candidato_input = true;
  votante = false;

	constructor(
    private formBuilder: FormBuilder,
    public service: ApirestService,
    public singleton: SingletonService,) {

          this.buildForm();
	}

	ngOnInit(): void {
    this.getPartidos();
	}

  private buildForm() {

		this.form = this.formBuilder.group({
			numero_identificacion: ['', [Validators.required]],
			tipo_voto: ['', [Validators.required]],
			partido_id: ['', [Validators.required]],
			candidato_id: [''],
			
		});
	}

	getMediaQuery(): void {
		const width = screen.width;
		this.collapseMenu = false;
		if (width <= 575.98) {
			this.mediaQuery = 'xs';
			this.collapseMenu = true;
		} else if (width <= 767.98) {
			this.mediaQuery = 'sm';
			this.collapseMenu = true;
		} else if (width <= 991.98) {
			this.mediaQuery = 'md';
			this.collapseMenu = true;
		} else if (width <= 1199.98) {
			this.mediaQuery = 'lg';
		}
	}

	initSearch(): void {
		fromEvent(this.searchInput.nativeElement, 'keyup').pipe(
		// get value
		map((event: any) => {
			return event.target.value;
		})
		// if character length greater then 2
		, filter(res => res.length > 2)
		// Time in milliseconds between key events
		, debounceTime(500)
		// If previous query is diffent from current
		, distinctUntilChanged()
		// subscription for response
		).subscribe((text: string) => {
			this.singleton.updateSearch(text);
		});
	}

	onCloseModalUser(data): void {
		this.openProfile = false;
		if (data.saved == 1) {
			this.user = data.user;
			localStorage.setItem('user', JSON.stringify(this.user));
		}
	}


	validarVotante(){

		let documento = document.getElementById('numero_identificacion') as HTMLInputElement;
		
		this.singleton.updateLoading(true);
		let url = `validar-votante/${documento.value}`;
	
		this.service.queryGet(url).subscribe(
			(response: any) => {
				this.votante = response;
				this.singleton.updateLoading(false);
				if(!this.votante){
					Swal.fire({
						title: 'Este usuario no está habilitado para registrar un voto',
						confirmButtonColor: '#c00d0d',
					});
				}
				console.log(this.votante);
			},
			(err: any) => {
				console.log(err);
			}
		);
	}


  getPartidos(): void
	{
		this.singleton.updateLoading(true);
		let url = 'partido';

		this.service.queryGet(url).subscribe(
			(response: any) => {
				this.partidos = response;
				this.singleton.updateLoading(false);
			},
			(err: any) => {
				console.log(err);
			}
		);
	}

  validarLista(){

    let partido_id = document.getElementById('partido_id') as HTMLInputElement;
	let tipo_voto = document.getElementById('tipo_voto') as HTMLInputElement;
    this.singleton.updateLoading(true);
	let url = `validar-partido/${partido_id.value}/${tipo_voto.value}`;

	this.service.queryGet(url).subscribe(
		(response: any) => {
			let respuesta = response;



			if(respuesta.length == 0){
				this.candidato_input = false;
			
        	}else{
				this.candidato_input = true;
			}

			this.candidatos = response;
			this.singleton.updateLoading(false);
			console.log(this.candidato_input );
		},
		(err: any) => {
			console.log(err);
		}
	);
  }

  

  voto(event: Event){

	event.preventDefault();
	if (this.form.invalid) {
		this.form.markAllAsTouched();
		return;
	}

	let values = Object.assign({}, this.form.value);
	let body = new FormData();
	Object.keys(values).forEach(key => body.append(key, values[key]));

	let url = 'voto';

	//-- Open Loading

	this.service.queryPost(url, body).subscribe(
		(response: any) => {

			let respuesta = response;
			console.log(respuesta);
			//-- Close Loading
			if (respuesta.ok) {
				Swal.fire({
					title: respuesta.mensaje,
					confirmButtonColor: '#c00d0d',
				});
				
				setTimeout(() => {
					window.location.reload();
				}, 5000);
			} else {
				Swal.fire({
					title: respuesta.mensaje,
					confirmButtonColor: '#c00d0d',
				});
			}
		},
		(err: any) => {
		}
	);

  }
}