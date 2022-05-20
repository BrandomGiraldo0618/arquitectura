import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
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
	userId = 0;

	// -- Variables users
	users = [];

	companies = [];
	currentPage = 1;
	pages = [];
	totalPages: any = 1;
	perPage = 10;  status = 1;
	search = '';
	rows = 10;
	placeholderSearch = 'Buscar en empresas';

	//-- Search Variables
	formSearch: FormGroup;

  	companyId = 0;
	company:any;

	showForm = false;

  constructor(
    private service: ApirestService,
		public singleton: SingletonService,
    public route: ActivatedRoute,
	private formBuilder: FormBuilder
  ) {
	this.confirmMessage = '¿Está seguro de eliminar este usuario?';
	this.buildForm();
    this.route.params.subscribe(params => {
			if (params['id']) {
				this.companyId = params['id'];
			}
		});
   }

  ngOnInit(): void {

	this.getUsers('');
	this.singleton.currentSearch.subscribe((search: string) => {
		this.search = search;
		this.getUsers('');
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
		let url = `companies-users?company_id=${this.companyId}`;
		url += '&search=' + this.formSearch.get('search').value;
		url += '&rows=' + this.perPage;

		if (page) url += '&page=' + page;

		this.service.queryGet(url).subscribe(
			(response: any) => {
				this.singleton.updateLoading(false);
				this.users = response.data;
				this.totalPages = response.meta;
				this.currentPage = response['current_page'];
				this.pages = this.singleton.pagination(response);
				this.singleton.updateLoading(false);
				this.showForm = true;
			},
			(err: any) => {
				this.singleton.updateLoading(false);
			}
		);
	}

	/**
	 *
	 * Get the next page
	 *
	 */

	
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
			const url = 'companies-users/' + userId;
	
			this.service.queryDelete(url).subscribe(
				(response: any) => {
					let result = response;
					if (result.success) 
					{
						this.ngOnInit();
						this.userId = 0;
						this.singleton.showAlert({type: 'success', content: result.message});
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
						this.ngOnInit();
						this.singleton.showAlert({type: 'success', content: result.message});						
					},
					(err:any) =>
					{
						console.log(err);
						
					}
				)
		}

	getPage(page: number) {
		if (page != this.currentPage) {
		  this.getUsers(page);
		}
	}
		
	changeRow(e){
		this.perPage = e.target.value;
		this.getUsers('');
	}   

}
