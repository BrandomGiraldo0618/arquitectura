import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import {ApirestService} from '@core/services/apirest.service';
import {SingletonService} from '@core/services/singleton.service';
import { debounceTime } from 'rxjs/operators';

@Component({
  selector: 'app-index',
  templateUrl: './index.component.html',
  styleUrls: ['./index.component.scss']
})
export class IndexComponent implements OnInit {

  @ViewChild('searchInput', {static: true}) searchInput: ElementRef;

  // -- Confirm modal variables --//
	@ViewChild('modalConfirm') modalConfirm;
	confirmMessage = '';
	punto_votacion_id = 0;

	// -- Variables users
	companies = [];
	puntos_votaciones = [];
	currentPage = 1;
	pages = [];
	totalPages: any = 1;
	perPage = 10;  status = 1;
	search = '';
	placeholderSearch = 'Buscar punto de votación';

	//-- Search Variables
	formSearch: FormGroup;


  constructor(
    private service: ApirestService,
		public singleton: SingletonService,
		private formBuilder: FormBuilder


  ) {
    this.confirmMessage = '¿Está seguro de eliminar este punto de votación?';
	this.buildForm();

  }

  ngOnInit(): void {

	this.getPuntos_votacion('');

	this.singleton.currentSearch.subscribe((search: string) => {
		this.search = search;
		this.getPuntos_votacion('');
	});
  }

  private buildForm() {
    this.formSearch = this.formBuilder.group({
      search: [''],
      rows:[10]
    });
    this.onChangeForm();
  }
  onChangeForm() {
    this.formSearch
      .get('search')
      .valueChanges.pipe(debounceTime(500))
      .subscribe((value) => {
        this.getPuntos_votacion('');
      });
  }

  /**
	 *
	 * List the users
	 *
	 */


  getPuntos_votacion(page): void
	{
		this.singleton.updateLoading(true);
		let url = 'punto_vota';
		url += '?search=' + this.formSearch.get('search').value;
		url += '&rows=' + this.perPage;

		if (page != '') {
			url += '&page=' + page;
		}

		this.service.queryGet(url).subscribe(
			(response: any) => {
				this.puntos_votaciones = response;
				this.singleton.updateLoading(false);
			},
			(err: any) => {
				console.log(err);
			}
		);
	}

	/**
	 *
	 * Get the next page
	 *
	 */

	getPage(page: number) {
	if (page != this.currentPage) {
		this.getPuntos_votacion(page);
	}
	}

	changeRow(e){
		this.perPage = e.target.value;
		this.getPuntos_votacion('');
	   }

	 /**
	 *
	 * Opens the modal to confirm the delete
	 *
	 */

	  confirmDelete(id: number): void {
		this.punto_votacion_id = id;
		this.modalConfirm.openModal();
	}

	/**
	 *
	 * Validates if the user accept or not the delete
	 *
	 */

	onCloseModalConfirm(accepted: boolean): void {
		if (accepted) {
			this.deletePuntos_votacion(this.punto_votacion_id);
		}
	}

	/**
	 *
	 * It calls to the user's delete service
	 *
	 */

	deletePuntos_votacion(punto_votacion_id: number): void {
		const url = 'punto_vota/' + punto_votacion_id;

		this.service.queryDelete(url).subscribe(
			(response: any) => {
				let result = response;

				if (result.ok)
				{
					this.punto_votacion_id = 0;
					this.singleton.showAlert({type: 'success', content: 'Eliminado  Exitosamente!'});
					this.ngOnInit();
				}
				else
				{
					this.singleton.showAlert({type: 'error', content: result.message});
				}
			},
			(err: any) => {
			}
		);
	}
}
