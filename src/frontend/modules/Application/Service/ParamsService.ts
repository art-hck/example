import {Injectable} from "@angular/core";
import {Params} from "@angular/router";

@Injectable()
export class ParamsService 
{
    stringify(object: Object): Params 
    {
        let params: Params = {};
        Object.keys(object).forEach((k) => {
            if(object[k] !== null && object[k] !== "undefined" && object[k] !== "") {
                try {
                    params[k] = typeof object[k] !== "string" ? JSON.stringify(object[k]) : object[k];
                } catch (e) {
                    params[k] = object[k];
                }
            }
        });
        return params;
    }
    
    parse<T>(params: Params): T
    {
        let object = {};
        
        Object.keys(params).map((key) => {
            try {
                object[key] = JSON.parse(params[key]);
            } catch (e) {
                object[key] = params[key];
            }
        });
        
        return <T>object;
    }
}