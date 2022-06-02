import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { Error500Component } from '@shared/components/error500/error500.component';
import { Error404Component } from '@shared/components/error404/error404.component';

const routes: Routes = [
	{ path: '', redirectTo: '/auth/login', pathMatch: 'full' },
	{ 
		path: 'auth', 
		loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule) 
	},
	{ 
		path: 'admin', 
		loadChildren: () => import('./admin/admin.module').then(m => m.AdminModule) 
	},
	{ 
		path: 'tecnico', 
		loadChildren: () => import('./technical/technical.module').then(m => m.TechnicalModule) 
	},
	{ 
		path: 'votos', 
		loadChildren: () => import('./votos/votos.module').then(m => m.VotosModule) 
	},

	//-- Error 500 page
	{ path: 'error-500', component: Error500Component },
	//-- Not found page
	{ path: '**', component: Error404Component },

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
