import { Component, inject, OnInit, ViewChild, AfterViewInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatTableDataSource, MatTableModule } from '@angular/material/table';
import { MatPaginator, MatPaginatorModule } from '@angular/material/paginator';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon'
import { RouterModule } from '@angular/router';
import { Router } from '@angular/router';
import { EntryService } from '../../services/Entry/entry.service';

@Component({
  selector: 'app-entry',
  imports: [CommonModule, MatPaginatorModule, MatTableModule, MatButtonModule, MatIconModule],
  templateUrl: './entry.component.html',
  styleUrl: './entry.component.scss'
})
export class EntryComponent implements OnInit {

  displayedColumns: string[] = ['first_name', 'last_name', 'arrival_time'];
  dataSource = new MatTableDataSource<any>();

  entries: any[] = [];
  first_name !: string;
  last_name !: string;
  currentPage: number = 1;
  lastPage: number = 1;
  nextPageUrl: string | null = null;
  prevPageUrl: string | null = null;
  totalEntries !: number;
  entryService = inject(EntryService);

  @ViewChild(MatPaginator) paginator!: MatPaginator;

  constructor(private router: Router) { }

  ngOnInit(): void {
    this.loadEntries(this.currentPage)
  }

  loadEntries(current_page: number): void {
    const url = current_page
    this.entryService.getEntries(current_page).subscribe((response: any) => {
      console.log(response)
      this.dataSource.data = response.data; // Extraire le tableau `data` de la réponse paginée
      this.currentPage = response.current_page;
      this.lastPage = response.last_page;
      this.nextPageUrl = response.next_page_url;
      this.prevPageUrl = response.prev_page_url;
      this.totalEntries = response.total;
    });
  }

  navigateToAddEntry(): void {
    this.router.navigate(['/add-entry']);
  }

  // Gérer la pagination
  onPageChange(event: any): void {
    this.currentPage = event.pageIndex + 1;
    this.loadEntries(this.currentPage);
  }

}
