import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';

@Component({
  selector: 'app-landing',
  templateUrl: './landing.component.html',
  styleUrls: ['./landing.component.scss']
})
export class LandingComponent implements OnInit {

  public markers = [];
  lat = 5.067427;
  long = -75.51935;
  zoom:number = 12;

  user : any;
  roleId = 0;

  moreDetail = false;
  deviceId = 0;

  path = 'admin';

  constructor(
    public service: ApirestService,
    public singleton : SingletonService,
    public router: Router
  ) { }

  ngOnInit(): void {
    this.initMap();

  }
  capture_log = {
    date: '',
    elevation: 0,
    humidity: 0,
    ica: 0,
    pressure: 0,
    temperature: 0,
    description : '',
    color : '',
    url_emoticon : '',
    name : '',

  }
  initMap() {

    this.service.queryGet(`devices?role_id=${this.roleId}`)
      .subscribe(
        (response:any) => 
        {
          let result = response;

          this.markers = result.data;       
          this.capture_log = result.general_capture_log;
          this.singleton.updateLoading(false);
        },
        (err:any) =>
        {
          console.error(err);
          
        }
      )


  }

  markerClicked(deviceId: number)
  {
    this.capture_log = this.markers.find(x=>x.id == deviceId).capture_log;
    this.moreDetail = this.markers.find(x=>x.id == deviceId).more_detail
    this.deviceId = this.markers.find(x=>x.id == deviceId).id;
  }

  showDetail()
  {
    this.router.navigate([`${this.path}/equipos/detalle/${this.deviceId}`]);
    
  }
}
