import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { IndexComponent } from './components/index/index.component';
import { FormComponent } from './components/form/form.component';
import { ProfileComponent } from './components/profile/profile.component';

const routes: Routes = [
	{ path: '', component: IndexComponent },
	{ path: 'nuevo', component: FormComponent },
	{ path: 'editar/:id', component: FormComponent },
	{ path: 'edicion-perfil', component: FormComponent },
	{ path: 'detalle/:id', component: ProfileComponent },
	{ path: 'perfil', component: ProfileComponent },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UsersRoutingModule { }
