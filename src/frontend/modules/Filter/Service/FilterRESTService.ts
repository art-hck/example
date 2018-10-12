import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs/Observable";

@Injectable()
export class FilterRESTService {

    constructor(private http: HttpClient) {}

    // public get(): Observable<any>
    // {
    //     // let url = `/feed/limit/${limit}`;
    //     //
    //     // return this.http
    //     //     .get<Feed>(url, {params: getFeedRequest, withCredentials: true})
    //     //     ;
    // }

}