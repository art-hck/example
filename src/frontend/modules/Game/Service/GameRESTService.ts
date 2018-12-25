import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';

import {Game} from "../Entity/Game";
import {GameByPlayerResponse} from "../Http/GameByPlayerResponse";
import {GameFilterRequest} from "../Http/GameFilterRequest";
import {Params} from "@angular/router";
import {ParamsService} from "../../Application/Service/ParamsService";

@Injectable()
export class GameRESTService {

    constructor(private http: HttpClient, private paramsService: ParamsService) {}

    public getByPlayer(playerId: number): Observable<GameByPlayerResponse>
    {
        const url = `/games/player/${playerId}`;

        return this.http.get<GameByPlayerResponse>(url, { headers: {stateKey: `GameByPlayer-${playerId}`}});
    }

    public filter(gameFilterRequest: GameFilterRequest): Observable<Game[]>
    {
        const url = `/games/filter`;
        const params: Params = this.paramsService.stringify(gameFilterRequest);

        return this.http.get<Game[]>(url, {params, headers: {stateKey: `LastGames`}});
    }
}