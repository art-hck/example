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
            case "Attacking Midfield":
            case PlayerRoleAttackingMidfield::name:
                return new PlayerRoleAttackingMidfield();
            case "Central Midfield":
            case PlayerRoleCentralMidfield::name:
                return new PlayerRoleCentralMidfield();
            case "Centre-Back":
            case PlayerRoleCentreBack::name:
                return new PlayerRoleCentreBack();
            case "Centre-Forward":
            case PlayerRoleCentreForward::name:
                return new PlayerRoleCentreForward();
            case "Defence":
            case "Defender":
            case PlayerRoleDefender::name:
                return new PlayerRoleDefender();
            case "Defensive Midfield":
            case PlayerRoleDefensiveMidfield::name:
                return new PlayerRoleDefensiveMidfield();
            case "Goalkeeper":
            case "Keeper":
            case PlayerRoleGoalkeeper::name:
                return new PlayerRoleGoalkeeper();
            case "Left Midfield":
            case PlayerRoleLeftMidfield::name:
                return new PlayerRoleLeftMidfield();
            case "Left Wing":
            case "Left Winger":
            case PlayerRoleLeftWing::name:
                return new PlayerRoleLeftWing();
            case "Left-Back":
            case PlayerRoleLeftBack::name:
                return new PlayerRoleLeftBack();
            case "Midfield":
            case "Midfielder":
            case PlayerRoleMidfielder::name:
                return new PlayerRoleMidfielder();
            case "Right Midfield":
            case PlayerRoleRightMidfield::name:
                return new PlayerRoleRightMidfield();
            case "Right Wing":
            case "Right Winger":
            case PlayerRoleRightWing::name:
                return new PlayerRoleRightWing();
            case "Right-Back":
            case PlayerRoleRightBack::name:
                return new PlayerRoleRightBack();
            case "Secondary Striker":
            case "Second Striker":
            case PlayerRoleSecondaryStriker::name:
                return new PlayerRoleSecondaryStriker();
            case "Striker":
            case PlayerRoleStriker::name:
                return new PlayerRoleStriker();
            case "Forward":
            case PlayerRoleForward::name:
                return new PlayerRoleForward();
            case "Sweeper":
            case PlayerRoleSweeper::name:
                return new PlayerRoleSweeper();
            default:
                return null;
        }
    }

    static function createFromId(int $roleId): ?PlayerRole
    {
        $roleName = PlayerRoleType::getChoices()[$roleId];
        return self::createFromString($roleName);
    }

}