import { Component, OnInit, ViewChild, Input, Output, EventEmitter } from '@angular/core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-modal-message',
  templateUrl: './modal-message.component.html',
  styleUrls: ['./modal-message.component.scss']
})
export class ModalMessageComponent implements OnInit {

  	@ViewChild('modalMessage') modalMessage;
	modalRef: BsModalRef;
  	public opened = false;
  	_type = '';
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
    	    this.modalRef = this.modalService.show(this.modalMessage, {ignoreBackdropClick: true, keyboard: false, class: 'modal-sm modal-alert'});
        }
  	}

  	closeModal(accepted)
  	{
        this.opened = false;
  		this.modalRef.hide();
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
}
