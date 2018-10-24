import {DateISO} from "../../Application/Entity/ISODate";

export interface PlayerFilterRequest {
    dateFrom?: DateISO;
    dateTo?: DateISO;
    leagueId?: number;
    teamId?: number;
    minGoals?: number;
    maxGoals?: number;
    minCards?: number;
    maxCards?: number;
    cardsType?: number;
    minPlayTime?: number;
    maxPlayTime?: number;
    orderBy?: string;
    orderDirection?: "ASC | DESC";
    offset?: number;
    limit?: number;
    [name: string]: any;
}