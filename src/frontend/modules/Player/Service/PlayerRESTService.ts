import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';

import {PlayerFilterRequest} from "../Http/PlayerFilterRequest";
import {Player} from "../Entity/Player";

@Injectable()
export class PlayerRESTService {

    constructor(private http: HttpClient) {}

    public get(id: number): Observable<Player>
    {
        let url = `/player/${id}`;

        return this.http
            .get<Player>(url)
        ;
    }
    
    public filter(playerFilterRequest: PlayerFilterRequest): Observable<Player[]>
    {
        let url = `/players/filter`;
        
        return this.http
            .get<Player[]>(url, {params: playerFilterRequest})
        ;
    }
}