import {Component, OnInit, Input, Output, EventEmitter} from '@angular/core';
import { Location } from '@angular/common';

@Component({
  selector: 'app-index-mobile',
  templateUrl: './index-mobile.component.html',
  styleUrls: ['./index-mobile.component.scss'],
})

export class IndexMobileComponent implements OnInit {
  @Input() isPermissionActive;
  @Output() isPermissionActiveOut = new EventEmitter<boolean>();
  isCollapsed = false;
  constructor(
    public location: Location
  ) { }

  ngOnInit(): void {
  }

  shadow(id): void {
    const shadow = document.getElementById('shadow' + id);
    const arrow = document.getElementById('arrow-item' + id);
    shadow.classList.toggle('selected');
    arrow.classList.toggle('active-arrow');
  }

  collapse(id): void {
    const collapse = document.getElementById('collapse' + id);
    collapse.classList.toggle('active');
  }

  togglePermissions(): void {
    this.isPermissionActiveOut.emit(!this.isPermissionActiveOut);
  }
}
