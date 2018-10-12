import {Injectable, Optional} from '@angular/core';
import {HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse} from '@angular/common/http';
import {Observable} from "rxjs/Observable";
import {RESTConfig} from "./RESTInterceptorConfig";
import {ResponseFailure} from "../Http/ResponseFailure";

@Injectable()
export class RESTInterceptor implements HttpInterceptor
{
    private readonly path: string = "";
    private readonly tokenPrefix: string = "Bearer ";

    constructor(
        @Optional() config: RESTConfig
    ) {
        this.path = config.path || "";
        this.tokenPrefix = config.tokenPrefix || this.tokenPrefix;
    }

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        req = req.clone({url: this.path + req.url});

        return next.handle(req)
            .catch((httpErrorResponse: HttpErrorResponse) => {
                let error: ResponseFailure = httpErrorResponse.error;
                
                switch (error.code) {
                    default: return Observable.throw(error);
                }
            });
    }
}