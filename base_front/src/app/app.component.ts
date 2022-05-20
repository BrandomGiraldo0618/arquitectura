import { Component, OnInit, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { SingletonService } from '@core/services/singleton.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  	title = 'Polombia';
  	langs = ['en', 'es'];

    loading = false;

    //-- Modal message variables --//
    @ViewChild('modalMessage') modalMessage;
    typeModal = '';
    contentModal = '';

  	constructor(private translateService: TranslateService,
                public singleton: SingletonService)
  	{
  
  	}

  	ngOnInit(): void {
  		let browserLang = this.translateService.getBrowserLang();
		if (this.langs.indexOf(browserLang) > -1) 
		{
		    this.translateService.setDefaultLang(browserLang);
            this.singleton.currentLang = browserLang;
		} 
		else 
		{
		    this.translateService.setDefaultLang('en');
            this.singleton.currentLang = 'en';
		}

        this.singleton.currentAlert.subscribe(alert => {
            if(Object.getOwnPropertyNames(alert).length > 0)
            {
                console.log(alert);
                this.typeModal = alert['type'];
                this.contentModal = alert['content'];
                this.modalMessage.openModal();
            }
        });

        this.singleton.currentLoading.subscribe(loading => {
            this.loading = loading;
        });
  	}

  	useLanguage(lang: string): void 
  	{
	   	this.translateService.setDefaultLang(lang);
        this.singleton.currentLang = lang;
	}
}
