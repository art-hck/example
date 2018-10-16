<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;
use App\Type\PlayerRole\{
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

class PlayerRoleType extends AbstractEnumType
{
    protected static $choices = [
        PlayerRoleGoalkeeper::id => PlayerRoleGoalkeeper::name,
        PlayerRoleDefender::id => PlayerRoleDefender::name,
        PlayerRoleLeftBack::id => PlayerRoleLeftBack::name,
        PlayerRoleCentreBack::id => PlayerRoleCentreBack::name,
        PlayerRoleRightBack::id => PlayerRoleRightBack::name,
        PlayerRoleDefensiveMidfield::id => PlayerRoleDefensiveMidfield::name,
        PlayerRoleMidfielder::id => PlayerRoleMidfielder::name,
        PlayerRoleAttackingMidfield::id => PlayerRoleAttackingMidfield::name,
        PlayerRoleCentralMidfield::id => PlayerRoleCentralMidfield::name,
        PlayerRoleLeftMidfield::id => PlayerRoleLeftMidfield::name,
        PlayerRoleRightMidfield::id => PlayerRoleRightMidfield::name,
        PlayerRoleLeftWing::id => PlayerRoleLeftWing::name,
        PlayerRoleCentreForward::id => PlayerRoleCentreForward::name,
        PlayerRoleForward::id => PlayerRoleForward::name,
        PlayerRoleStriker::id => PlayerRoleStriker::name,
        PlayerRoleSecondaryStriker::id => PlayerRoleSecondaryStriker::name,
        PlayerRoleRightWing::id => PlayerRoleRightWing::name,
        PlayerRoleSweeper::id => PlayerRoleSweeper::name,
    ];
}