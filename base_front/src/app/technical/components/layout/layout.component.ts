import {Component, OnInit, ViewChild, ElementRef} from '@angular/core';
import {fromEvent} from 'rxjs';
import {debounceTime, map, distinctUntilChanged, filter} from 'rxjs/operators';
import {SingletonService} from '@core/services/singleton.service';
import {ApirestService} from '@core/services/apirest.service';

import * as $ from 'jquery';

@Component({
  selector: 'app-layout',
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.scss']
})
export class LayoutComponent implements OnInit {

	@ViewChild('searchInput', {static: true}) searchInput: ElementRef;

	collapseMenu = false;
    openMenu = '';

	user: any;
	openProfile = false;
	mediaQuery = 'lg';
	sectionSearch = '';
	placeholderSearch = '';
	titlePage = '¡Te damos la bienvenida!';

	constructor(public singleton: SingletonService,
				public service: ApirestService) {
	}

	ngOnInit(): void {
		this.user = this.singleton.getSessionUser();

		if (this.user) {
			this.service.getPermissions();
		}

		this.singleton.currentSectionSearch.subscribe(section => {
			this.sectionSearch = section;

			if (this.sectionSearch === 'users') {
				this.placeholderSearch = 'Buscar en usuarios';
				this.titlePage = 'Usuarios';
			} else {
				this.placeholderSearch = '';
				this.titlePage = '¡Te damos la bienvenida!';
			}

			if (this.sectionSearch !== '') {
				setTimeout(() => {
				this.initSearch();
				}, 100);
			}
		});

		this.getMediaQuery();

		$(window).resize(() => {
			this.getMediaQuery();
		});

	}

	getMediaQuery(): void {
		const width = screen.width;
		this.collapseMenu = false;
		if (width <= 575.98) {
			this.mediaQuery = 'xs';
			this.collapseMenu = true;
		} else if (width <= 767.98) {
			this.mediaQuery = 'sm';
			this.collapseMenu = true;
		} else if (width <= 991.98) {
			this.mediaQuery = 'md';
			this.collapseMenu = true;
		} else if (width <= 1199.98) {
			this.mediaQuery = 'lg';
		}
	}

	initSearch(): void {
		fromEvent(this.searchInput.nativeElement, 'keyup').pipe(
		// get value
		map((event: any) => {
			return event.target.value;
		})
		// if character length greater then 2
		, filter(res => res.length > 2)
		// Time in milliseconds between key events
		, debounceTime(500)
		// If previous query is diffent from current
		, distinctUntilChanged()
		// subscription for response
		).subscribe((text: string) => {
			this.singleton.updateSearch(text);
		});
	}

	onCloseModalUser(data): void {
		this.openProfile = false;
		if (data.saved == 1) {
			this.user = data.user;
			localStorage.setItem('user', JSON.stringify(this.user));
		}
	}
}