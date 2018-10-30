import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {catchError, tap} from "Rxjs/internal/operators";

import {CacheService} from "../../Application/Service/CacheService";
import {Player} from "../Entity/Player";
import {PlayerRESTService} from "./PlayerRESTService";
import * as objectHash from "object-hash";
import {PlayerFilterRequest} from "../Http/PlayerFilterRequest";

@Injectable()
export class PlayersFilterService {
    
    constructor(private rest: PlayerRESTService, private cacheService: CacheService){}

    public filter(request: PlayerFilterRequest): Observable<Player[]>
    {
        return this.cacheService.get<Player[]>(objectHash(request)).pipe(
            catchError(() =>
                this.rest.filter(request).pipe(
                    tap(players => 
                        this.cacheService.set<Player[]>(players, objectHash(request))
                    )
                )
            )
        );
    }
}