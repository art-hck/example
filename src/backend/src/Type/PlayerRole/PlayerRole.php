<?php

namespace App\Type\PlayerRole;

abstract class PlayerRole
{
    const id = null;
    const name = null;

    abstract function getId(): int;
    abstract function getName(): string;
}