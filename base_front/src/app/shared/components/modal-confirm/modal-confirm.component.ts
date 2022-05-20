import { Component, OnInit, ViewChild, Input, Output, EventEmitter } from '@angular/core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-modal-confirm',
  templateUrl: './modal-confirm.component.html',
  styleUrls: ['./modal-confirm.component.scss']
})
export class ModalConfirmComponent implements OnInit {

	@ViewChild('modalConfirm') modalConfirm;
  	@Output() onCloseModal = new EventEmitter<boolean>();
	modalRef: BsModalRef;
  	public opened = false;
  	_content = '';

  	constructor(private modalService: BsModalService) { }

  	ngOnInit(): void 
  	{
  	}

  	openModal() 
  	{
        if(!this.opened)
        {
            this.opened = true;
    	    this.modalRef = this.modalService.show(this.modalConfirm, {ignoreBackdropClick: true, keyboard: false, class: 'modal-sm modal-alert'});
        }
  	}

  	closeModal(accepted)
  	{
        this.opened = false;
  		this.modalRef.hide();
  		this.onCloseModal.emit(accepted);
  	}

  	@Input()
  	set content(content: string) 
  	{
    	this._content = content;
  	}
}
