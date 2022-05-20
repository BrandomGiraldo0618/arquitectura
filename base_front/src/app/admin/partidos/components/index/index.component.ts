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
	partidoId = 0;

	// -- Variables users
	companies = [];
	partidos = [];
	currentPage = 1;
	pages = [];
	totalPages: any = 1;
	perPage = 10;  status = 1;
	search = '';
	placeholderSearch = 'Buscar en partidos';

	//-- Search Variables
	formSearch: FormGroup;
	

  constructor(
    private service: ApirestService,
		public singleton: SingletonService,
		private formBuilder: FormBuilder
  ) {
    this.confirmMessage = '¿Está seguro de eliminar este partido?';
	this.buildForm();
   }

  ngOnInit(): void {

	//this.getIncoterms('');

	this.singleton.currentSearch.subscribe((search: string) => {
		this.search = search;
		this.getPartidos('');
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
        this.getPartidos('');
      });
  }

  /**
	 *
	 * List the users
	 *
	 */

   getPartidos(page): void 
	{
		this.singleton.updateLoading(true);
		let url = 'partido';
		url += '?search=' + this.formSearch.get('search').value;
		url += '&rows=' + this.perPage;

		if (page != '') {
			url += '&page=' + page;
		}

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

	/**
	 *
	 * Get the next page
	 *
	 */

	getPage(page: number) {
	if (page != this.currentPage) {
	  this.getPartidos(page);
	}
	}

	changeRow(e){
		this.perPage = e.target.value;
		this.getPartidos('');
	   }

	 /**
	 *
	 * Opens the modal to confirm the delete
	 *
	 */

	  confirmDelete(id: number): void {
		this.partidoId = id;
		this.modalConfirm.openModal();
	}

	/**
	 *
	 * Validates if the user accept or not the delete
	 *
	 */

	onCloseModalConfirm(accepted: boolean): void {
		if (accepted) {
			this.deletePartido(this.partidoId);
		}
	}

	/**
	 *
	 * It calls to the user's delete service
	 *
	 */

	 deletePartido(partidoId: number): void {
		const url = 'partido/' + partidoId;
		this.service.queryDelete(url).subscribe(
			(response: any) => {
				let result = response;			
				if (result.ok) 
				{
					this.partidoId = 0;
					this.singleton.showAlert({type: 'success', content: 'Partido eliminado exitosamente'});
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
