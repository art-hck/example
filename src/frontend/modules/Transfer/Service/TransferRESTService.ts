import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import * as objectHash from "object-hash";

import {Transfer} from "../Entity/Transfer";
import {TransferFilterRequest} from "../Http/TransferFilterRequest";
import {Params} from "@angular/router";
import {ParamsService} from "../../Application/Service/ParamsService";

@Injectable()
export class TransferRESTService {

    constructor(private http: HttpClient, private paramsService: ParamsService) {}

    public filter(transferFilterRequest: TransferFilterRequest): Observable<Transfer[]> 
    {
        const url = `/transfers/filter`;
        const params: Params = this.paramsService.stringify(transferFilterRequest);

        return this.http
            .get<Transfer[]>(url, { params, headers: { stateKey: objectHash(transferFilterRequest)}})
        ;
    }
}
