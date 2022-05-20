import { Injectable } from '@angular/core';
import {CanActivateChild, Route, UrlSegment, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree } from "@angular/router";
import { Observable } from 'rxjs';
import { Router } from '@angular/router';
import { ApirestService } from '@core/services/apirest.service';

@Injectable({
  providedIn: 'root'
})
export class IsAuthenticatedGuard implements CanActivateChild {
      
    constructor(private service: ApirestService) { }

    async canActivateChild(next: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        let token = localStorage.getItem("token");

        // Validamos si exite un token
        if (token) 
        {
            // Validamos si la sesion del token esta activa
            let session: any = await this.getValidToken();
            if (session) 
            {
                return true;
            } 
        } 

        return false;
    }

    async getValidToken() {
        let url = 'me';
        let body = new FormData();
        return await this.service.queryPost(url, body).toPromise().then(
            (response: any) => {
                return true;
            }, (err: any) => {
                return false;
            }
        );
    }
}
