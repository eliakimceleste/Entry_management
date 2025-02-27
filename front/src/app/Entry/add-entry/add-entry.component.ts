import { Component, inject , OnInit} from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router'; 
import { EntryService } from '../../services/Entry/entry.service';

@Component({
  selector: 'app-add-entry',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './add-entry.component.html',
  styleUrl: './add-entry.component.scss'
})
export class AddEntryComponent implements OnInit{

  first_name: string = '';
  last_name: string = '';

  entryService = inject(EntryService);
  router = inject(Router);

  constructor() {}

  ngOnInit(): void {
    this.addEntry()
  }
  // Ajouter une nouvelle entrée
  addEntry(): void {
    if (this.first_name && this.last_name) {
      this.entryService.addEntry(this.first_name, this.last_name).subscribe((data) => {
        this.router.navigate(['/']); // Rediriger vers le tableau de bord après l'ajout
        console.log(data);
      });
    }
  }
}