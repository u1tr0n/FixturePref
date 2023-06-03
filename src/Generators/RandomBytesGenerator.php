<?php

namespace ultron\FixturePref\Generators;

use Exception;

final class RandomBytesGenerator implements IdGeneratorInterface
{

    /**
     * @param positive-int $keySize
     */
    public function __construct(
        private readonly int $keySize = 36
    ) {
    }

    /**
     * @throws Exception
     */
    public function createId(): string
    {
        return bin2hex(random_bytes($this->keySize));
    }
}