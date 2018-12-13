import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';
import {League} from "../Entity/League";


@Injectable()
export class LeagueRESTService {

    constructor(private http: HttpClient) {}

    public findByname(name: string): Observable<League[]>
    {
        let url = `/league/name/${name}`;

        return this.http
            .get<League[]>(url, {headers: { stateKey: `LeagueByName-${name}`}});
    }
}