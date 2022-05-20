import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {LayoutComponent} from '@admin/components/layout/layout.component';
import {DashboardComponent} from '@admin/dashboard/dashboard.component';
import { IsAuthenticatedGuard } from '@core/guards/is-authenticated.guard';
import { FormComponent } from './users/components/form/form.component';


const routes: Routes = [
  	{
		path: '',
		component: LayoutComponent,
		canActivateChild: [IsAuthenticatedGuard],
		children: [
			{path: 'panel', component: DashboardComponent},
			{ 
				path: 'usuarios', 
				loadChildren: () => import('./users/users.module').then(m => m.UsersModule) 
			},
			{ 
				path: 'permisos', 
				loadChildren: () => import('./permissions/permissions.module').then(m => m.PermissionsModule) 
			},
			{ 
				path: 'partidos', 
				loadChildren: () => import('./partidos/partidos.module').then(m => m.PartidosModule) 
			},
			{ 
				path: 'personas', 
				loadChildren: () => import('./personas/personas.module').then(m => m.PersonasModule) 
			},
			{ 
				path: 'mesas', 
				loadChildren: () => import('./mesas/mesas.module').then(m => m.MesasModule) 
			},
			{
				path: 'edicion-perfil/:id', component: FormComponent
			}
		]
  	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule {
}
