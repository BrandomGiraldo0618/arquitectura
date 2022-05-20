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

	// -- Variables users
	users = [];
	currentPage = 1;
	pages = [];
	totalPages: any = 1;
	perPage = 10;  status = 1;
	search = '';
	placeholderSearch = 'Buscar en usuarios';

	// -- Confirm modal variables --//
	@ViewChild('modalConfirm') modalConfirm;
	confirmMessage = '';
	userId = 0;

	//-- Search Variables
	formSearch: FormGroup;
	options = [
		{name:"20",value:20},
		{name:"50",value:50},
		{name:"100",value:100}
	  ]


	constructor(
		private service: ApirestService,
		public singleton: SingletonService,
		private formBuilder: FormBuilder) 
	{
		this.confirmMessage = '¿Está seguro de eliminar este usuario?';
		this.buildForm();
		
	}

	ngOnInit(): void {
		this.getUsers('');

		this.singleton.currentSearch.subscribe((search: string) => {
			this.search = search;
			this.getUsers('');
		});
	}

	AfterViewInit(): void {
		this.singleton.updateSectionSearch('users');
	}

	OnDestroy(): void {
		this.singleton.updateSectionSearch('');
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
			this.getUsers('');
		  });
	  }

	/**
	 *
	 * List the users
	 *
	 */

	getUsers(page): void 
	{
		this.singleton.updateLoading(true);

		let url = 'users';
		url += '?search=' + this.formSearch.get('search').value;
		url += '&rows=' + this.perPage;

		if (page) url += '&page=' + page;

		this.service.queryGet(url).subscribe(
			(response: any) => {
				this.users = response.data;
				this.totalPages = response.meta;
				this.currentPage = response['current_page'];
				this.pages = this.singleton.pagination(response);
				this.singleton.updateLoading(false);
			},
			(err: any) => {
			}
		);
	}

	/**
	 *
	 * Get the next page
	 *
	 */
	 getPage(page: number) 
	 {
		if (page != this.currentPage) 
		{
		  this.getUsers(page);
		}	
	}

	changeRow(e)
	{
		this.perPage = e.target.value;
		this.getUsers('');
	}

	/**
	 *
	 * Opens the modal to confirm the delete
	 *
	 */


	 confirmDelete(id: number): void {
		this.userId = id;
		this.modalConfirm.openModal();
	}

	/**
	 *
	 * Validates if the user accept or not the delete
	 *
	 */

	 onCloseModalConfirm(accepted: boolean): void {
		if (accepted) {
			this.deleteUser(this.userId);
		}
	}

	/**
	 *
	 * It calls to the user's delete service
	 *
	 */

	deleteUser(userId: number): void {
		const url = 'users/' + userId;

		this.service.queryDelete(url).subscribe(
			(response: any) => {
				let result = response;

				if (response.success) {

					this.userId = 0;
					this.singleton.showAlert({type: 'success', content: result.message});
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

	changeStatus(id)
	{
		let body = new FormData();
		body.append('_method', 'PATCH');

		this.service.queryPost(`users/${id}/change-status` , body)
			.subscribe(
				(response:any) =>
				{
					let result = response;
					
					this.singleton.showAlert({type: 'success', content: result.message});
					this.ngOnInit();
				},
				(err:any) =>
				{
					console.log(err);
					
				}
			)
	}



}
