import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {Observable} from "rxjs";
import {PlayerRESTService} from "../Service/PlayerRESTService";
import {PlayerFilterRequest} from "../Http/PlayerFilterRequest";
import {ParamsService} from "../../Application/Service/ParamsService";
import {PlayerFilterResponse} from "../Http/PlayerFilterResponse";

@Injectable()
export class PlayersFilterResolver implements Resolve<any>
{
    constructor(private playerService: PlayerRESTService, private paramsService: ParamsService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<PlayerFilterResponse>
    {
        let request = this.paramsService.parse<PlayerFilterRequest>(route.queryParams);
       
        return this.playerService.filter(request);
    }
}