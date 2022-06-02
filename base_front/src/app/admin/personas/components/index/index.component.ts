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
	funcionarioId = 0;
	funcionarios = [];
	// -- Variables users
	currentPage = 1;
	pages = [];
	totalPages: any = 1;
	perPage = 10;  status = 1;
	search = '';
	placeholderSearch = 'Buscar en funcionarios';

	//-- Search Variables
	formSearch: FormGroup;


  constructor(
    private service: ApirestService,
		public singleton: SingletonService,
		private formBuilder: FormBuilder
  ) {
    this.confirmMessage = '¿Está seguro de eliminar esta persona?';
	this.buildForm();
  }

  ngOnInit(): void {

	this.getFuncionarios('');

	this.singleton.currentSearch.subscribe((search: string) => {
		this.search = search;
		this.getFuncionarios('');
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
        this.getFuncionarios('');
      });
  }

  /**
	 *
	 * List the users
	 *
	 */

   getFuncionarios(page): void
	{
		this.singleton.updateLoading(true);
		let url = 'persona';
		url += '?search=' + this.formSearch.get('search').value;
		url += '&rows=' + this.perPage;

		if (page != '') {
			url += '&page=' + page;
		}

		this.service.queryGet(url).subscribe(
			(response: any) => {
				this.funcionarios = response;
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
	  this.getFuncionarios(page);
	}
	}

	changeRow(e){
		this.perPage = e.target.value;
		this.getFuncionarios('');
	   }

	 /**
	 *
	 * Opens the modal to confirm the delete
	 *
	 */

	  confirmDelete(id: number): void {
		this.funcionarioId = id;
		this.modalConfirm.openModal();
	}

	/**
	 *
	 * Validates if the user accept or not the delete
	 *
	 */

	onCloseModalConfirm(accepted: boolean): void {
		if (accepted) {
			this.deleteFuncionario(this.funcionarioId);
		}
	}

	/**
	 *
	 * It calls to the user's delete service
	 *
	 */

	 deleteFuncionario(funcionarioId: number): void {
		const url = 'persona/' + funcionarioId;

		this.service.queryDelete(url).subscribe(
			(response: any) => {
				let result = response;

				if (result.ok)
				{
					this.funcionarioId = 0;
					this.singleton.showAlert({type: 'success', content: 'Funcionario eliminado'});
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
