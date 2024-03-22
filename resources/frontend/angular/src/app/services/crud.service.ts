import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CrudService {
  baseUrl: string = environment.baseUrl;
  private apiResponseSubject = new BehaviorSubject<any>(null);
  apiResponse$ = this.apiResponseSubject.asObservable();
  constructor(private http: HttpClient) { }
  
  getRequest(requestUrl:string,other?:any){
    return this.http.get(`${this.baseUrl}/api/${requestUrl}`,other);
  }
  postRequest(requestUrl:string,data:any){
    return this.http.post(`${this.baseUrl}/api/${requestUrl}`,data)
  }
  putRequest(requestUrl:string,data:any){
    return this.http.put(`${this.baseUrl}/api/${requestUrl}`,data)
  }
  deleteRequest(requestUrl:string){
    return this.http.delete(`${this.baseUrl}/api/${requestUrl}`)
  }

  postRequest1(requestUrl:string,data:any){
    return this.http.post(`http://127.0.0.1:8000/api/${requestUrl}`,data)
  }

  sendResponse(response: any) {
    this.apiResponseSubject.next(response);
  }
}
