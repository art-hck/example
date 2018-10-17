<?php

namespace App\Type\PlayerRole;

use App\DBAL\Types\PlayerRoleType;
use App\Type\PlayerRole\Types\{
    PlayerRoleAttackingMidfield,
    PlayerRoleCentralMidfield,
    PlayerRoleCentreBack,
    PlayerRoleCentreForward,
    PlayerRoleDefender,
    PlayerRoleDefensiveMidfield,
    PlayerRoleForward,
    PlayerRoleGoalkeeper,
    PlayerRoleLeftBack,
    PlayerRoleLeftMidfield,
    PlayerRoleLeftWing,
    PlayerRoleMidfielder,
    PlayerRoleRightBack,
    PlayerRoleRightMidfield,
    PlayerRoleRightWing,
    PlayerRoleSecondaryStriker,
    PlayerRoleStriker,
    PlayerRoleSweeper
};

class PlayerRoleFactory
{
    static function createFromString(string $roleName): ?PlayerRole
    {
        switch ($roleName) {
            case "Attacking Midfield": return new PlayerRoleAttackingMidfield();
            case "Central Midfield": return new PlayerRoleCentralMidfield();
            case "Centre-Back": return new PlayerRoleCentreBack();
            case "Centre-Forward": return new PlayerRoleCentreForward();
            case "Defence": return new PlayerRoleDefender();
            case "Defensive Midfield": return new PlayerRoleDefensiveMidfield();
            case "Goalkeeper": return new PlayerRoleGoalkeeper();
            case "Keeper": return new PlayerRoleGoalkeeper();
            case "Left Midfield": return new PlayerRoleLeftMidfield();
            case "Left Wing": return new PlayerRoleLeftWing();
            case "Left-Back": return new PlayerRoleLeftBack();
            case "Midfield": return new PlayerRoleMidfielder();
            case "Right Midfield": return new PlayerRoleRightMidfield();
            case "Right Wing": return new PlayerRoleRightWing();
            case "Right-Back": return new PlayerRoleRightBack();
            case "Secondary Striker": return new PlayerRoleSecondaryStriker();
            case "Striker": return new PlayerRoleStriker();
            case "Defender": return new PlayerRoleDefender();
            case "Forward": return new PlayerRoleForward();
            case "Left Winger": return new PlayerRoleLeftWing();
            case "Midfielder": return new PlayerRoleMidfielder();
            case "Right Winger": return new PlayerRoleRightWing();
            case "Second Striker": return new PlayerRoleSecondaryStriker();
            case "Sweeper": return new PlayerRoleSweeper();
            default: return null;
        }
    }

    static function createFromId(int $roleId): ?PlayerRole
    {
        $roleName = PlayerRoleType::getChoices()[$roleId];
        return self::createFromString($roleName);
    }
    
}