import {DateISO} from "../../Application/Entity/ISODate";
import {PlayerRoleEnum} from "../Entity/PlayerRoleEnum";

export class PlayerFilterRequest {
    dateFrom?: DateISO = null;
    dateTo?: DateISO = null;
    leagueId?: number = null;
    teamId?: number = null;
    goals?: [number, number] = null;
    cards?: [number, number] = null;
    cardsType?: number = null;
    playTime?: [number, number] = null;
    orderBy?: string = null;
    orderDirection?: "ASC | DESC" = null;
    offset?: number = null;
    limit?: number = null;
    role?: PlayerRoleEnum;
    [name: string]: any;
}