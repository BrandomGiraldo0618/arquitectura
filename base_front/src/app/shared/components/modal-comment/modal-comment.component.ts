import {
  Component,
  Input,
  OnInit,
  ViewChild,
  Output,
  EventEmitter,
} from '@angular/core';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';

@Component({
  selector: 'app-modal-comment',
  templateUrl: './modal-comment.component.html',
  styleUrls: ['./modal-comment.component.scss'],
})
export class ModalCommentComponent implements OnInit {
  @Input() opened;
  @Input() maintenances;
  @Input() deviceId;

  // -- Confirm modal variables --//
  @ViewChild('modalConfirm') modalConfirm;
  confirmMessage = '';
  maintenanceId = 0;

  @Output() refreshTemplate = new EventEmitter<boolean>();

  form!: FormGroup;


  constructor(
    private formBuilder: FormBuilder,
    public service: ApirestService,
    public singleton: SingletonService
  ) {
    this.confirmMessage = '¿Está seguro de eliminar este comentario?';
    this.buildForm();
  }

  ngOnInit(): void {}

  private buildForm() {
    this.form = this.formBuilder.group({
      id: [0],
      comments: ['', [Validators.required]],
    });
  }

  close() {
    this.opened = false;
    this.refreshTemplate.emit(true);
  }



  save(event: Event) {
    event.preventDefault();
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    let values = Object.assign({}, this.form.value);
    let body = new FormData();
    body.append('comments', values.comments);
    body.append('device_id', '42');

    let url = 'devices-maintenance';

    this.service.queryPost(url, body).subscribe(
      (response: any) => {
        //-- Close Loading
        if (response.success) {
          this.singleton.showAlert({
            type: 'success',
            content: response.message,
          });
          this.refreshTemplate.emit(true);
        } else {
          this.singleton.showAlert({
            type: 'error',
            content: response.message,
          });
        }
      },
      (err: any) => {}
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

  deleteMaintenance() {
    this.service
      .queryDelete(`devices-maintenance/${this.maintenanceId}`)
      .subscribe(
        (response: any) => {
          //-- Close Loading
          if (response.success) {
            this.singleton.showAlert({
              type: 'success',
              content: response.message,
            });
            this.refreshTemplate.emit(true);
          } else {
            this.singleton.showAlert({
              type: 'error',
              content: response.message,
            });
          }
        },
        (err: any) => {}
      );
  }
}
