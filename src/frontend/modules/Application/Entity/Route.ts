import {Route} from "@angular/router";

export interface GenieRoute extends Route {
    children?: GenieRoute[];
    data?: {
        [name: string]: any;
        title?: string;
        description?: string;
    }
}

export type GenieRoutes = GenieRoute[];