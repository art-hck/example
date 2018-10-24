import {Injectable, Optional} from '@angular/core';
import {HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse} from '@angular/common/http';
import {Observable, throwError as observableThrow} from "rxjs";

import {RESTInterceptorConfig} from "./RESTInterceptorConfig";
import {ResponseFailure} from "../Http/ResponseFailure";
import {catchError} from "rxjs/internal/operators";

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