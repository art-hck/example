import {DateISO} from "../../Application/Entity/ISODate";
import {PlayerRoleEnum} from "../Entity/PlayerRoleEnum";

export class PlayerFilterRequest {
    age?: string = null;
    assists?: [number, number] = null;
    cards?: [number, number] = null;
    cardsType?: number = null;
    countryId?: string = null;
    countryName?: string = null;
    dateFrom?: DateISO = null;
    dateTo?: DateISO = null;
    goals?: [number, number] = null;
    height?: number = null;
    international?: boolean = null;
    leagueId?: number = null;
    leagueName?: string = null;
    playTime?: [number, number] = null;
    playerName?: string = null;
    role?: PlayerRoleEnum = null;
    teamId?: number = null;
    teamName?: string = null;
    orderBy?: string = null;
    orderDirection?: "ASC" | "DESC" = null;
    offset?: number = null;
    limit?: number = null;
    [name: string]: any;
}