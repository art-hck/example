import {NgModule} from "@angular/core";
import {TransferRESTService} from "./Service/TransferRESTService";
import {LastestTransferResolver} from "./Resolver/LastestTransferResolver";

@NgModule({
    providers: [
        LastestTransferResolver,
        TransferRESTService
    ],
})
export class TransferModule {
}