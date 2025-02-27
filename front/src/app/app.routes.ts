import { Routes } from '@angular/router';
import { EntryComponent } from './Entry/entry/entry.component';
import { AddEntryComponent } from './Entry/add-entry/add-entry.component';

export const routes: Routes = [
  { path: '', component: EntryComponent }, // Tableau de bord
  { path: 'add-entry', component: AddEntryComponent }, // Formulaire d'ajout
];