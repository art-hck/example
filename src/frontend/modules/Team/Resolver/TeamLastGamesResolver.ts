import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot} from "@angular/router";
import {Observable} from "rxjs";

import {GameRESTService} from "../../Game/Service/GameRESTService";
import {Game} from "../../Game/Entity/Game";

@Injectable()
export class TeamLastGamesResolver {
    constructor(private gameService: GameRESTService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<Game[]>
    {
        return this.gameService.filter({orderBy: "date", teamId: route.params["id"]});
    }
}
