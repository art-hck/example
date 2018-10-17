<?php

namespace App\Type\PlayerRole\Types;

use App\Type\PlayerRole\PlayerRole;

class PlayerRoleDefensiveMidfield extends PlayerRole
{
    const id = 6;
    const name = 'defensive midfield';

    function getId(): int
    {
        return $this::id;
    }

    function getName(): string
    {
        return $this::name;
    }
}