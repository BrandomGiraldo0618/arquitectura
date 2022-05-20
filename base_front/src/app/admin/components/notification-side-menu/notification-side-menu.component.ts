import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-notification-side-menu',
  templateUrl: './notification-side-menu.component.html',
  styleUrls: ['./notification-side-menu.component.scss']
})
export class NotificationSideMenuComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }

  toggleMenu(item): void {
    const drawer = document.getElementById('drawer-' + item);
    drawer.classList.toggle('active');
  }
  stopPropagation(event): void {
    event.stopPropagation();
  }

}
