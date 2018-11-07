import {Component, HostBinding} from "@angular/core";
import {RouteHelperService} from "../../Service/RouteHelperService";
import {Event, NavigationCancel, NavigationEnd, NavigationError, NavigationStart, Router} from "@angular/router";
import {Device} from "../../Service/Device";

@Component({
    selector: "application",
    styleUrls: ["./style.shadow.scss"],
    templateUrl: "./template.pug"
})
export class ApplicationComponent {
    
    @HostBinding('class.loading')
    public isLoading = false;
    public device = Device;

    constructor(
        private routeHelperService: RouteHelperService,
        private router: Router
    ){}
    
    ngOnInit() {
        this.routeHelperService.metaTagsWatcher();

        this.router.events.subscribe((event: Event) => {

            switch (event.constructor) {
                case NavigationStart:
                    this.isLoading = true;
                    break;
                case NavigationEnd:
                case NavigationCancel:
                case NavigationError:
                    this.isLoading = false;
                    break;
            }
        });
    }
}