<?php

namespace App\Type\PlayerRole\Types;

use App\Type\PlayerRole\PlayerRole;

class PlayerRoleAttackingMidfield extends PlayerRole
{
    const id = 8;
    const name = 'attacking midfield';

    function getId(): int
    {
        return $this::id;
    }

    function getName(): string
    {
        return $this::name;
    }
}