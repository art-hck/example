import {DateISO} from "../../Application/Entity/ISODate";

export class GameFilterRequest {
    dateFrom?: DateISO = null;
    dateTo?: DateISO = null;
    teamId?: number = null;
    leagueId?: number = null;
    duration?: [number, number] = null;
    orderBy?: string = null;
    orderDirection?: "ASC" | "DESC" = null;
    offset?: number = null;
    limit?: number = null;
    [name: string]: any;
}