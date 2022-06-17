import { Component, OnInit } from '@angular/core';
import {ApirestService} from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

	companies = [];
  	constructor(
		public singleton: SingletonService,
		public service: ApirestService
	  ) { }

  	ngOnInit(): void 
  	{
  	}
}
