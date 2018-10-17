<?php

namespace App\Type\PlayerRole\Types;

use App\Type\PlayerRole\PlayerRole;

class PlayerRoleLeftBack extends PlayerRole
{
    const id = 3;
    const name = 'left back';

    function getId(): int
    {
        return $this::id;
    }

    function getName(): string
    {
        return $this::name;
    }
}