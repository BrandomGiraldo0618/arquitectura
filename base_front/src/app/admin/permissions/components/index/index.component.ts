import {Component, OnInit, ViewChild} from '@angular/core';
import {FormGroup, FormBuilder, Validators} from '@angular/forms';
import {debounceTime} from 'rxjs/operators';
import {SingletonService} from '@core/services/singleton.service';
import {ApirestService} from '@core/services/apirest.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-index',
  templateUrl: './index.component.html'
})
export class IndexComponent implements OnInit {

	// -- List roles variables
	roles = [];
	sections = [];
	selectedRole = '';
	selectedSection = '';
	placeholderSearch = 'Buscar en roles';
	isPermissionActive = false;
	idRole = 0;

	// -- Search Variables
	formSearch: FormGroup;

	// -- Form role variables
	form: FormGroup;
	showForm = false;

	// -- Confirm modal variables --//
	@ViewChild('modalConfirm') modalConfirm;
	confirmMessage = '';
	roleId = 0;

	constructor(public singleton: SingletonService,
				private service: ApirestService,
				private formBuilder: FormBuilder,
				private router: Router) {
		this.confirmMessage = '¿Está seguro de eliminar este rol?';
		this.buildForm();
	}

	ngOnInit(): void {
		this.getRoles();
	}

	/**
	 *
	 * It creates the role form with validations
	 *
	 */

	private buildForm(): void {
		this.form = this.formBuilder.group({
		id: [0],
		name: ['', [Validators.required]],
		type: ['3', [Validators.required]],
		status: [true]
		});

		this.formSearch = this.formBuilder.group({
		search: ['']
		});

		this.onChangeForm();
	}

	onChangeForm(): void {
		this.formSearch.get('search').valueChanges.pipe(
		debounceTime(500)
		)
		.subscribe(value => {
			this.getRoles();
		});
	}

	/**
	 *
	 * List the roles
	 *
	 */

	getRoles(): void {
		let url = 'roles';
		url += '?search=' + this.formSearch.get('search').value;

		this.singleton.updateLoading(true)

		this.service.queryGet(url).subscribe(
		(response: any) => {
			this.singleton.updateLoading(false)
			this.roles = response.data;

			if (!this.selectedRole && this.roles.length > 0) {
			this.selectRole(this.roles[0]['id']);
			}
		},
		(err: any) => {
		}
		);
	}

	/**
	 *
	 * Closes the form and reset it
	 *
	 */

	cancelCreate(): void {
		this.showForm = false;
		this.form.reset();
		this.form.get('type').setValue('3');
		this.form.get('status').setValue(true);
	}

	/**
	 *
	 * Select role
	 *
	 */

	selectRole(roleId): void {
		this.selectedRole = roleId;
		this.getPermissions();
	}

	/**
	 *
	 * List of permissions related to a role
	 *
	 */

	getPermissions(): void {
		this.singleton.updateLoading(true);
		this.service.queryGet('permissions/' + this.selectedRole).subscribe(
			(response: any) => {
				this.singleton.updateLoading(false);
				this.sections = response;
				//const sections = [];

				// if (permissions.length > 0) {
				// 	this.sections = [];
				// 	let index = -1;
				// 	let section = {};

				// 	for (var i = 0; i < permissions.length; ++i) {
				// 		index = sections.indexOf(permissions[i]['section']);
				// 		if (index == -1) {
				// 		let allowed = 0;
				// 		if (permissions[i]['allowed']) {
				// 			allowed = 1;
				// 		}
				// 		section = {
				// 			name: permissions[i]['section_name'],
				// 			allowed: allowed,
				// 			permissions: [{
				// 			id: permissions[i]['id'],
				// 			name: permissions[i]['permission_name'],
				// 			allowed: permissions[i]['allowed'],
				// 			real_name: permissions[i]['name']
				// 			}]
				// 		};
				// 		sections.push(permissions[i]['section']);

				// 		this.sections.push(section);
				// 		} else {
				// 		section = this.sections[index];
				// 		section['permissions'].push({
				// 			id: permissions[i]['id'],
				// 			name: permissions[i]['permission_name'],
				// 			allowed: permissions[i]['allowed'],
				// 			real_name: permissions[i]['name']
				// 		});

				// 		if (permissions[i]['allowed']) {
				// 			section['allowed'] += 1;
				// 		}
				// 		this.sections[index] = section;
				// 		}
				// 	}
				// }

			},
			(err: any) => {
			}
		);
	}

	/**
	 *
	 * Enable or disable permission to role
	 *
	 */

	changePermissions(permission, i, j) {
		const url = permission.allowed ? 'revoke-permission-to' : 'permission-to-role';

		const body = new FormData();
		body.append('role_id', this.selectedRole);
		body.append('permissions_id', permission.id);

		this.singleton.updateLoading(true);

		this.service.queryPost(url, body).subscribe(
			(response: any) => {
				this.sections[i]['permissions'][j]['allowed'] = !permission.allowed;
				this.singleton.updateLoading(false);
				this.singleton.showAlert({type: 'success', content: response.message});
				this.service.getPermissions();
			},
			(err: any) => {
			}
		);
	}

	/**
	 *
	 * Change permissions by section
	 *
	 */

	changePermissionsSection(section, index) {
		let allowed = true;
		if (section.allowed == section.permissions.length) {
			allowed = false;
		}

		let url = allowed ? 'permission-to-role' : 'revoke-permission-to';
		let ids = [];

		for (var i = 0; i < section.permissions.length; ++i) {
			ids.push(section.permissions[i]['id']);
		}

		let body = new FormData();
		body.append('role_id', this.selectedRole);
		body.append('permissions_id', ids.join(','));

		this.singleton.updateLoading(true);

		this.service.queryPost(url, body).subscribe(
			(response: any) => {
				this.singleton.updateLoading(false);
				this.sections[index]['allowed'] = allowed ? this.sections[index]['permissions'].length : 0;
				for (var i = 0; i < this.sections[index]['permissions'].length; ++i) {
				this.sections[index]['permissions'][i]['allowed'] = allowed;
				}
				this.singleton.showAlert({type: 'success', content: response.message});
				this.service.getPermissions();
			},
			(err: any) => {
			}
		);
	}

	/**
	 *
	 * Save or update rol function
	 *
	 */

	save(event: Event) {
		event.preventDefault();
		if (this.form.invalid) {
			this.form.markAllAsTouched();
			return;
		}

		const body = Object.assign(this.form.value, {});
		body.status = body.status ? 1 : 0;

		let url = 'roles';
		
		if (this.idRole != 0) {
			url = `roles/${body.id}`;
			body._method = 'PATCH';
		}

		this.singleton.updateLoading(true);

		this.service.queryPost(url, body).subscribe(
			(response: any) => {
				this.singleton.updateLoading(false);
				if (response.success) {
				this.showForm = false;
				this.form.reset();
				this.form.get('type').setValue('3');
				this.form.get('status').setValue(true);
				this.getRoles();
				this.singleton.showAlert({type: 'success', content: response.message});
				} else {
				this.singleton.showAlert({type: 'error', content: response.message});
				}
			},
			(err: any) => {
			}
		);
	}

	/**
	 *
	 * Opens form to edit role
	 *
	 */

	editRole(role) {
		console.log(role);
		this.idRole = role.id;
		this.form.setValue({
			id: role.id,
			name: role.name,
			type: 3,
			status: true
		});

		this.showForm = true;
	}

	/**
	 *
	 * Opens the modal to confirm the delete
	 *
	 */

	confirmDelete(id: number) {
		this.roleId = id;
		this.modalConfirm.openModal();
	}

	/**
	 *
	 * Validates if the role accept or not the delete
	 *
	 */

	onCloseModalConfirm(accepted: boolean) {
		if (accepted) {
			this.deleteRole(this.roleId);
		}
	}

	/**
	 *
	 * It calls to the role's delete service
	 *
	 */

	deleteRole(roleId: number) {
		let url = 'roles/' + roleId;

		this.service.queryDelete(url).subscribe(
			(response: any) => {
				if (response.success) {
				this.singleton.showAlert({type: 'success', content: response.message});
				this.roleId = 0;
				this.getRoles();
				} else {
				this.singleton.showAlert({type: 'error', content: response.message});
				}
			},
			(err: any) => {
			}
		);
	}

	togglePermissions(): void {
		this.isPermissionActive = !this.isPermissionActive;
	}

	getPermissionActive(event): void {
		this.isPermissionActive = event;
	}
}