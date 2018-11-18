import {Injectable} from '@angular/core';
import {HttpEvent, HttpInterceptor, HttpHandler, HttpRequest} from '@angular/common/http';

import {catchError, tap} from "rxjs/internal/operators";
import {Observable} from 'rxjs';

import {CacheService} from "../Service/CacheService";

@Injectable()
export class CacheInterceptor implements HttpInterceptor
{
    constructor(private cacheService: CacheService){}
    
    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        let stateKey: string;
        
        if(req.headers.has("stateKey")) {
            stateKey = req.headers.get("stateKey");
            req = req.clone({headers: req.headers.delete("stateKey")});
        }
        
        if(stateKey) {
            return this.cacheService.get<any>(stateKey).pipe(
                catchError(() =>
                    next.handle(req).pipe(
                        tap((data: any) =>
                            this.cacheService.set<any>(data, stateKey)
                        )
                    )
                )
            );
        } else {
            return next.handle(req);
        }
    }
}