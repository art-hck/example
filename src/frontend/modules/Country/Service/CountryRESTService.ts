import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';
import {Country} from "../Entity/Country";

@Injectable()
export class CountryRESTService {

    constructor(private http: HttpClient) {}

    public findByName(name: string): Observable<Country[]>
    {
        const url = `/country/name/${name}`;

        return this.http
            .get<Country[]>(url, {headers: { stateKey: `CountryByName-${name}`}});
    }
}