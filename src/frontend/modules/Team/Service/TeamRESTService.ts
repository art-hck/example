import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';

import {Team} from "../Entity/Team";

@Injectable()
export class TeamRESTService {

    constructor(private http: HttpClient) {}

    public get(id: number): Observable<Team> 
    {
        const url = `/team/${id}`;
        
        return this.http.get<Team>(url, {headers: { stateKey: `TeamById-${id}`}});
    }
    
    public findByName(name: string): Observable<Team[]>
    {
        const url = `/team/name/${name}`;

        return this.http
            .get<Team[]>(url, {headers: { stateKey: `TeamByName-${name}`}});
    }
}