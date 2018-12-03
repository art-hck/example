import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';
import * as objectHash from "object-hash";

import {PlayerFilterRequest} from "../Http/PlayerFilterRequest";
import {Player} from "../Entity/Player";
import {Params} from "@angular/router";
import {ParamsService} from "../../Application/Service/ParamsService";

@Injectable()
export class PlayerRESTService {

    constructor(private http: HttpClient, private paramsService: ParamsService) {}

    public get(id: number): Observable<Player>
    {
        let url = `/player/${id}`;

        return this.http
            .get<Player>(url, { headers: {stateKey: `Player-${id}`}})
        ;
    }
    
    public filter(playerFilterRequest: PlayerFilterRequest): Observable<Player[]>
    {
        let url = `/players/filter`;
        let params: Params = this.paramsService.stringify(playerFilterRequest);
        
        return this.http
            .get<Player[]>(url, { params, headers: { stateKey: objectHash(playerFilterRequest)}})
        ;
    }
}