<?php

namespace App\Type\PlayerRole;

use App\Serializable\PlayerRoleSerializable;

abstract class PlayerRole extends PlayerRoleSerializable
{
    const id = null;
    const name = null;

    abstract function getId(): int;
    abstract function getName(): string;
}