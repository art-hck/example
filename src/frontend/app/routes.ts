import {MainRoute} from "../modules/Application/Routes/MainRoute/MainRoute";
import {GenieRoutes} from "../modules/Application/Entity/Route";

export const appRoutes: GenieRoutes = [
    {
        path: '',
        component: MainRoute,
        data: {
            title: "Genie",
            description: "Genie description"
        }
    }
];