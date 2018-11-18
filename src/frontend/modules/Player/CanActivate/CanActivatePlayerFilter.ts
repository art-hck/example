import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot} from "@angular/router";
import {PlayerFilterRequest} from "../Http/PlayerFilterRequest";

@Injectable()
export class CanActivatePlayerFilter implements CanActivate {

    constructor(private router: Router) {}
    
    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): true
    {
        let request = new PlayerFilterRequest();
        
        Object.keys(route.queryParams).forEach(key => {
           if(!request.hasOwnProperty(key)) {
               this.router.navigate(["not-found"]);
           }
        });
        
        return true;
    }    
}