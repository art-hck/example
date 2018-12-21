import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {Observable} from "rxjs";
import {TransferRESTService} from "../Service/TransferRESTService";
import {Transfer} from "../Entity/Transfer";

@Injectable()
export class LastestTransferResolver implements Resolve<Transfer[]>
{
    constructor(private transferService: TransferRESTService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<Transfer[]>
    {
        return this.transferService.filter({
            orderBy: "date",
            orderDirection: "DESC"
        });
    }
}