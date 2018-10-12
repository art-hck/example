import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {RouterModule} from "@angular/router";
import {registerLocaleData} from "@angular/common";
import {HttpClientModule} from "@angular/common/http";
import localeRu from '@angular/common/locales/ru';

import {appRoutes} from "../../app/routes";
import "../../assets/styles/index.scss";

import {ApplicationComponent} from "./Component/Application";
import {MainRoute} from "./Routes/MainRoute/MainRoute";
import {FilterModule} from "../Filter/FilterModule";
import {PlatformService} from "./Service/PlatformService";
import {RouteHelperService} from "./Service/RouteHelperService";

registerLocaleData(localeRu);

@NgModule({
    imports: [
        BrowserModule,
        RouterModule.forRoot(appRoutes),
        HttpClientModule,
        FilterModule,
    ],

    declarations: [
        ApplicationComponent,
        MainRoute,
    ],
    providers: [
        RouteHelperService,
        PlatformService
    ],
    exports: [ApplicationComponent]
})
export class ApplicationModule {}
