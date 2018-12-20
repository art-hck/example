import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from 'rxjs';

import {ParamsService} from "../../Application/Service/ParamsService";
import {Game} from "../Entity/Game";

@Injectable()
export class GameRESTService {

    constructor(private http: HttpClient) {}

    public getLastGames(): Observable<Game[]>
    {
        let url = `/games/last`;

        return this.http.get<Game[]>(url, { headers: {stateKey: `LastGames`}});
    }
}