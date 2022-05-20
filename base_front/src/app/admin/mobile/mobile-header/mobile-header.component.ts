import { Component, OnInit } from '@angular/core';
import {SingletonService} from '@core/services/singleton.service';
import {ApirestService} from '@core/services/apirest.service';

@Component({
  selector: 'app-mobile-header',
  templateUrl: './mobile-header.component.html',
  styleUrls: ['./mobile-header.component.scss']
})
export class MobileHeaderComponent implements OnInit {

  public user: any;

  constructor(
    public singleton: SingletonService,
    public service: ApirestService
  ) { }

  ngOnInit(): void {
    this.user = this.singleton.getSessionUser();
  }

  toggleMenu(item): void {
    const drawer = document.getElementById('drawer-' + item);
    drawer.classList.toggle('active');
  }
  stopPropagation(event): void {
    event.stopPropagation();
  }

}
