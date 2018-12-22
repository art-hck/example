import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';

import {Game} from "../Entity/Game";
import {GameByPlayerResponse} from "../Http/GameByPlayerResponse";

@Injectable()
export class GameRESTService {

    constructor(private http: HttpClient) {}

    public getLastGames(): Observable<Game[]>
    {
        const url = `/games/last`;

        return this.http.get<Game[]>(url, { headers: {stateKey: `LastGames`}});
    }
    
    public getByPlayer(playerId: number): Observable<GameByPlayerResponse>
    {
        const url = `/games/player/${playerId}`;

        return this.http.get<GameByPlayerResponse>(url, { headers: {stateKey: `GameByPlayer-${playerId}`}});
    }
}