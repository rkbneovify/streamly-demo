import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class CrudService {
  baseUrl: string = environment.baseUrl;
  constructor(private http: HttpClient) { }
  
  getRequest(requestUrl:string){
    return this.http.get(`${this.baseUrl}/api/${requestUrl}`)
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
}
