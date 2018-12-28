import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {RouterModule} from "@angular/router";
import {CommonModule, registerLocaleData} from "@angular/common";
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import localeRu from '@angular/common/locales/ru';

import {appRoutes} from "../../app/routes";
import "../../assets/styles/index.scss";

import {ApplicationComponent} from "./Component/Application";
import {PlatformService} from "./Service/PlatformService";
import {RouteHelperService} from "./Service/RouteHelperService";
import {PlayerModule} from "../Player/PlayerModule";
import {RESTInterceptorConfig} from "./Interceptor/RESTInterceptorConfig";
import {RESTInterceptor} from "./Interceptor/RESTInterceptor";

import {MaterialModule} from "./MaterialModule";
import {PageNotFoundRoute} from "./Route/PageNotFoundRoute";
import {ForbiddenRoute} from "./Route/ForbiddenRoute";
import {CacheService} from "./Service/CacheService";
import {IconDirective} from "./Directive/IconDirective";
import {CacheInterceptor} from "./Interceptor/CacheInterceptor";
import {ParamsService} from "./Service/ParamsService";
import {TeamModule} from "../Team/TeamModule";
import {GameModule} from "../Game/GameModule";
import {TransferModule} from "../Transfer/TransferModule";
import {LeagueModule} from "../League/LeagueModule";
import {MarketRoute} from "./Route/MarketRoute";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {NouisliderModule} from "ng2-nouislider";
import {ShortNumberPipe} from "./Pipe/ShortNumberPipe";
import {CountryModule} from "../Country/CountryModule";

registerLocaleData(localeRu);

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        ReactiveFormsModule,
        BrowserModule,
        RouterModule.forRoot(appRoutes, {scrollPositionRestoration: 'enabled', onSameUrlNavigation: "reload"}),
        HttpClientModule,
        CountryModule,
        PlayerModule,
        TeamModule,
        GameModule,
        TransferModule,
        LeagueModule,
        MaterialModule,
    ],

    declarations: [
        ApplicationComponent,

        MarketRoute,
        PageNotFoundRoute,
        ForbiddenRoute,

        IconDirective,

        ShortNumberPipe
    ],
    providers: [
        RouteHelperService,
        PlatformService,
        CacheService,
        ParamsService,
        {
            provide: HTTP_INTERCEPTORS,
            useClass: RESTInterceptor,
            multi: true,
        },
        {
            provide: HTTP_INTERCEPTORS,
            useClass: CacheInterceptor,
            multi: true,
        },
        {
            provide: RESTInterceptorConfig,
            useValue: {
                path: "/api",
                tokenPrefix: "Bearer "
            }
        }
    ],
    exports: [ApplicationComponent]
})
export class ApplicationModule {}
