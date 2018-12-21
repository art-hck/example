import {DateISO} from "../../Application/Entity/ISODate";

export class TransferFilterRequest {
    dateFrom?: DateISO = null;
    dateTo?: DateISO = null;
    fee?: [number, number] = null;
    mv?: [number, number] = null;
    orderBy?: string = null;
    orderDirection?: "ASC" | "DESC" = null;
    offset?: number = null;
    limit?: number = null;
    [name: string]: any;
}