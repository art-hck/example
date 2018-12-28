import {NgModule} from "@angular/core";
import {CountryRESTService} from "./Service/CountryRESTService";

@NgModule({
    providers: [
        CountryRESTService
    ]
})
export class CountryModule {
}