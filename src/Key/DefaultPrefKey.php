<?php

namespace ultron\FixturePref\Key;

use ultron\FixturePref\Key\PrefKeyInterface;

final class DefaultPrefKey implements PrefKeyInterface
{
    public function __construct(
        private readonly string $group,
        private readonly string $name = 'default',
    ) {
    }

    public function getKey(): string
    {
        return $this->name;
    }

    public function getGroup(): string
    {
        return $this->group;
    }
}