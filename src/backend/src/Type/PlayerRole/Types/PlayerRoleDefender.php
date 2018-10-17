<?php

namespace App\Type\PlayerRole\Types;

use App\Type\PlayerRole\PlayerRole;

class PlayerRoleDefender extends PlayerRole
{
    const id = 2;
    const name = 'defender';

    function getId(): int
    {
        return $this::id;
    }

    function getName(): string
    {
        return $this::name;
    }
}