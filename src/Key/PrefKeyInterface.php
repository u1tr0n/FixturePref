<?php

namespace ultron\FixturePref\Key;

interface PrefKeyInterface
{
    public function getKey(): string;
    public function getGroup(): string;
}