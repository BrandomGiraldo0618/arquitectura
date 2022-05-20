import { Component, OnInit } from '@angular/core';
import { ApirestService } from "@core/services/apirest.service";
import { SingletonService } from "@core/services/singleton.service";

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

    user : any;
    devices = [];

  	constructor(
      public service: ApirestService,
      public singleton: SingletonService
      ) 
      {
        this.user = this.singleton.getSessionUser();
      }

  	ngOnInit(): void 
  	{
      this.getDevices();
  	}

    getDevices()
    {
        /*this.service.queryGet(`devices-users?user_id=${this.user.id}`)
          .subscribe(
            (response:any) =>
            {
              let devices = response.data;

              devices.forEach(element => {
                  this.devices.push(element.device);
              });
              
            },
            (err:any) =>
            {
              console.log(err);
              
            }
          )*/
          
    }
}
