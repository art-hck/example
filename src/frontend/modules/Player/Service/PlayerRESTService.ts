import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';
import * as objectHash from "object-hash";

import {PlayerFilterRequest} from "../Http/PlayerFilterRequest";
import {Player} from "../Entity/Player";
import {Params} from "@angular/router";
import {ParamsService} from "../../Application/Service/ParamsService";
import {PlayerFilterResponse} from "../Http/PlayerFilterResponse";

@Injectable()
export class PlayerRESTService {

    constructor(private http: HttpClient, private paramsService: ParamsService) {}

    public get(id: number): Observable<Player>
    {
        const url = `/player/${id}`;

        return this.http
            .get<Player>(url, { headers: {stateKey: `Player-${id}`}})
        ;
    }
    
    public filter(playerFilterRequest: PlayerFilterRequest): Observable<PlayerFilterResponse>
    {
        const url = `/players/filter`;
        const params: Params = this.paramsService.stringify(playerFilterRequest);
        
        return this.http
            .get<PlayerFilterResponse>(url, { params, headers: { stateKey: objectHash(playerFilterRequest)}})
        ;
    }
}