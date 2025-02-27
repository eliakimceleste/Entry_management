import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs'

@Injectable({
  providedIn: 'root'
})
export class EntryService {

  headers = new HttpHeaders({
    "Content-Type": "application/json",
    //Authorization: token,
  });
  private http = inject(HttpClient )
  endpoint = "http://localhost:8000/api/";


  constructor() { }

  // Récupérer toutes les entrées
  getEntries(current_page:number): Observable<any> {
    return this.http.get(this.endpoint + `entries?page=` + current_page, { headers: this.headers });
  }

  // Ajouter une nouvelle entrée
  addEntry(first_name: string, last_name: string): Observable<any> {
    let entryData = {"first_name": first_name, "last_name": last_name };
    return this.http.post<any>(this.endpoint + `store`, entryData);
  }
}
