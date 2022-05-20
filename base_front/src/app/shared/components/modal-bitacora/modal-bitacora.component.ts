import { Component, Input, OnInit , ViewChild , Output , EventEmitter} from '@angular/core';
import {FormGroup, Validators, FormBuilder} from '@angular/forms';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';

@Component({
  selector: 'app-modal-bitacora',
  templateUrl: './modal-bitacora.component.html',
  styleUrls: ['./modal-bitacora.component.scss']
})
export class ModalBitacoraComponent implements OnInit {

  @Input() opened;
  @Input() maintenances;
  @Input() deviceId;

  // -- Confirm modal variables --//
	@ViewChild('modalConfirm') modalConfirm;
	confirmMessage = '';
  maintenanceId = 0;

  @Output() refreshTemplate = new EventEmitter<boolean>();
  

  form!: FormGroup;

  file = '';

  constructor(
    private formBuilder: FormBuilder,
    public service: ApirestService,
		public singleton: SingletonService
  ) { 
    this.confirmMessage = '¿Está seguro de eliminar este registro de la bitacora?';
    this.buildForm();
  }

  ngOnInit(): void {
  }

  private buildForm() {

		this.form = this.formBuilder.group({
			id: [0],
			comments: ['', [Validators.required]],
			file: ['', [Validators.required]],
		});
	}


  close()
  {
    this.opened = false;
    this.refreshTemplate.emit(true);
  }

  openInputFile(id)
    {
        document.getElementById(id).click();
    }

    getAttachmentImage(event)
    {

      this.file = event.target.files[0];

    }



  save(event: Event)
  {
    event.preventDefault();
		if (this.form.invalid) {
			this.form.markAllAsTouched();
			return;
		}

    let values = Object.assign({}, this.form.value);
		let body = new FormData();
    body.append('comments', values.comments);
    body.append('file', this.file);
    body.append('device_id', this.deviceId);

    let url = 'devices-maintenance';

    this.service.queryPost(url, body).subscribe(
			(response: any) => {
				//-- Close Loading
				if (response.success) {
				this.singleton.showAlert({type: 'success', content: response.message});
				this.refreshTemplate.emit(true);
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
	 * Opens the modal to confirm the delete
	 *
	 */

	   confirmDelete(id: number): void {
      this.maintenanceId = id;
      this.modalConfirm.openModal();
    }
  
    /**
     *
     * Validates if the user accept or not the delete
     *
     */
  
    onCloseModalConfirm(accepted: boolean): void {
      if (accepted) {
        this.deleteMaintenance();
      }
    }

    deleteMaintenance()
  {
    this.service.queryDelete(`devices-maintenance/${this.maintenanceId}`).subscribe(
			(response: any) => {
				//-- Close Loading
				if (response.success) {
				this.singleton.showAlert({type: 'success', content: response.message});
				this.refreshTemplate.emit(true);
				} else {
				this.singleton.showAlert({type: 'error', content: response.message});
				}
			},
			(err: any) => {
			}
		);
  }

}
