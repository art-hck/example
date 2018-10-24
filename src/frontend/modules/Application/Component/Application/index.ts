import {Component}  from "@angular/core";
import {RouteHelperService} from "../../Service/RouteHelperService";

@Component({
    selector: "application",
    templateUrl: "./template.pug"
})
export class ApplicationComponent {
    
    constructor(private routeHelperService: RouteHelperService){}
    ngOnInit() {
        this.routeHelperService.metaTagsWatcher();
    }
}