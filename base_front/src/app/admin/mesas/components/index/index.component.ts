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
	mesa_id = 0;

	// -- Variables users
	companies = [];
	mesas = [];
	currentPage = 1;
	pages = [];
	totalPages: any = 1;
	perPage = 10;  status = 1;
	search = '';
	placeholderSearch = 'Buscar Mesa';

	//-- Search Variables
	formSearch: FormGroup;


  constructor(
    private service: ApirestService,
		public singleton: SingletonService,
		private formBuilder: FormBuilder


  ) {
    this.confirmMessage = '¿Está seguro de eliminar esta Mesa?';
	this.buildForm();

  }

  ngOnInit(): void {

	this.getMesas('');

	this.singleton.currentSearch.subscribe((search: string) => {
		this.search = search;
		this.getMesas('');
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
        this.getMesas('');
      });
  }

  /**
	 *
	 * List the users
	 *
	 */


   getMesas(page): void
	{
		this.singleton.updateLoading(true);
		let url = 'mesa';
		url += '?search=' + this.formSearch.get('search').value;
		url += '&rows=' + this.perPage;

		if (page != '') {
			url += '&page=' + page;
		}

		this.service.queryGet(url).subscribe(
			(response: any) => {
				this.mesas = response;
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
	  this.getMesas(page);
	}
	}

	changeRow(e){
		this.perPage = e.target.value;
		this.getMesas('');
	   }

	 /**
	 *
	 * Opens the modal to confirm the delete
	 *
	 */

	  confirmDelete(id: number): void {
		this.mesa_id = id;
		this.modalConfirm.openModal();
	}

	/**
	 *
	 * Validates if the user accept or not the delete
	 *
	 */

	onCloseModalConfirm(accepted: boolean): void {
		if (accepted) {
			this.deleteMesa(this.mesa_id);
		}
	}

	/**
	 *
	 * It calls to the user's delete service
	 *
	 */

	 deleteMesa(mesa_id: number): void {
		const url = 'mesa/' + mesa_id;

		this.service.queryDelete(url).subscribe(
			(response: any) => {
				let result = response;

				if (result.ok)
				{
					this.mesa_id = 0;
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
