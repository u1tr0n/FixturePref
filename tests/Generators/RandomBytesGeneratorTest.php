<?php

namespace ultron\FixturePref\Generators;

use Exception;
use PHPUnit\Framework\TestCase;

class RandomBytesGeneratorTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testCreateId(): void
    {
        $keySizes = [null, 20, 32, 36, 64, 128];

        foreach ($keySizes as $keySize) {
            if(null === $keySize){
                $generator = new RandomBytesGenerator();
                $this->assertSame(mb_strlen($generator->createId()), 72);
            } else {
                $generator = new RandomBytesGenerator($keySize);
                $this->assertSame(mb_strlen($generator->createId()), $keySize * 2);
            }
        }
    }
}
