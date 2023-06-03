<?php

namespace ultron\FixturePref\Generators;

interface IdGeneratorInterface
{
    public function createId(): string;
}