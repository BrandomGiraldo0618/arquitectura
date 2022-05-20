import { Component, OnInit } from '@angular/core';
import {ApirestService} from '@core/services/apirest.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

	companies = [];

  	constructor(
		private service: ApirestService
	  ) { }

  	ngOnInit(): void 
  	{
    		
  	}
}
