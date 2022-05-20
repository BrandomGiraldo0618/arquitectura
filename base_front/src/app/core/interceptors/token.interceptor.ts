import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpEvent } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class TokenInterceptor implements HttpInterceptor {

 	constructor() {}

  	intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
  
    	const token: string = localStorage.getItem('token');

    	let request = req;

    	if (token) 
    	{
      		request = req.clone({
        		setHeaders: {
          			Authorization: token
        		}
      		});
    	}

    	return next.handle(request);
  	}
}
