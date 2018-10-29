import {Injectable, Optional} from '@angular/core';
import {HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse} from '@angular/common/http';

import {catchError} from "Rxjs/internal/operators";
import {throwError as observableThrow} from "Rxjs/internal/observable/throwError";
import {Observable} from 'rxjs';

import {RESTInterceptorConfig} from "./RESTInterceptorConfig";
import {ResponseFailure} from "../Http/ResponseFailure";

@Injectable()
export class RESTInterceptor implements HttpInterceptor
{
    private readonly path: string = "";
    private readonly tokenPrefix: string = "Bearer ";

    constructor(@Optional() config: RESTInterceptorConfig) {
        this.path = config.path || "";
        this.tokenPrefix = config.tokenPrefix || this.tokenPrefix;
    }

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        req = req.clone({url: this.path + req.url});

        return next.handle(req).pipe(
            catchError((httpErrorResponse: HttpErrorResponse) => {
                let error: ResponseFailure = httpErrorResponse.error;

                switch (error.code) {
                    default: return observableThrow(error);
                }
            })
        )
    }
}