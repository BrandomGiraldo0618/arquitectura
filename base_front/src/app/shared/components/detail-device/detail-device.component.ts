import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';

@Component({
  selector: 'app-detail-device',
  templateUrl: './detail-device.component.html',
  styleUrls: ['./detail-device.component.scss']
})
export class DetailDeviceComponent implements OnInit {

  lat = 5.067427;
  long = -75.51935;
  zoom:number = 12;
	opened = false;
  maintenances = [];

  @Input() deviceId ;


  device = {
    id : 0,
    device_id : 0,
    key : 0,
    lat : '',
    lon : '',
    reference : {},
    maintenances : [] 
  }

  showMap = false;


  constructor(
    private service: ApirestService,
		public singleton: SingletonService,
    public route: ActivatedRoute,
  ) { }

  ngOnInit(): void {

    this.getDevice()
  }

  getDevice()
  {
    this.service.queryGet(`devices/${this.deviceId}`)
      .subscribe(
        (response:any) => {
          let result = response;

          if(result.success)
          {
            this.device = result.data;
            this.maintenances = this.device.maintenances['data'];

            this.showMap = true;
            
          }
          
        },
        (err:any) => {
          console.log(err);
          
        }
      )
  }

  showBinnacle()
  {
    this.opened = true;

  }

  changeStatus(id)
		{
			let body = new FormData();
			body.append('_method', 'PATCH');
	
			this.service.queryPost(`devices/${id}/change-status` , body)
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

    refresh(event)
    {      
      this.ngOnInit();
      this.opened = false;
    }


}
