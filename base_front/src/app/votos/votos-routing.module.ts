import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoginComponent } from './components/login/login.component';
import { LayoutComponent } from './components/layout/layout.component';

const routes: Routes = [
	{
		path: '',
		component: LayoutComponent,
		children: [
			{ path: 'login', component: LoginComponent },
		]
	}	
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class VotosRoutingModule { }
