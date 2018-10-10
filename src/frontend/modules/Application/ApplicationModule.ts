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

registerLocaleData(localeRu);

@NgModule({
    imports: [
        BrowserModule,
        RouterModule.forRoot(appRoutes),
        HttpClientModule,
    ],

    declarations: [
        ApplicationComponent,
        MainRoute,
    ],
    exports: [ApplicationComponent]
})
export class ApplicationModule {}
