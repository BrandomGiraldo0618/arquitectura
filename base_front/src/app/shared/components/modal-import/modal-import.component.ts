import { Component, OnInit, ViewChild, Input, Output, EventEmitter } from '@angular/core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { ApirestService } from '../../../core/services/apirest.service';
import { SingletonService } from '../../../core/services/singleton.service';
import { ActivatedRoute, Router } from '@angular/router';
import Swal from 'sweetalert2/dist/sweetalert2.js'; 

@Component({
  selector: 'app-modal-import',
  templateUrl: './modal-import.component.html',
  styleUrls: ['./modal-import.component.scss']
})
export class ModalImportComponent implements OnInit {

  	@ViewChild('modalImport') modalImport;
	modalRef: BsModalRef;
	@Output() onCloseModal = new EventEmitter<boolean>();
  	public opened = false;
  	_type = '';
  	_content = '';

  	constructor(private modalService: BsModalService,
				public service: ApirestService,
				public singleton: SingletonService,
				public route: ActivatedRoute,
				public router: Router) { }

  	ngOnInit(): void 
  	{
  	}

  	openModal() 
  	{
        if(!this.opened)
        {
            this.opened = true;
    	    this.modalRef = this.modalService.show(this.modalImport, {ignoreBackdropClick: true, keyboard: false, class: 'modal-md modal-alert'});
        }
  	}

  	closeModal(accepted)
  	{
        this.opened = false;
  		this.modalRef.hide();
		this.onCloseModal.emit(accepted);
  	}

	uploadFile()
	{
		let inputFileImage = document.getElementById("file") as HTMLInputElement;
        let file = inputFileImage.files[0];
        let url = 'upload-file';

		if(undefined != file){
			let body = new FormData();
			body.append('file', file);
			
			this.service.queryPost(url, body).subscribe(
				(response: any) => {
					if (response.success) {
						this.singleton.showAlert({type: 'success', content: response.message});
						this.closeModal(true);
					} else {
						this.singleton.showAlert({type: 'error', content: response.message});
					}
				},
				(err: any) => {
				}
			);
		}else{
			Swal.fire({
				title: 'Debe seleccionar un archivo',
				confirmButtonColor: '#c00d0d',
			})
		}
		
	}

  	@Input()
  	set type(type: string) 
  	{
    	this._type = type;
  	}

  	@Input()
  	set content(content: string) 
  	{
    	this._content = content;
  	}

	downloadExcel(){
		let url = 'download';

		this.service.downloadExcel(url);
	}
}
