import { Routes } from '@angular/router';
import { LandingPageComponent } from './landing-page/landing-page.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';

export const routes: Routes = [
  {path:'',component:LandingPageComponent},
  {path:'**',component:PageNotFoundComponent},
  {path:'auth', loadChildren: () => import('./auth/auth.module').then(m => m.AuthModule)},

];
