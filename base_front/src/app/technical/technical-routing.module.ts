import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { IsAuthenticatedGuard } from '@core/guards/is-authenticated.guard';
import { DashboardComponent } from './dashboard/dashboard.component';
import { LayoutComponent } from './components/layout/layout.component';
import { FormComponent } from './components/form/form.component';

const routes: Routes = [
  {
    path: '',
		component: LayoutComponent,
		canActivateChild: [IsAuthenticatedGuard],
		children: [
			{path: 'panel', component: DashboardComponent},
			{
				path: 'edicion-perfil/:id', component: FormComponent
			},
      {
				path: 'personas',
				loadChildren: () => import('./personas/personas.module').then(m => m.PersonasModule)
			},
      {
				path: 'mesas',
				loadChildren: () => import('./mesas/mesas.module').then(m => m.MesasModule)
			},
		]

  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TechnicalRoutingModule { }
