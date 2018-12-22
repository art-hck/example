import {Injectable} from "@angular/core";
import {Observable, of, throwError as observableThrow} from "rxjs";
import {makeStateKey, StateKey, TransferState} from "@angular/platform-browser";

@Injectable()
export class CacheService {

    constructor(private transferState: TransferState){}

    public get<T>(key: string): Observable<T>
    {
        const stateKey: StateKey<T> = makeStateKey<T>(key);

        if(this.transferState.hasKey(stateKey)) {
            return of(this.transferState.get(stateKey, null as T));
        } else {
            return observableThrow(`Is not cached`);
        }
    }

    public set<T>(data: T, key: string): void
    {
        const stateKey: StateKey<T> = makeStateKey<T>(key);
        this.transferState.set(stateKey, data as T);
    }    
}