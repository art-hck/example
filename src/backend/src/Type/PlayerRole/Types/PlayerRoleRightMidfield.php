<?php

namespace App\Type\PlayerRole\Types;

use App\Type\PlayerRole\PlayerRole;

class PlayerRoleRightMidfield extends PlayerRole
{
    const id = 11;
    const name = 'right midfield';

    function getId(): int
    {
        return $this::id;
    }

    function getName(): string
    {
        return $this::name;
    }
}