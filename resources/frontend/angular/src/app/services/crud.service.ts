import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CrudService {
  baseUrl: string = environment.baseUrl;
  private apiResponseSubject = new BehaviorSubject<any>(null);
  apiResponse$ = this.apiResponseSubject.asObservable();
  constructor(private http: HttpClient) { }
  
  getRequest(requestUrl:string,headers?:any){
    return this.http.get(`${this.baseUrl}/api/${requestUrl}`,{headers});
  }
  getRequest2(requestUrl: string, typeUrl: string, data?: any, headers?: any): Observable<any> {
    // Initialize an empty options object
    let requestOptions: any = {};

    // Set headers if provided
    if (headers) {
      requestOptions.headers = new HttpHeaders(headers);
    }

    // Construct the query parameters
    let params = {};
    if (data) {
      params = { ...data, typeurl: typeUrl };
    } else {
      params = { typeurl: typeUrl };
    }

    // Perform the HTTP GET request
    return this.http.get(`${this.baseUrl}/api/${requestUrl}`, { params, ...requestOptions });
  }
  // postRequest(requestUrl:string,data:any){
  //   return this.http.post(`${this.baseUrl}/api/${requestUrl}`,data)
  // }

  postRequest(requestUrl: string, data: any, headers?: any) {
    return this.http.post(`${this.baseUrl}/api/${requestUrl}`, data, { headers });
  }

  putRequest(requestUrl:string,data:any){
    return this.http.put(`${this.baseUrl}/api/${requestUrl}`,data)
  }
  deleteRequest(requestUrl:string, headers?: any){
    return this.http.delete(`${this.baseUrl}/api/${requestUrl}`,{ headers })
  }

  postRequest1(requestUrl:string,data:any){
    return this.http.post(`http://127.0.0.1:8000/api/${requestUrl}`,data)
  }

  sendResponse(response: any) {
    this.apiResponseSubject.next(response);
  }

  // /filterChannels/Others
  filterChannelResponse(channel:string,body?:any,headers?:any){
    return this.http.post(`${this.baseUrl}/api/auth/filterChannels/${channel}`,body,{headers})
  }
}
