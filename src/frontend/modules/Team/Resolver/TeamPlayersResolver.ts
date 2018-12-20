import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot} from "@angular/router";
import {Observable} from "rxjs";
import {PlayerRESTService} from "../../Player/Service/PlayerRESTService";
import {PlayerFilterResponse} from "../../Player/Http/PlayerFilterResponse";

@Injectable()
export class TeamPlayersResolver {
    constructor(private playerService: PlayerRESTService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<PlayerFilterResponse>
    {
        return this.playerService.filter({teamId: route.params["id"]});
    }
}
