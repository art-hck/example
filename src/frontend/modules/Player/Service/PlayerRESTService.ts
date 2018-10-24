import {Injectable} from "@angular/core";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs/Observable";
import {PlayerFilterRequest} from "../Http/PlayerFilterRequest";
import {Player} from "../Entity/Player";

@Injectable()
export class PlayerRESTService {

    constructor(private http: HttpClient) {}

    public get(id: number)
    {
        let url = `/player/${id}`;

        return this.http
            .get<Player>(url)
        ;
    }
    
    public filter(playerFilterRequest: PlayerFilterRequest): Observable<any>
    {
        let url = `/players/filter`;
        
        return this.http
            .get<Player[]>(url, {params: playerFilterRequest})
        ;
    }
}