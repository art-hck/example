import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';

import {Team} from "../Entity/Team";

@Injectable()
export class TeamRESTService {

    constructor(private http: HttpClient) {}

    public findByname(name: string): Observable<Team[]>
    {
        let url = `/team/name/${name}`;

        return this.http
            .get<Team[]>(url, {headers: { stateKey: `TeamByName-${name}`}});
    }
}