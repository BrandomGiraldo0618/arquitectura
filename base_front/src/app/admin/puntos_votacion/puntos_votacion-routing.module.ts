import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { FormComponent } from './components/form/form.component';
import { IndexComponent } from './components/index/index.component';

const routes: Routes = [
  { path: '', component: IndexComponent },
	{ path: 'nuevo', component: FormComponent },
	{ path: 'editar/:id', component: FormComponent },
  {
    path: ':id/usuarios',
    loadChildren: () => import('./components/users/users.module').then(m => m.UsersModule)
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PuntosVotacionRoutingModule { }
